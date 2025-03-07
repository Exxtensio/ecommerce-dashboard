<?php

namespace Exxtensio\EcommerceDashboard\Resources;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Exxtensio\EcommerceDashboard\Fields;
use Exxtensio\EcommerceDashboard\Http\Controllers\ProductBrandController;
use Exxtensio\EcommerceDashboard\Models;

class ProductBrandResource extends Resource
{
    public static string $model = Models\ProductBrand::class;
    public static string $controller = ProductBrandController::class;
    public static string $title = 'name';
    public static string $label = 'Brands';
    public static string $prefix = 'brands';
    public static string $singularLabel = 'Brand';
    public static array $search = ['id', 'name'];
    public static array $defaultColumns = ['id', 'name', 'src', 'created_at', 'deleted_at'];
    protected static ?int $limitations = 10;

    /**
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            Fields\ID::make('ID', 'id', $this)
                ->copyable(),

            Fields\Image::make('Image', 'src', $this)
                ->path('brands')
                ->rules(array_merge(['nullable', 'image'], config('dashboard.validation_rules.brands.src'))),

            Fields\Text::make('Name', 'name', $this)
                ->rules(['required', 'string', 'max:255'])
                ->creationRules(['unique:product_brands,name'])
                ->updateRules([
                    Rule::unique('product_brands', 'name')->ignore($this->id)
                ])
                ->copyable()
                ->sortable(),

            Fields\Slug::make('Slug', 'slug', $this)
                ->from('name')
                ->rules(['required', 'string', 'max:255', 'regex:/^[a-zA-Z0-9-]+$/'])
                ->creationRules(['unique:product_brands,slug'])
                ->updateRules([
                    Rule::unique('product_brands', 'slug')->ignore($this->id)
                ]),

            Fields\Textarea::make('Summary', 'summary', $this)
                ->fullWidth()
                ->placeholder('Write the summary...')
                ->rules(['required', 'string', 'max:255']),

            Fields\Textarea::make('Description', 'description', $this)
                ->rows(6)
                ->fullWidth()
                ->placeholder('Write the description...')
                ->rules(['required', 'string', 'max:60000']),

            Fields\Text::make('Meta Title', 'meta_title', $this)
                ->hideFromColumns()
                ->fullWidth()
                ->panel('seo')
                ->rules(['sometimes', 'max:255']),

            Fields\Textarea::make('Meta Description', 'meta_description', $this)
                ->rows(6)
                ->fullWidth()
                ->hideFromColumns()
                ->panel('seo')
                ->placeholder('Meta description')
                ->rules(['sometimes', 'max:500']),

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
