<?php

namespace Exxtensio\EcommerceDashboard\Fields;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ReflectionClass;
use Exxtensio\EcommerceDashboard\Models\Product;
use Exxtensio\EcommerceDashboard\RelationCollection;
use Exxtensio\EcommerceDashboard\Traits;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Http\Resources\MissingValue;
use Exception;
use Throwable;

abstract class Field extends FieldElement
{
    use Macroable,
        Traits\HasHelpText,
        Traits\HasRules,
        Traits\Resolvable,
        Traits\Sortable,
        Traits\Readable,
        Traits\HasPanel,
        Traits\HasWidth;

    public string $name;
    public ?string $attribute = null;
    public mixed $value = null;
    public bool $relatable = false;
    public mixed $relation = null;

    protected function __construct(string $name, string $attribute, mixed $model = null, mixed $relationResourceClass = null)
    {
        $this->name = $name;
        $this->attribute = $attribute;

        if (in_array($this->component, app('dashboard')->relationComponents())) {
            $relation = !$this->relatable ? null : $model->resource->{$attribute}();

            $this->setRelationAttributes($model, $attribute, $relation);

            $this->value = match ($this->component) {
                'belong-to-field' => $model->{$this->attribute}->id ?? null,
                'morph-one-field' => $model->{$this->attribute}()->count() ? json_decode((string)$model->{$this->attribute}()->first()) : null,
                'activities-field' => $model->{$this->attribute}()->count() ? json_decode((string)$model->{$this->attribute}()->orderBy('created_at', 'desc')->take(7)->get()) : null,
                'permissions-field', 'belong-to-many-field' => $model->{$this->attribute}()->count() ? json_decode((string)$model->{$this->attribute}) : null,
                'inventory-field' => (function () use ($model) {
                    $prices = $model->prices()->pluck('price', 'country');
                    $stocks = $model->stocks()->pluck('stock', 'country');
                    return $prices->map(function ($price, $country) use ($stocks) {
                        return [
                            'country' => $country,
                            'stock' => $stocks->get($country),
                            'price' => $price,
                        ];
                    })->values()->toArray() ?? [];
                })(),
                'cart-items-field' => (function () use ($model) {
                    return $model->items->map(function ($item) {
                        $product = $item->product;
                        $prices = $product->prices()->pluck('price', 'country');
                        $stocks = $product->stocks()->pluck('stock', 'country');
                        $country = $item->cart->country;
                        return [
                            'quantity' => $item->quantity,
                            'item_price' => $item->price,
                            'country' => $country,
                            'id' => $product->id,
                            'name' => $product->name,
                            'stock' => $stocks->get($country),
                            'price' => $prices->get($country)
                        ];
                    })->values()->toArray() ?? [];
                })(),
                'gallery-field' => (function () use ($model) {
                    return $model->images->map(function ($image) {
                        return [
                            'id' => $image->id,
                            'default' => $image->default,
                            'src' => Storage::disk($this->getStorageDisk())->url($image->src),
                        ];
                    })->sortByDesc('default')->values()->toArray() ?? [];
                })(),
                default => $model->{$this->attribute} ?? null
            };

            if (is_array($relationResourceClass)) {
                $relationResourceClass = collect($relationResourceClass)
                    ->firstWhere(function ($class) use ($relation) {
                        $reflection = new ReflectionClass($class);
                        $modelClass = $this->component === 'activities-field' ? get_class($relation->getParent()) : get_class($relation->getRelated());
                        return $reflection->getMethod('getModel')->invoke($reflection->newInstance($class)) === $modelClass;
                    });
            }

            if ($this->component === 'inventory-field') {
                $this->relation = 'inventory';
            } else if ($this->component === 'gallery-field') {
                $this->relation = 'gallery';
            } else if ($relationResourceClass) {
                $this->relation = (object)(new RelationCollection($relationResourceClass, $attribute, $this->component, $relation))
                    ->toArray(request());
            }

        } else {
            $this->value = $model->{$attribute} ?? null;

            if ($model->whenHas($this->attribute) instanceof MissingValue)
                $this->hideFromIndex();
        }

        $this->setPlaceholder($name);
    }

    protected function setRelationAttributes($model, $attribute, $relation = null): void
    {
        $this->attribute = $relation && property_exists($relation, 'relationName') ? $relation->getRelationName() : $attribute;
        $this->relationName = $this->attribute;

        if ($relation && property_exists($relation, 'foreignKey') && property_exists($this, 'foreignKey'))
            $this->foreignKey = $relation->getForeignKeyName();

        if ($relation && property_exists($relation, 'morphType') && property_exists($this, 'morphType'))
            $this->morphType = $relation->getMorphType();

        if (property_exists($this, 'morphValue'))
            $this->morphValue = (object)[
                'id' => $model->{$this->foreignKey} ?? null,
                'type' => $model->{$this->morphType} ?? null
            ];


    }

    /** @throws Exception */
    public function validateToUpdate($request): static
    {
        if ($this->component === 'key-value-field') {
            $collection = collect($request->get($this->attribute));
            if ($collection->duplicates('key')->count()) {
                $message = [
                    'message' => "The $this->attribute field has a duplicate keys.",
                    'errors' => [
                        $this->attribute => ["The $this->attribute field has a duplicate keys."]
                    ]
                ];
                throw new Exception(json_encode($message));
            }

            $request->request->set($this->attribute, $collection->pluck('value', 'key')->filter()->toArray());
        }
        return $this;
    }

