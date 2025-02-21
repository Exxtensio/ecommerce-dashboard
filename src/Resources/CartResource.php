<?php

namespace Exxtensio\EcommerceDashboard\Resources;

use Exxtensio\EcommerceDashboard\Models\Country;
use Illuminate\Http\Request;
use Exxtensio\EcommerceDashboard\Fields;
use Exxtensio\EcommerceDashboard\Filters;
use Exxtensio\EcommerceDashboard\Http\Controllers\CartController;
use Exxtensio\EcommerceDashboard\Models\Cart;
use Exxtensio\EcommerceDashboard\Models\User;

class CartResource extends Resource
{
    public static string $model = Cart::class;
    public static string $controller = CartController::class;
    public static string $prefix = 'carts';
    public static string $label = 'Carts';
    public static string $singularLabel = 'Cart';
    public static array $search = ['id'];
    public static bool $canCreate = false;
    public static bool $canEdit = false;
    public static bool $canDelete = false;
    public static array $defaultColumns = ['id', 'user_id', 'country', 'created_at'];

    public static array $defaultRelations = ['user', 'currentCountry', 'items'];
    public $with = ['user', 'country'];

    public array $withOptions = [
        UserResource::class => User::class,
        CountryResource::class => Country::class,
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

            Fields\BelongTo::make('Country', 'currentCountry', $this, CountryResource::class),

            Fields\BelongTo::make('User', 'user', $this, UserResource::class),

            Fields\CartItems::make('Products', 'items', $this)
                ->panel('products')
                ->fullWidth(),

            Fields\Activities::make('Last Activities', 'activities', $this, self::class)
                ->hideFromIndex()->hideWhenCreating(),

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
            Filters\Select::make('User', 'users', 'user')
                ->searchable(),

            Filters\Select::make('Country', 'countries', 'currentCountry')
                ->searchable()
        ];
    }
}
