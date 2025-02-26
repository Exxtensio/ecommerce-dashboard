<?php

namespace Exxtensio\EcommerceDashboard\Resources;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Exxtensio\EcommerceDashboard\Fields;
use Exxtensio\EcommerceDashboard\Filters;
use Exxtensio\EcommerceDashboard\Models;
use Exxtensio\EcommerceDashboard\Http\Controllers\ProductController;
use Exception;

class ProductResource extends Resource
{
    public static string $model = Models\Product::class;
    public static string $controller = ProductController::class;
    public static string $title = 'name';
    public static string $label = 'Products';
    public static string $prefix = 'products';
    public static string $singularLabel = 'Product';
    public static array $search = ['id', 'name', 'status'];
    public static array $defaultColumns = ['id', 'product_brand_id', 'name', 'slug', 'status', 'created_at', 'deleted_at'];
    public static array $defaultRelations = ['categories', 'attributes', 'brand', 'inventory', 'gallery'];
    protected static ?int $limitations = 50;

    public array $withOptions = [
        CountryResource::class => Models\Country::class,
        ProductBrandResource::class => Models\ProductBrand::class,
        ProductCategoryResource::class => Models\ProductCategory::class,
        ProductAttributeResource::class => Models\ProductAttribute::class,
    ];

    /**
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public function toArray(Request $request): array
    {
        $places = self::$model::getDefaultPlaces();
        $min = self::$model::getDefaultMin();
        $max = self::$model::getDefaultMax();

        return [
            Fields\ID::make('ID', 'id', $this)
                ->copyable(),

            Fields\Gallery::make('Gallery', 'gallery', $this)
                ->panel('gallery')
                ->fullWidth()
                ->path('products'),

            Fields\Badge::make('Status', 'status', $this)
                ->fullWidth()
                ->rules(['required'])
                ->map(self::$model::getStatusesMap())
                ->default(self::$model::getDefaultStatus())
                ->placeholder('Select a product status')
                ->options(self::$model::getStatuses()),

            Fields\BelongTo::make('Brand', 'brand', $this, ProductBrandResource::class)
                ->panel('brand')
                ->fullWidth()
                ->nullable()
                ->searchable()
                ->rules(['nullable']),

            Fields\BelongToMany::make('Categories', 'categories', $this, ProductCategoryResource::class)
                ->panel('categories')
                ->fullWidth()
                ->groupBy('name', 'children')
                ->rules(['nullable']),

            Fields\BelongToMany::make('Attributes', 'attributes', $this, ProductAttributeResource::class)
                ->panel('attributes')
                ->fullWidth()
                ->groupBy('name')
                ->rules(['nullable']),

            Fields\Inventory::make('Inventory', 'inventory', $this)
                ->panel('inventory')
                ->fullWidth(),

            Fields\Text::make('Name', 'name', $this)
                ->rules(['required', 'string', 'max:255'])
                ->creationRules(['unique:products,name'])
                ->updateRules([
                    Rule::unique('products', 'name')->ignore($this->id)
                ])
                ->copyable()
                ->sortable(),

            Fields\Slug::make('Slug', 'slug', $this)
                ->from('name')
                ->rules(['required', 'string', 'max:255', 'regex:/^[a-zA-Z0-9-]+$/'])
                ->creationRules(['unique:products,slug'])
                ->updateRules([
                    Rule::unique('products', 'slug')->ignore($this->id)
                ]),

            Fields\Textarea::make('Summary', 'summary', $this)
                ->fullWidth()
                ->placeholder('Write the summary...')
                ->rules(['nullable', 'string', 'max:255']),

            Fields\Textarea::make('Description', 'description', $this)
                ->rows(6)
                ->fullWidth()
                ->placeholder('Write the description...')
                ->rules(['nullable', 'string', 'max:60000']),

            Fields\Select::make('Type', 'type', $this)
                ->panel('type')
                ->rules(['required'])
                ->default(self::$model::getTypeDefault())
                ->placeholder('Select a product type')
                ->options(self::$model::getTypeOptions()),

            Fields\Select::make('Unit', 'unit', $this)
                ->panel('type')
                ->rules(['required'])
                ->dependOn('type', self::$model::getUnitDependOnArray()),

            Fields\Number::make('Step', 'step', $this)
                ->panel('type')
                ->default(self::$model::getDefaultStep())
                ->places($places)
                ->step(self::$model::getDefaultStep())
                ->min($min)
                ->max($max)
                ->rules(['required', !$places ? 'integer' : "decimal:$places", "min:$min", "max:$max"]),

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
            Filters\Select::make('Status', 'status')
                ->separatable()
                ->options(self::$model::getStatuses())
                ->searchable(),

            Filters\Select::make('Brand', 'brands', 'brand')
                ->searchable(),

            Filters\Select::make('Category', 'categories')
                ->searchable(),

            Filters\Select::make('Attributes', 'attributes')
                ->searchable()
        ];
    }
}
