<?php

namespace Exxtensio\EcommerceDashboard\Resources;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Exxtensio\EcommerceDashboard\Fields;
use Exxtensio\EcommerceDashboard\Filters;
use Exxtensio\EcommerceDashboard\Http\Controllers\CountryController;
use Exxtensio\EcommerceDashboard\Models;

class CountryResource extends Resource
{
    public static string $model = Models\Country::class;
    public static string $controller = CountryController::class;
    public static string $title = 'name';
    public static string $prefix = 'countries';
    public static string $label = 'Countries';
    public static string $singularLabel = 'Country';
    public static array $search = ['id', 'name', 'code'];
    public static bool $canCreate = false;
    public static bool $canDelete = false;
    public static array $defaultColumns = ['id', 'currency_id', 'name', 'code', 'active', 'deleted_at'];
    public static array $defaultRelations = ['currency'];

    public $with = ['currency'];

    public array $withOptions = [
        CurrencyResource::class => Models\Currency::class
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

            Fields\Checkbox::make('Active', 'active', $this)
                ->panel('currency')
                ->fullWidth()
                ->default(false),

            Fields\BelongTo::make('Currency', 'currency', $this, CurrencyResource::class)
                ->searchable()
                ->panel('currency')
                ->rules(['required_if_accepted:active']),

            Fields\Text::make('Name', 'name', $this)
                ->rules(['required', 'string', 'max:255'])
                ->creationRules(['unique:countries,name'])
                ->updateRules([
                    Rule::unique('countries', 'name')->ignore($this->id)
                ])
                ->sortable(),

            Fields\Text::make('Code', 'code', $this)
                ->rules(['required', 'string', 'size:2', 'uppercase'])
                ->creationRules(['unique:countries,code'])
                ->updateRules([
                    Rule::unique('countries', 'code')->ignore($this->id)
                ])
                ->sortable()
                ->copyable(),

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
            Filters\Select::make('Currency', 'currencies', 'currency')
                ->searchable(),

            Filters\Boolean::make('Active', 'active')
        ];
    }
}
