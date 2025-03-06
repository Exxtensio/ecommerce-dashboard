<?php

namespace Exxtensio\EcommerceDashboard\Http\Controllers;

use Exxtensio\EcommerceDashboard\Events;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Exxtensio\EcommerceDashboard\Collection;
use Illuminate\Support\Facades\Storage;
use Exception;

class CollectionController
{
    public string $resourceClass;

    public function __construct($resourceClass)
    {
        $this->resourceClass = $resourceClass;
    }

    public function index(Request $request): Response
    {
        if($request->user()->cannot('viewAny', $this->resourceClass::$model))
            abort(403, 'You do not have permission to viewAny this resources.');

        return Inertia::render('Index', [
            'title' => $this->resourceClass::$label,
            'resource' => new Collection('index', $this->resourceClass, $request)
        ]);
    }

    public function create(Request $request): Response
    {
        if($request->user()->cannot('create', $this->resourceClass::$model))
            abort(403, 'You do not have permission to create this resource.');

        return Inertia::render('Create', [
            'title' => "Creating {$this->resourceClass::$singularLabel}",
            'resource' => new Collection('create', $this->resourceClass, $request),
            'extentOfLimitations' => $this->resourceClass::hasLimitations() && (new $this->resourceClass::$model)->count() >= $this->resourceClass::getLimitations(),
        ]);
    }

    public function show(Request $request): Response
    {
        return Inertia::render('Show', [
            'title' => $this->resourceClass::$singularLabel,
            'resource' => new Collection('show', $this->resourceClass, $request)
        ]);
    }