    /**
     * @throws Exception
     * @throws Throwable
     */
    public function fillToUpdate($request, $model): void
    {
        if ($this->component === 'belong-to-field') {

            $oldRelation = $model->{$this->attribute};
            $newRelation = $model->{$this->attribute}()->getModel()->find($request->get($this->attribute));

            if ((string)$oldRelation !== (string)$newRelation) {
                if ($oldRelation) $model->{$this->attribute}()->dissociate();
                if ($newRelation) $model->{$this->attribute}()->associate($newRelation);
                $model->save();

                activity()
                    ->performedOn($model)
                    ->causedBy(auth()->user())
                    ->withProperties([
                        'old' => $oldRelation ? json_decode((string)$oldRelation, true) : null,
                        'attributes' => $newRelation ? json_decode((string)$newRelation, true) : null
                    ])
                    ->event('associated')
                    ->log(class_basename($model->{$this->attribute}()->getModel()));
            }
        } else if ($this->component === 'morph-one-field') {

            $oldRelation = (string)$model->{$this->attribute}->makeHidden('pivot')->first();
            $newRelation = (string)$model->{$this->attribute}()->getModel()->find($request->get($this->attribute));

            if ($oldRelation !== $newRelation) {
                $model->{$this->attribute}()->sync($request->get($this->attribute));
                activity()
                    ->performedOn($model)
                    ->causedBy(auth()->user())
                    ->withProperties([
                        'old' => json_decode($oldRelation, true),
                        'attributes' => json_decode($newRelation, true)
                    ])
                    ->event('synced')
                    ->log(class_basename($model->{$this->attribute}()->getModel()));
            }
        } else if ($this->component === 'permissions-field' || $this->component === 'belong-to-many-field') {

            $oldRelation = $model->{$this->attribute} ? $model->{$this->attribute}()->pluck('id')->toArray() : null;
            $newRelation = $model->{$this->attribute}()->getModel()->find($request->get($this->attribute))
                ? $model->{$this->attribute}()->getModel()->find($request->get($this->attribute))->pluck('id')->toArray()
                : null;

            if ($oldRelation !== $newRelation) {
                $model->{$this->attribute}()->sync($request->get($this->attribute));

                activity()
                    ->performedOn($model)
                    ->causedBy(auth()->user())
                    ->withProperties([
                        'old' => $oldRelation,
                        'attributes' => $newRelation
                    ])
                    ->event('synced')
                    ->log(class_basename($model->{$this->attribute}()->getModel()));
            }
        } else if ($this->component === 'inventory-field') {
            $prices = $model->prices()->pluck('price', 'country');
            $stocks = $model->stocks()->pluck('stock', 'country');
            $oldRelation = $prices->map(function ($price, $country) use ($stocks) {
                return [
                    'country' => $country,
                    'stock' => number_format($stocks->get($country), 2, '.', ''),
                    'price' => number_format($price, 2, '.', ''),
                ];
            })->values()->toArray() ?? [];
            $newRelation = json_decode($request->get($this->attribute), true);

            $product = Product::findOrFail($model->id);
            collect($newRelation)->map(function ($c) use ($product) {
                $currentPrice = $product->prices()->where('country', $c['country'])->first();
                $currentStock = $product->prices()->where('country', $c['country'])->first();
                if ($currentPrice) {
                    $currentPrice->update(['price' => $c['price']]);
                } else {
                    $product->prices()->create([
                        'price' => $c['price'],
                        'country' => $c['country']
                    ]);
                }
                if ($currentStock) {
                    $currentStock->update(['stock' => $c['stock']]);
                } else {
                    $product->stocks()->create([
                        'stock' => $c['stock'],
                        'country' => $c['country']
                    ]);
                }
            });

            if ($oldRelation !== $newRelation) {
                activity()
                    ->performedOn($model)
                    ->causedBy(auth()->user())
                    ->withProperties([
                        'old' => $oldRelation,
                        'attributes' => $newRelation
                    ])
                    ->event('synced')
                    ->log('ProductInventory');
            }
        } else if ($this->component === 'gallery-field') {

            $oldRelation = $model->images->map(fn($image) => [
                'src' => $image->src,
                'default' => $image->default
            ])->values()->toArray() ?? [];

            collect($request->get('gallery'))->map(function ($image) use ($model, $request) {
                $img = json_decode($image);
                $value = $request->file($img->id);
                if ($value) {
                    $name = Str::random();
                    $path = normalizePath("{$this->getStorageDir()}/$model->id/$name.{$value->getClientOriginalExtension()}");
                    Storage::disk($this->getStorageDisk())->put($path, file_get_contents($value));

                    $model->images()->create([
                        'src' => $path,
                        'default' => $img->default,
                    ]);
                } else if ($img->deleted) {
                    $model->images()
                        ->where('id', $img->id)
                        ->delete();
                } else {
                    $model->images()
                        ->where('id', $img->id)
                        ->update(['default' => $img->default]);
                }
            });

            $newRelation = Product::find($model->id)->images->map(fn($image) => [
                'src' => $image->src,
                'default' => $image->default
            ])->values()->toArray() ?? [];

            if ($oldRelation !== $newRelation) {
                activity()
                    ->performedOn($model)
                    ->causedBy(auth()->user())
                    ->withProperties([
                        'old' => $oldRelation,
                        'attributes' => $newRelation
                    ])
                    ->event('synced')
                    ->log('ProductImage');
            }
        }
    }

    private function setPlaceholder($name): void
    {
        if (property_exists($this, 'placeholder'))
            $this->placeholder = $name;
    }
}
