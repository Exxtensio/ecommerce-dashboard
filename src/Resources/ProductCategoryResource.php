<?php

namespace Exxtensio\EcommerceDashboard\Resources;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Exxtensio\EcommerceDashboard\Fields;
use Exxtensio\EcommerceDashboard\Http\Controllers\ProductCategoryController;
use Exxtensio\EcommerceDashboard\Models\ProductCategory;

class ProductCategoryResource extends Resource
{
    public static string $model = ProductCategory::class;
    public static string $controller = ProductCategoryController::class;
    public static string $title = 'name';
    public static string $label = 'Categories';
    public static string $prefix = 'categories';
    public static string $singularLabel = 'Category';
    public static array $search = ['id', 'parent_id', 'name'];
    public static array $defaultColumns = ['id', 'parent_id', 'name', 'src', 'created_at', 'deleted_at'];
    public static array $defaultRelations = ['parent'];

    public $with = ['parent:id,name,parent_id'];
    protected static ?int $limitations = 5;

    public array $withOptions = [
        ProductCategoryResource::class => ProductCategory::class
    ];

    /**
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            Fields\ID::make('ID', 'id', $this)
                ->copyable(),

            Fields\BelongTo::make('Parent', 'parent', $this, ProductCategoryResource::class)
                ->nullable()
                ->searchable()
                ->panel('parent')
                ->fullWidth()
                ->rules(['required']),

            Fields\Image::make('Image', 'src', $this)
                ->path('categories')
                ->rules(array_merge(['nullable', 'image'], config('dashboard.validation_rules.categories.src'))),

            Fields\Text::make('Name', 'name', $this)
                ->rules(['required', 'string', 'max:255'])
                ->creationRules(['unique:product_categories,name'])
                ->updateRules([
                    Rule::unique('product_categories', 'name')->ignore($this->id)
                ])
                ->copyable()
                ->sortable(),

            Fields\Slug::make('Slug', 'slug', $this)
                ->from('name')
                ->rules(['required', 'string', 'max:255', 'regex:/^[a-zA-Z0-9-]+$/'])
                ->creationRules(['unique:product_categories,slug'])
                ->updateRules([
                    Rule::unique('product_categories', 'slug')->ignore($this->id)
                ]),

            Fields\Textarea::make('Summary', 'summary', $this)
                ->placeholder('Write the summary...')
                ->fullWidth()
                ->rules(['nullable', 'string', 'max:255']),

            Fields\Textarea::make('Description', 'description', $this)
                ->rows(6)
                ->fullWidth()
                ->placeholder('Write the description...')
                ->rules(['nullable', 'string', 'max:60000']),

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
}
