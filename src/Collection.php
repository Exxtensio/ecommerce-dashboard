<?php

namespace Exxtensio\EcommerceDashboard;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Cache;
use ReflectionClass;

class Collection extends ResourceCollection
{
    protected mixed $resourceClass;
    protected ?Request $request;

    protected bool $isCollection;
    protected ?string $method;
    protected string $prefix;
    protected string $model;
    protected string $sort;
    protected string $order;
    protected array $search;
    protected ?array $columns;
    protected array $filters;
    protected ?array $relations;
    protected int|string $perPage;
    protected string $trashed;
    protected int|string $page;

    public function __construct(?string $method, string $resourceClass, Request $request)
    {
        $this->method = $method;
        $this->request = $this->method === 'index' ? null : $request;
        $this->isCollection = $this->method === 'index' || $this->method === 'search';

        $this->resourceClass = $resourceClass;
        $this->model = $this->resourceClass::$model;
        $this->search = $this->resourceClass::$search ?? ['id'];
        $this->prefix = $this->resourceClass::$prefix;

        $this->sort = Cache::get("$this->prefix.index.sort") ?? 'id';
        $this->order = Cache::get("$this->prefix.index.order") ?? 'asc';
        $this->perPage = Cache::get("$this->prefix.index.per_page") ?? 15;
        $this->trashed = Cache::get("$this->prefix.index.trashed") ?? 'without';
        $this->page = Cache::get("$this->prefix.index.page") ?? 1;
        $this->columns = Cache::get("$this->prefix.index.columns") ?? $this->resourceClass::$defaultColumns ?? ['*'];
        $this->filters = Cache::get("$this->prefix.index.filters") ?? [];
        $this->relations = Cache::get("$this->prefix.index.relations") ?? $this->resourceClass::$defaultRelations ?? null;

        if ($this->isCollection) {
            $model = match ($this->trashed) {
                'with' => $this->model::query()->withTrashed(),
                'only' => $this->model::query()->onlyTrashed(),
                'without' => $this->model::query()
            };

            $collection = $model->when($this->request, function ($query) {
                $query->when($this->request->has('search'), function (Builder $query) {
                    $query->where(function (Builder $query) {
                        $query->where('id', 'like', "%{$this->request->get('search')}%");
                        collect($this->search)->map(function ($key) use ($query) {
                            if ($key !== 'id') $query->orWhere($key, 'like', "%{$this->request->get('search')}%");
                        });
                    });
                    return $query;
                });
            })->when(collect($this->filters)->where('relation', false)->count(), function (Builder $query) {
                collect($this->filters)->where('relation', false)->map(function ($value, $key) use ($query) {
                    $query->where($value['key'], $value['value']);
                });
                return $query;
            })->when(collect($this->filters)->where('relation', true)->count(), function (Builder $query) {
                collect($this->filters)->where('relation', true)->map(function ($value, $key) use ($query) {
                    $query->whereHas($value['key'], function ($q) use ($value) {
                        $q->where('id', $value['value']);
                    });
                });
                return $query;
            })->orderBy($this->sort, $this->order)
            ->when($this->sort !== 'id', function ($query) {
                $query->orderBy('id', 'asc');
            })
            ->paginate($this->perPage, $this->columns, 'page', $this->request && $this->request->has('search') ? 1 : $this->page);
        }

        parent::__construct($collection ?? collect());
    }

    public function getPrefix(): string
    {
        return $this->prefix;
    }

    public function toArray(Request $request): array
    {
        $collection = null;
        $fields = null;

        if($this->isCollection) {
            $collection = $this->collection;
            $fields = $this->getCollectionFields($collection, $request);
        } else if ($this->method === 'show' || $this->method === 'edit') {
            $collection = $this->model::findOrFail($this->request->route('id'));

            if($request->user()->cannot($this->method === 'show' ? 'view' : 'update', $collection))
                abort(403, $this->method === 'show'
                    ? 'You do not have permission to view this resource.'
                    : 'You do not have permission to update this resource.');

            $fields = $this->getCollectionFields($collection, $request);
        }

        $newResource = (new $this->resourceClass(new $this->model()));

        return [
            'model' => $this->model,
            'prefix' => $this->prefix,
            'title' => $this->resourceClass::$title ?? 'id',
            'label' => __($this->resourceClass::$label),
            'singularLabel' => __($this->resourceClass::$singularLabel),
            'search' => $this->method === 'index' ? $this->search : [],
            'perPage' => $this->method === 'index' ? $this->resourceClass::$perPage ?? [10, 15, 25] : [],
            'columns' => $this->method === 'index' || $this->method === 'create' ? $this->getCollectionColumns($newResource, $request) : [],
            'fields' => !$fields ? [] : $fields,
            'relations' => $this->getCollectionRelations($newResource, $fields),
            'filters' => $this->method === 'index' ? $newResource->filters($request) : [],
            'permissions' => !$collection ? [] : $this->getCollectionPermissions($collection, $request),
            'options' => $this->method === 'index' ? [
                'sort' => $this->sort,
                'order' => $this->order,
                'perPage' => $this->perPage,
                'trashed' => $this->trashed,
                'filters' => $this->filters,
            ] : [],
            'hasSoftDelete' => (new ReflectionClass($this->resourceClass::$model))->hasMethod('softDeleted'),
            'canCreate' => $this->resourceClass::$canCreate ?? true
        ];
    }

