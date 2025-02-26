<?php

namespace Exxtensio\EcommerceDashboard\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Exxtensio\EcommerceDashboard\Contracts\Activity as ActivityContract;

/**
 * Exxtensio\EcommerceDashboard\Models\Activity.
 *
 * @property int $id
 * @property string|null $log_name
 * @property string $description
 * @property string|null $subject_type
 * @property int|null $subject_id
 * @property string|null $causer_type
 * @property int|null $causer_id
 * @property string|null $event
 * @property string|null $batch_uuid
 * @property Collection|null $properties
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Model|null $causer
 * @property-read Collection $changes
 * @property-read Model|null $subject
 *
 * @method static Builder|Activity causedBy(Model $causer)
 * @method static Builder|Activity forBatch(string $batchUuid)
 * @method static Builder|Activity forEvent(string $event)
 * @method static Builder|Activity forSubject(Model $subject)
 * @method static Builder|Activity hasBatch()
 * @method static Builder|Activity inLog($logNames)
 * @method static Builder|Activity newModelQuery()
 * @method static Builder|Activity newQuery()
 * @method static Builder|Activity query()
 */
class Activity extends Model implements ActivityContract
{
    use HasUlids;

    public $guarded = [];

    protected $table = 'log_activity';

    protected $appends = [
        'diffHumans'
    ];

    protected $casts = [
        'properties' => 'collection',
    ];

    public function getDiffHumansAttribute(): string
    {
        if(!$this->created_at) {
            return '';
        } else if(now()->subDay() > $this->created_at) {
            return $this->created_at->format('Y-m-d H:i:s');
        } else {
            return $this->created_at->diffForHumans();
        }
    }

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    /**
     * @return MorphTo
     */
    public function subject(): MorphTo
    {
        return $this->morphTo()->withTrashed();
    }

    /**
     * @return MorphTo
     */
    public function causer(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @param string $propertyName
     * @param mixed|null $defaultValue
     * @return mixed
     */
    public function getExtraProperty(string $propertyName, mixed $defaultValue = null): mixed
    {
        return Arr::get($this->properties->toArray(), $propertyName, $defaultValue);
    }

    /**
     * @return Collection
     */
    public function changes(): Collection
    {
        if (!$this->properties instanceof Collection) {
            return new Collection();
        }

        return $this->properties->only(['attributes', 'old']);
    }

    /**
     * @return Collection
     */
    public function getChangesAttribute(): Collection
    {
        return $this->changes();
    }

    /**
     * @param Builder $query
     * @param ...$logNames
     * @return Builder
     */
    public function scopeInLog(Builder $query, ...$logNames): Builder
    {
        if (is_array($logNames[0])) {
            $logNames = $logNames[0];
        }

        return $query->whereIn('log_name', $logNames);
    }

    /**
     * @param Builder $query
     * @param Model $causer
     * @return Builder
     */
    public function scopeCausedBy(Builder $query, Model $causer): Builder
    {
        return $query
            ->where('causer_type', $causer->getMorphClass())
            ->where('causer_id', $causer->getKey());
    }

    /**
     * @param Builder $query
     * @param Model $subject
     * @return Builder
     */
    public function scopeForSubject(Builder $query, Model $subject): Builder
    {
        return $query
            ->where('subject_type', $subject->getMorphClass())
            ->where('subject_id', $subject->getKey());
    }

    /**
     * @param Builder $query
     * @param string $event
     * @return Builder
     */
    public function scopeForEvent(Builder $query, string $event): Builder
    {
        return $query->where('event', $event);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeHasBatch(Builder $query): Builder
    {
        return $query->whereNotNull('batch_uuid');
    }

    /**
     * @param Builder $query
     * @param string $batchUuid
     * @return Builder
     */
    public function scopeForBatch(Builder $query, string $batchUuid): Builder
    {
        return $query->where('batch_uuid', $batchUuid);
    }
}
