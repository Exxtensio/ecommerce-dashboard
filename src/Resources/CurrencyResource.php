<?php

namespace Exxtensio\EcommerceDashboard\Resources;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Exxtensio\EcommerceDashboard\Fields;
use Exxtensio\EcommerceDashboard\Http\Controllers\CurrencyController;
use Exxtensio\EcommerceDashboard\Models;
use Exception;

class CurrencyResource extends Resource
{
    public static string $model = Models\Currency::class;
    public static string $controller = CurrencyController::class;
    public static string $title = 'name';
    public static string $label = 'Currencies';
    public static string $prefix = 'currencies';
    public static string $singularLabel = 'Currency';
    public static array $search = ['id', 'name', 'code', 'symbol'];
    public static bool $canDelete = false;
    public static array $defaultColumns = ['id', 'name', 'code', 'symbol', 'rate', 'deleted_at'];

    /**
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public function toArray(Request $request): array
    {
        return [
            Fields\ID::make('ID', 'id', $this)
                ->copyable(),

            Fields\Text::make('Name', 'name', $this)
                ->rules(['required', 'string', 'max:255'])
                ->creationRules(['unique:currencies,name'])
                ->updateRules([
                    Rule::unique('currencies', 'name')->ignore($this->id)
                ])
                ->sortable(),

            Fields\Text::make('Code', 'code', $this)
                ->rules(['required', 'string', 'size:3', 'uppercase'])
                ->creationRules(['unique:currencies,code'])
                ->updateRules([
                    Rule::unique('currencies', 'code')->ignore($this->id)
                ])
                ->sortable()
                ->copyable(),

            Fields\Text::make('Symbol', 'symbol', $this)
                ->rules(['required', 'string', 'max:7', 'min:1'])
                ->updateRules([
                    Rule::unique('currencies', 'symbol')->ignore($this->id)
                ]),

            Fields\Number::make('Rate', 'rate', $this)
                ->default(1)
                ->places(4)
                ->min(0)
                ->max(99999)
                ->fullWidth()
                ->rules(['required', 'decimal:4', 'min:0', 'max:99999']),

            Fields\Number::make('Fixed Rate', 'fixed_rate', $this)
                ->nullable()
                ->places(4)
                ->min(0)
                ->max(99999)
                ->fullWidth()
                ->rules(['nullable', 'decimal:4', 'min:0', 'max:99999']),

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