    private function getCollectionFields($collection, $request): array
    {
        return $this->isCollection
            ? collect($this->resourceClass::collection($collection)->toArray($request))
                ->each(function ($item) {
                    collect($item)->each(function ($field) {
                        if (method_exists($field, 'resolved')) $field->resolved($this->relations);
                        if (method_exists($field, 'resolvedForUpdate')) $field->resolvedForUpdate();
                        if (method_exists($field, 'resolvedForDisplay')) $field->resolvedForDisplay();
                    });
                })->toArray()
            : collect((new $this->resourceClass($collection))->toArray($request))
                ->each(function ($field) {
                    if (method_exists($field, 'resolved')) $field->resolved($this->relations);
                    if (method_exists($field, 'resolvedForUpdate')) $field->resolvedForUpdate();
                    if (method_exists($field, 'resolvedForDisplay')) $field->resolvedForDisplay();
                })->toArray();
    }

    private function getCollectionColumns($newResource, $request): array
    {
        return collect($newResource->toArray($request))->each(function ($field) {
            if (method_exists($field, 'resolved')) $field->resolved($this->relations);
            if (method_exists($field, 'resolvedForUpdate')) $field->resolvedForUpdate();
            if (method_exists($field, 'resolvedForDisplay')) $field->resolvedForDisplay();
        })->toArray();
    }

    private function getCollectionPermissions($collection, $request): array
    {
        return $this->isCollection
            ? $this->collection->mapWithKeys(function ($item) use ($request) {
                return [
                    "item-$item->id" => [
                        'canDelete' => $this->resourceClass::$canDelete && $request->user()->can('delete', $item),
                        'canPreview' => $this->resourceClass::$canPreview && $request->user()->can('view', $item),
                        'canEdit' => $this->resourceClass::$canEdit && $request->user()->can('update', $item),
                    ]
                ];
            })->toArray()
            : ["item-$collection->id" => [
                'canDelete' => $this->resourceClass::$canDelete && $request->user()->can('delete', $collection),
                'canPreview' => $this->resourceClass::$canPreview && $request->user()->can('view', $collection),
                'canEdit' => $this->resourceClass::$canEdit && $request->user()->can('update', $collection),
            ]];
    }

    private function getCollectionRelations($newResource, $fields): array
    {
        $except = !$this->isCollection ? collect($fields)->firstWhere('component', 'id-field')->value ?? null : null;

        return collect($newResource->withOptions)->mapWithKeys(function ($m, $r) use ($newResource, $except) {
            $relatedResource = (new $r(new $m));
            return [
                $relatedResource::$prefix => (new $relatedResource::$model())::when($except && get_class($newResource) === get_class($relatedResource), function ($query) use ($except) {
                    $query->where('id', '!=', $except);
                })->with($relatedResource->with ?? [])->get()
                    ->map(function ($item) use ($relatedResource) {
                        if ($relatedResource::$prefix === 'permissions') {
                            return [
                                'name' => $item[$relatedResource::$title],
                                'group' => $item->group,
                                'value' => $item->id
                            ];
                        } else if ($relatedResource::$prefix === 'attributes') {
                            return [
                                'name' => $item[$relatedResource::$title],
                                'key' => $item->key,
                                'value' => $item->id
                            ];
                        } else if ($relatedResource::$prefix === 'countries') {
                            return [
                                'name' => $item[$relatedResource::$title],
                                'code' => $item->code,
                                'flag' => $item->flag,
                                'value' => $item->id,
                                'active' => $item->active,
                                'relations' => [
                                    'currency' => json_decode(json_encode(
                                        $item->currency()
                                            ->select('id', 'name', 'code', 'symbol', 'rate', 'fixed_rate')
                                            ->first()
                                    ), true),
                                ]
                            ];
                        } else if ($relatedResource::$prefix === 'categories') {
                            return [
                                'name' => $item[$relatedResource::$title],
                                'parent_id' => $item->parent_id,
                                'value' => $item->id,
                                'relations' => json_decode(json_encode($item->getRelations()), true),
                            ];
                        } else if (count($relatedResource->with)) {
                            return [
                                'name' => $item[$relatedResource::$title],
                                'value' => $item->id,
                                'relations' => json_decode(json_encode($item->getRelations()), true),
                            ];
                        }
                        return [
                            'name' => $item[$relatedResource::$title],
                            'value' => $item->id
                        ];
                    })
                    ->sortBy('name')
                    ->values()
                    ->toArray()
            ];
        })->toArray();
    }
}
