<?php

namespace Exxtensio\EcommerceDashboard\Resources;

use Exxtensio\EcommerceDashboard\Models;
use Illuminate\Http\Request;
use Exxtensio\EcommerceDashboard\Fields;
use Exxtensio\EcommerceDashboard\Filters;
use Exxtensio\EcommerceDashboard\Http\Controllers\OrderController;

class OrderResource extends Resource
{
    public static string $model = Models\Order::class;
    public static string $controller = OrderController::class;
    public static string $label = 'Orders';
    public static string $prefix = 'orders';
    public static string $singularLabel = 'Order';
    public static array $search = ['id'];
    public static bool $canCreate = false;
    public static bool $canDelete = false;
    public static array $defaultColumns = ['id', 'user_id', 'country', 'amount', 'status', 'payment_status', 'created_at'];

    public static array $defaultRelations = ['customer', 'currentCountry', 'items'];
    public $with = ['user', 'country'];

    public array $withOptions = [
        UserResource::class => Models\User::class,
        CountryResource::class => Models\Country::class,
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

            Fields\Badge::make('Status', 'status', $this)
                ->fullWidth()
                ->rules(['required'])
                ->map(self::$model::getStatusesMap())
                ->default(self::$model::getDefaultStatus())
                ->options(self::$model::getStatuses()),

            Fields\Badge::make('Payment Status', 'payment_status', $this)
                ->fullWidth()
                ->rules(['required'])
                ->map(self::$model::getPaymentStatusesMap())
                ->default(self::$model::getDefaultPaymentStatus())
                ->options(self::$model::getPaymentStatuses()),

            Fields\Activities::make('Last Activities', 'activities', $this, self::class)
                ->hideFromIndex()->hideWhenCreating(),

            Fields\BelongTo::make('Country', 'currentCountry', $this, CountryResource::class),

            Fields\BelongTo::make('Customer', 'customer', $this, UserResource::class),

            Fields\OrderItems::make('Products', 'items', $this)
                ->panel('products')
                ->fullWidth(),

            Fields\Text::make('Amount', 'amount', $this),

            Fields\Timestamp::make('Created At', 'created_at', $this)
                ->sortable()
                ->diffForHumans(),

            Fields\Timestamp::make('Updated At', 'updated_at', $this)
                ->sortable()
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

            Filters\Select::make('Payment Status', 'payment_status')
                ->separatable()
                ->options(self::$model::getPaymentStatuses())
                ->searchable(),

            Filters\Select::make('Customer', 'users', 'customer')
                ->searchable(),

            Filters\Select::make('Country', 'countries', 'currentCountry')
                ->searchable()
        ];
    }
}