    public function edit(Request $request): Response
    {
        if(isLocked($this->resourceClass::$model, $request->route('id'), $request->user()))
            abort(403, 'You do not have permission to update this resource.');

        tryBroadcast(new Events\Locked($this->resourceClass, $request));
        return Inertia::render('Edit', [
            'title' => "Editing {$this->resourceClass::$singularLabel}",
            'resource' => new Collection('edit', $this->resourceClass, $request)
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $model = $this->resourceClass::$model::findOrFail($request->route('id'));

        if($request->user()->cannot('update', $model) || isLocked($model, $model->id, $request->user()))
            return response()->json(['message' => 'You do not have permission to update this resource'], 403);

        try {
            $fields = collect((new $this->resourceClass($model))->toArray($request))
                ->filter(fn($field) => $field->showOnUpdate)
                ->each(function ($field) use ($request) {
                    $field->validateToUpdate($request);
                });
        } catch (Exception $e) {
            return response()->json(json_decode($e->getMessage()), 422);
        }

        $request->validate(
            $fields->mapWithKeys(fn($field) => [
                $field->attribute => array_merge(
                    $field->rules,
                    $field->updateRules
                )
            ])->toArray()
        );

        $attributes = collect(
            $request->only(
                $fields->where('component', '!=', 'image-field')
                    ->whereNull('relation')
                    ->pluck('attribute')
                    ->toArray()
            )
        );

        collect($request->files)->map(function ($value, $key) use ($attributes, $fields) {
            $field = $fields->firstWhere('attribute', $key);
            if($field) {
                if($field->value) Storage::disk($field->getStorageDisk())->delete($field->value);
                $name = Str::random();
                $path = normalizePath("{$field->getStorageDir()}/$name.{$value->getClientOriginalExtension()}");
                Storage::disk($field->getStorageDisk())->put($path, file_get_contents($value));
                $attributes->put($key, $path);
            }
        });

        try {
            DB::transaction(function () use ($model, $attributes, $request) {
                $model->update(array_diff_assoc(
                    $attributes->toArray(),
                    $model->only($attributes->keys()->toArray())
                ));

                collect((new $this->resourceClass($model))->toArray($request))
                    ->filter(fn($field) => $field->showOnUpdate && $field->relation)
                    ->each(function ($field) use ($request, $model) {
                        $field->fillToUpdate($request, $model);
                    });
            });
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }

        return response()->json();
    }

    public function store(Request $request): JsonResponse
    {
        if($request->user()->cannot('create', $this->resourceClass::$model))
            return response()->json(['message' => 'You do not have permission to create this resource'], 403);

//        if($this->resourceClass::hasLimitations() && (new $this->resourceClass::$model)->count() >= $this->resourceClass::getLimitations())
//            return response()->json(['message' => 'Your license type does not allow you to create more resources'], 403);

        $model = new $this->resourceClass::$model;
        try {
            $fields = collect((new $this->resourceClass($model))->toArray($request))
                ->filter(fn($field) => $field->showOnCreation)
                ->each(function ($field) use ($request) {
                    $field->validateToUpdate($request);
                });
        } catch (Exception $e) {
            return response()->json(json_decode($e->getMessage()), 422);
        }

        $request->validate(
            $fields->mapWithKeys(fn($field) => [
                $field->attribute => array_merge(
                    $field->rules,
                    $field->creationRules
                )
            ])->toArray()
        );

        $attributes = collect(
            $request->only(
                $fields->where('component', '!=', 'image-field')
                    ->whereNull('relation')
                    ->pluck('attribute')
                    ->toArray()
            )
        );

        collect($request->files)->map(function ($value, $key) use ($attributes, $fields) {
            $field = $fields->firstWhere('attribute', $key);
            if($field) {
                $name = Str::random();
                $path = normalizePath("{$field->getStorageDir()}/$name.{$value->getClientOriginalExtension()}");
                Storage::disk($field->getStorageDisk())->put($path, file_get_contents($value));
                $attributes->put($key, $path);
            }
        });

        try {
            DB::transaction(function () use ($model, $attributes, $request) {
                $model = $model->create($attributes->toArray());

                collect((new $this->resourceClass($model))->toArray($request))
                    ->filter(fn($field) => $field->showOnCreation && $field->relation)
                    ->each(function ($field) use ($request, $model) {
                        $field->fillToUpdate($request, $model);
                    });
                });
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }

        return response()->json();
    }

    public function delete(Request $request): JsonResponse
    {
        $model = $this->resourceClass::$model::find($request->route('id'));

        if($request->user()->cannot('delete', $model) || isLocked($model, $model->id, $request->user()))
            return response()->json(['message' => 'You do not have permission to delete this resource'], 403);

        optional($model)->delete();

        return response()->json();
    }

    public function restore(Request $request): JsonResponse
    {
        $model = $this->resourceClass::$model::onlyTrashed()->find($request->route('id'));

        if($request->user()->cannot('delete', $model) || isLocked($model, $model->id, $request->user()))
            return response()->json(['message' => 'You do not have permission to restore this resource'], 403);

        optional($model)->restore();

        return response()->json();
    }

    public function force(Request $request): JsonResponse
    {
        $model = $this->resourceClass::$model::withTrashed()->find($request->route('id'));

        if($request->user()->cannot('delete', $model) || isLocked($model, $model->id, $request->user()))
            return response()->json(['message' => 'You do not have permission to forceDelete this resource'], 403);

        optional($model)->forceDelete();

        return response()->json();
    }

    public function actionDelete(Request $request): JsonResponse
    {
        $this->resourceClass::$model::whereIn('id', $this->excludeLocked($request))
            ->delete();

        return response()->json();
    }

    public function actionRestore(Request $request): JsonResponse
    {
        $this->resourceClass::$model::onlyTrashed()
            ->whereIn('id', $request->get('array'))
            ->restore();

        return response()->json();
    }

    public function actionForce(Request $request): JsonResponse
    {
        $this->resourceClass::$model::withTrashed()
            ->whereIn('id',  $this->excludeLocked($request))
            ->forceDelete();

        return response()->json();
    }

    public function setCache(Request $request): JsonResponse
    {
        Cache::put("{$this->resourceClass::$prefix}.index.{$request->get('key')}", $request->get('value'));
        Cache::put("{$this->resourceClass::$prefix}.index.relations", $request->get('relations'));
        return response()->json();
    }

    public function removeLocked(Request $request): void
    {
        $model = str_replace("\\", ".",  $this->resourceClass::$model);
        Cache::delete("$model:locked:{$request->get('id')}");
        tryBroadcast(new Events\Unlocked( $this->resourceClass, $request));
    }

    public function search(Request $request): Collection
    {
        return new Collection('search', $this->resourceClass, $request);
    }

    protected function excludeLocked($request): array
    {
        $class = getBroadcastingClass($this->resourceClass::$model);
        $exclude = DB::table('cache')
            ->where('key', 'like', "$class:locked:%")
            ->pluck('key')
            ->map(fn($key) => ['id' => explode(':', $key)[2], 'userId' => Cache::get($key)])
            ->where('userId', '!=', $request->user()->id)
            ->pluck('id')
            ->toArray();

        collect($request->get('array'))->map(function ($id) use ($class) {
            Cache::forget("$class:locked:$id");
        });

        return array_diff($request->get('array'), $exclude);
    }
}
