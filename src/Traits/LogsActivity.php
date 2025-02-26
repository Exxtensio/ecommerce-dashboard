<?php

namespace Exxtensio\EcommerceDashboard\Traits;

use Carbon\CarbonInterval;
use DateInterval;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Exxtensio\EcommerceDashboard\ActivityLogger;
use Exxtensio\EcommerceDashboard\ActivityLogStatus;
use Exxtensio\EcommerceDashboard\Contracts\LoggablePipe;
use Exxtensio\EcommerceDashboard\EventLogBag;
use Exxtensio\EcommerceDashboard\LogOptions;
use Exxtensio\EcommerceDashboard\Models\Activity;

trait LogsActivity
{
    public static array $changesPipes = [];
    protected array $oldAttributes = [];
    protected ?LogOptions $activitylogOptions;
    public bool $enableLoggingModelsEvents = true;

    abstract public function getActivitylogOptions(): LogOptions;

    protected static function bootLogsActivity(): void
    {
        static::eventsToBeRecorded()->each(function ($eventName) {
            if ($eventName === 'updated') {
                static::updating(function (Model $model) {
                    $oldValues = (new static())->setRawAttributes($model->getRawOriginal());
                    $model->oldAttributes = static::logChanges($oldValues);
                });
            }

            static::$eventName(function (Model $model) use ($eventName) {
                $model->activitylogOptions = $model->getActivitylogOptions();

                if (!$model->shouldLogEvent($eventName))
                    return;

                $changes = $model->attributeValuesToBeLogged($eventName);

                $description = $model->getDescriptionForEvent($eventName);

                $logName = $model->getLogNameToUse();

                if ($description == '')
                    return;

                if ($model->isLogEmpty($changes) && !$model->activitylogOptions->submitEmptyLogs)
                    return;

                $event = app(Pipeline::class)
                    ->send(new EventLogBag($eventName, $model, $changes, $model->activitylogOptions))
                    ->through(static::$changesPipes)
                    ->thenReturn();

                $logger = app(ActivityLogger::class)
                    ->useLog($logName)
                    ->event($eventName)
                    ->performedOn($model)
                    ->withProperties($event->changes);

                if (method_exists($model, 'tapActivity'))
                    $logger->tap([$model, 'tapActivity'], $eventName);

                $logger->log($description);

                $model->activitylogOptions = null;
            });
        });
    }

    public static function addLogChange(LoggablePipe $pipe): void
    {
        static::$changesPipes[] = $pipe;
    }

    public function isLogEmpty(array $changes): bool
    {
        return empty($changes['attributes'] ?? []) && empty($changes['old'] ?? []);
    }

    public function disableLogging(): self
    {
        $this->enableLoggingModelsEvents = false;

        return $this;
    }

    public function enableLogging(): self
    {
        $this->enableLoggingModelsEvents = true;

        return $this;
    }

    public function activities(): MorphMany
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        if (!empty($this->activitylogOptions->descriptionForEvent))
            return ($this->activitylogOptions->descriptionForEvent)($eventName);

