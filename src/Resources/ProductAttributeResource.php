<?php

namespace Exxtensio\EcommerceDashboard\Resources;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Exxtensio\EcommerceDashboard\Fields;
use Exxtensio\EcommerceDashboard\Filters;
use Exxtensio\EcommerceDashboard\Http\Controllers\ProductAttributeController;
use Exxtensio\EcommerceDashboard\Models;

class ProductAttributeResource extends Resource
{
    public static string $model = Models\ProductAttribute::class;
    public static string $controller = ProductAttributeController::class;
    public static string $title = 'value';
    public static string $label = 'Attributes';
    public static string $prefix = 'attributes';
    public static string $singularLabel = 'Attribute';
    public static array $search = ['id', 'key', 'value'];
    public static array $defaultColumns = ['id', 'key', 'value', 'created_at', 'deleted_at'];
    protected static ?int $limitations = 20;

    /**
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            Fields\ID::make('ID', 'id', $this)
                ->copyable(),

            Fields\Text::make('Key', 'key', $this)
                ->rules(['required', 'string', 'max:255'])
                ->creationRules([
                    Rule::unique('product_attributes', 'key')
                        ->where('value', $request->get('value'))
                ])
                ->updateRules([
                    Rule::unique('product_attributes', 'key')
                        ->where('value', $request->get('value'))
                        ->ignore($this->id)
                ])
                ->sortable(),

            Fields\Text::make('Value', 'value', $this)
                ->rules(['required', 'string', 'max:255'])
                ->creationRules([
                    Rule::unique('product_attributes', 'value')
                        ->where('key', $request->get('key'))
                ])
                ->updateRules([
                    Rule::unique('product_attributes', 'value')
                        ->where('key', $request->get('key'))
                        ->ignore($this->id)
                ])
                ->sortable(),

            Fields\Activities::make('Last Activities', 'activities', $this, self::class)
                ->hideFromIndex()->hideWhenCreating(),

            Fields\Timestamp::make('Created At', 'created_at', $this)
                ->sortable()
                ->diffForHumans(),

            Fields\Timestamp::make('Updated At', 'updated_at', $this)
                ->sortable()
                ->diffForHumans(),

            Fields\Timestamp::make('Deleted At', 'deleted_at', $this)
                ->diffForHumans()
        ];
    }

    /**
     * @param Request $request
     * @return array
     */
    public function filters(Request $request): array
    {
        return [
            Filters\Select::make('Key', 'key')
                ->separatable()
                ->options(
                    Models\ProductAttribute::all()
                        ->pluck('key', 'key')
                        ->toArray()
                )
                ->searchable(),
        ];
    }
}
