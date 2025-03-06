<?php

namespace Exxtensio\EcommerceDashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use ReflectionClass;

class RelationCollection extends ResourceCollection
{
    protected mixed $resourceClass;
    protected ?string $attribute;
    protected string $prefix;
    protected string $model;
    protected mixed $relation;

    protected array $search;

    public function __construct(string $resourceClass, ?string $attribute, string $component, $relation)
    {
        $this->resourceClass = $resourceClass;
        $this->model = $this->resourceClass::$model;
        $this->search = $this->resourceClass::$search ?? ['id'];
        $this->prefix = $this->resourceClass::$prefix;
        $this->attribute = $attribute;

        $this->relation = match ($component) {
            'belong-to-field','morph-one-field' => $relation->first() ?? null,
            default => null
        };

        parent::__construct(collect());
    }

    public function getPrefix(): string
    {
        return $this->prefix;
    }

    public function toArray(Request $request): array
    {
        return [
            'model' => $this->model,
            'attribute' => $this->attribute,
            'prefix' => $this->prefix,
            'title' => $this->resourceClass::$title ?? 'id',
            'label' => $this->resourceClass::$label,
            'singularLabel' => $this->resourceClass::$singularLabel,
            'search' => $this->search,
            'hasSoftDelete' => (new ReflectionClass($this->resourceClass::$model))->hasMethod('softDeleted'),
            'canCreate' => $this->resourceClass::$canCreate ?? true,
            'canDelete' => $this->resourceClass::$canDelete && $this->relation && $request->user()->can('delete', $this->relation),
            'canPreview' => $this->resourceClass::$canPreview && $this->relation && $request->user()->can('view', $this->relation),
            'canEdit' => $this->resourceClass::$canEdit && $this->relation && $request->user()->can('update', $this->relation)
        ];
    }
}