        return $eventName;
    }

    public function getLogNameToUse(): ?string
    {
        if (!empty($this->activitylogOptions->logName))
            return $this->activitylogOptions->logName;

        return 'default';
    }

    protected static function eventsToBeRecorded(): Collection
    {
        if (isset(static::$recordEvents))
            return collect(static::$recordEvents);

        $events = collect(['created', 'updated', 'deleted']);
        if (collect(class_uses_recursive(static::class))->contains(SoftDeletes::class))
            $events->push('restored');

        return $events;
    }

    protected function shouldLogEvent(string $eventName): bool
    {
        $logStatus = app(ActivityLogStatus::class);

        if (!$this->enableLoggingModelsEvents || $logStatus->disabled())
            return false;

        if (!in_array($eventName, ['created', 'updated']))
            return true;

        if ($this->isRestoring())
            return false;

        return (bool)count(Arr::except($this->getDirty(), $this->activitylogOptions->dontLogIfAttributesChangedOnly));
    }

    protected function isRestoring(): bool
    {
        $deletedAtColumn = method_exists($this, 'getDeletedAtColumn')
            ? $this->getDeletedAtColumn()
            : 'deleted_at';

        return $this->isDirty($deletedAtColumn) && count($this->getDirty()) === 1;
    }

    public function attributesToBeLogged(): array
    {
        $this->activitylogOptions = $this->getActivitylogOptions();

        $attributes = [];

        if ($this->activitylogOptions->logFillable)
            $attributes = array_merge($attributes, $this->getFillable());

        if ($this->shouldLogUnguarded())
            $attributes = array_merge($attributes, array_diff(array_keys($this->getAttributes()), $this->getGuarded()));

        if (!empty($this->activitylogOptions->logAttributes)) {
            $attributes = array_merge($attributes, array_diff($this->activitylogOptions->logAttributes, ['*']));

            if (in_array('*', $this->activitylogOptions->logAttributes))
                $attributes = array_merge($attributes, array_keys($this->getAttributes()));
        }

        if ($this->activitylogOptions->logExceptAttributes)
            $attributes = array_diff($attributes, $this->activitylogOptions->logExceptAttributes);

        return $attributes;
    }

    public function shouldLogUnguarded(): bool
    {
        if (!$this->activitylogOptions->logUnguarded)
            return false;

        if (in_array('*', $this->getGuarded()))
            return false;

        return true;
    }

    public function attributeValuesToBeLogged(string $processingEvent): array
    {
        if (!count($this->attributesToBeLogged()))
            return [];

        $properties['attributes'] = static::logChanges(
            $processingEvent == 'retrieved' ? $this : ($this->exists ? $this->fresh() ?? $this : $this)
        );

        if (static::eventsToBeRecorded()->contains('updated') && $processingEvent == 'updated') {

            $nullProperties = array_fill_keys(array_keys($properties['attributes']), null);
            $properties['old'] = array_merge($nullProperties, $this->oldAttributes);
            $this->oldAttributes = [];
        }

        if ($this->activitylogOptions->logOnlyDirty && isset($properties['old'])) {

            $properties['attributes'] = array_udiff_assoc(
                $properties['attributes'],
                $properties['old'],
                function ($new, $old) {
                    if ($old === null || $new === null)
                        return $new === $old ? 0 : 1;

                    if ($old instanceof DateInterval) {
                        return CarbonInterval::make($old)->equalTo($new) ? 0 : 1;
                    } elseif ($new instanceof DateInterval) {
                        return CarbonInterval::make($new)->equalTo($old) ? 0 : 1;
                    }

                    return $new <=> $old;
                }
            );

            $properties['old'] = collect($properties['old'])
                ->only(array_keys($properties['attributes']))
                ->all();
        }

        if (static::eventsToBeRecorded()->contains('deleted') && $processingEvent == 'deleted') {
            $properties['old'] = $properties['attributes'];
            unset($properties['attributes']);
        }

        return $properties;
    }

    public static function logChanges(Model $model): array
    {
        $changes = [];
        $attributes = $model->attributesToBeLogged();

        foreach ($attributes as $attribute) {
            if (Str::contains($attribute, '.')) {
                $changes += self::getRelatedModelAttributeValue($model, $attribute);
                continue;
            }

            if (Str::contains($attribute, '->')) {
                Arr::set(
                    $changes,
                    str_replace('->', '.', $attribute),
                    static::getModelAttributeJsonValue($model, $attribute)
                );

                continue;
            }

            $changes[$attribute] = in_array($attribute, $model->activitylogOptions->attributeRawValues)
                ? $model->getAttributeFromArray($attribute)
                : $model->getAttribute($attribute);

            if (is_null($changes[$attribute]))
                continue;

            if ($model->isDateAttribute($attribute))
                $changes[$attribute] = $model->serializeDate(
                    $model->asDateTime($changes[$attribute])
                );

            if ($model->hasCast($attribute)) {
                $cast = $model->getCasts()[$attribute];

                if ($model->isEnumCastable($attribute)) {
                    try {
                        $changes[$attribute] = $model->getStorableEnumValue($changes[$attribute]);
                    } catch (\ArgumentCountError $e) {
                        $changes[$attribute] = $model->getStorableEnumValue($cast, $changes[$attribute]);
                    }
                }

                if ($model->isCustomDateTimeCast($cast) || $model->isImmutableCustomDateTimeCast($cast))
                    $changes[$attribute] = $model->asDateTime($changes[$attribute])->format(explode(':', $cast, 2)[1]);
            }
        }

        return $changes;
    }

    protected static function getRelatedModelAttributeValue(Model $model, string $attribute): array
    {
        $relatedModelNames = explode('.', $attribute);
        $relatedAttribute = array_pop($relatedModelNames);

        $attributeName = [];
        $relatedModel = $model;

        do {
            $attributeName[] = $relatedModelName = static::getRelatedModelRelationName($relatedModel, array_shift($relatedModelNames));

            $relatedModel = $relatedModel->$relatedModelName ?? $relatedModel->$relatedModelName();
        } while (!empty($relatedModelNames));

        $attributeName[] = $relatedAttribute;

        return [implode('.', $attributeName) => $relatedModel->$relatedAttribute ?? null];
    }

    protected static function getRelatedModelRelationName(Model $model, string $relation): string
    {
        return Arr::first([
            $relation,
            Str::snake($relation),
            Str::camel($relation),
        ], function (string $method) use ($model): bool {
            return method_exists($model, $method);
        }, $relation);
    }

    protected static function getModelAttributeJsonValue(Model $model, string $attribute): mixed
    {
        $path = explode('->', $attribute);
        $modelAttribute = array_shift($path);
        $modelAttribute = collect($model->getAttribute($modelAttribute));

        return data_get($modelAttribute, implode('.', $path));
    }
}
