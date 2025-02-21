<?php

namespace Exxtensio\EcommerceDashboard;

use Exxtensio\EcommerceDashboard\Services\ExchangeRateService;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Collection;
use Exception;

class Dashboard extends Facade
{
    public function __invoke($request, $next) {}

    protected static function getFacadeAccessor(): string
    {
        return 'dashboard';
    }

    public function resources(): Collection
    {
        return collect([
            Resources\ActivityResource::class,
            Resources\CountryResource::class,
            Resources\CurrencyResource::class,
            Resources\UserResource::class,
            Resources\RoleResource::class,
            Resources\PermissionResource::class,
            Resources\ProductBrandResource::class,
            Resources\ProductCategoryResource::class,
            Resources\ProductAttributeResource::class,
            Resources\ProductResource::class,
            Resources\ProductReviewResource::class,
            Resources\CartResource::class,
            Resources\OrderResource::class,
        ])->filter();
    }

    public function relationComponents(): array
    {
        return [
            'belong-to-field',
            'morph-to-field',
            'morph-one-field',
            'permissions-field',
            'belong-to-many-field',
            'activities-field',
            'inventory-field',
            'cart-items-field',
            'gallery-field',
        ];
    }

    public function panels(): array
    {
        return [
            'overview' => 'Overview',
            'properties' => 'Properties',
            'type' => 'Type',
            'gallery' => 'Gallery',
            'brand' => 'Brand',
            'categories' => 'Categories',
            'attributes' => 'Attributes',
            'inventory' => 'Inventory',
            'products' => 'Products',
            'currency' => 'Currency',
            'parent' => 'Parent',
            'role' => 'Role',
            'permissions' => 'Permissions',
            'activities' => 'Activities'
        ];
    }

    public function menu($user): array
    {
        return array_filter([
            array_filter([
                ['title' => 'Overview', 'prefix' => '', 'icon' => 'ChartPieIcon', 'href' => route('dashboard.admin.home.index')],
                $user->can('viewAny', Models\Activity::class)
                    ? ['title' => 'Activities', 'prefix' => 'activities', 'icon' => 'BeakerIcon', 'href' => route('dashboard.admin.activities.index')]
                    : null
            ]),
            array_filter([
                $user->can('viewAny', Models\Country::class)
                    ? ['title' => 'Countries', 'prefix' => 'countries', 'icon' => 'GlobeAsiaAustraliaIcon', 'href' => route('dashboard.admin.countries.index')]
                    : null,
                $user->can('viewAny', Models\Currency::class)
                    ? ['title' => 'Currencies', 'prefix' => 'currencies', 'icon' => 'CurrencyDollarIcon', 'href' => route('dashboard.admin.currencies.index')]
                    : null
            ]),
            array_filter([
                $user->can('viewAny', Models\Role::class)
                    ? ['title' => 'Roles', 'prefix' => 'roles', 'icon' => 'UserGroupIcon', 'href' => route('dashboard.admin.roles.index')]
                    : null,
                $user->can('viewAny', Models\Permission::class)
                    ? ['title' => 'Permissions', 'prefix' => 'permissions', 'icon' => 'ShieldCheckIcon', 'href' => route('dashboard.admin.permissions.index')]
                    : null,
                $user->can('viewAny', Models\User::class)
                    ? ['title' => 'Users', 'prefix' => 'users', 'icon' => 'UsersIcon', 'href' => route('dashboard.admin.users.index')]
                    : null,
            ]),
            array_filter([
                $user->can('viewAny', Models\ProductBrand::class)
                    ? ['title' => 'Brands', 'prefix' => 'brands', 'icon' => 'TagIcon', 'href' => route('dashboard.admin.brands.index')]
                    : null,
                $user->can('viewAny', Models\ProductCategory::class)
                    ? ['title' => 'Categories', 'prefix' => 'categories', 'icon' => 'SquaresPlusIcon', 'href' => route('dashboard.admin.categories.index')]
                    : null,
                $user->can('viewAny', Models\ProductAttribute::class)
                    ? ['title' => 'Attributes', 'prefix' => 'attributes', 'icon' => 'SwatchIcon', 'href' => route('dashboard.admin.attributes.index')]
                    : null,
                $user->can('viewAny', Models\Product::class)
                    ? ['title' => 'Products', 'prefix' => 'products', 'icon' => 'BuildingStorefrontIcon', 'href' => route('dashboard.admin.products.index')]
                    : null,
                $user->can('viewAny', Models\ProductReview::class)
                    ? ['title' => 'Reviews', 'prefix' => 'reviews', 'icon' => 'StarIcon', 'href' => route('dashboard.admin.reviews.index')]
                    : null
            ]),
            array_filter([
                $user->can('viewAny', Models\Cart::class)
                    ? ['title' => 'Carts', 'prefix' => 'carts', 'icon' => 'ShoppingCartIcon', 'href' => route('dashboard.admin.carts.index')]
                    : null,
                $user->can('viewAny', Models\Order::class)
                    ? ['title' => 'Orders', 'prefix' => 'orders', 'icon' => 'CreditCardIcon', 'href' => route('dashboard.admin.orders.index')]
                    : null
            ])
        ]);
    }

    public function requiredPermissions(): array
    {
        return [
            'Activity viewAny', 'Activity view',
            'Cart viewAny', 'Cart view',
            'Country create', 'Country update', 'Country view', 'Country viewAny',
            'Currency viewAny', 'Currency view', 'Currency create', 'Currency update',
            'viewDashboard',
            'Order view', 'Order viewAny',
            'Permission view', 'Permission forceDelete', 'Permission restore', 'Permission delete', 'Permission update', 'Permission create', 'Permission viewAny',
            'Product viewAny', 'Product view', 'Product create', 'Product update', 'Product delete', 'Product restore', 'Product forceDelete',
            'ProductAttribute forceDelete', 'ProductAttribute restore', 'ProductAttribute delete', 'ProductAttribute create', 'ProductAttribute view', 'ProductAttribute viewAny', 'ProductAttribute update',
            'ProductBrand forceDelete', 'ProductBrand restore', 'ProductBrand delete', 'ProductBrand update', 'ProductBrand create', 'ProductBrand view', 'ProductBrand viewAny',
            'ProductCategory viewAny', 'ProductCategory view', 'ProductCategory create', 'ProductCategory update', 'ProductCategory delete', 'ProductCategory restore', 'ProductCategory forceDelete',
            'ProductReview view', 'ProductReview forceDelete', 'ProductReview restore', 'ProductReview update', 'ProductReview viewAny', 'ProductReview delete',
            'Role restore', 'Role forceDelete', 'Role delete', 'Role update', 'Role create', 'Role view', 'Role viewAny',
            'User viewAny', 'User view', 'User create', 'User update', 'User delete', 'User restore', 'User forceDelete'
        ];
    }

    /**
     * @return void
     * @throws Exception
     */
    public static function updateCurrencyRate(): void
    {
        $service = new ExchangeRateService();
        $service->update();
    }
}
