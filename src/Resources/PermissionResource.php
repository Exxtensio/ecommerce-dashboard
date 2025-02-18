<?php

namespace Exxtensio\EcommerceDashboard\Resources;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Exxtensio\EcommerceDashboard\Fields;
use Exxtensio\EcommerceDashboard\Filters;
use Exxtensio\EcommerceDashboard\Http\Controllers\PermissionController;
use Exxtensio\EcommerceDashboard\Models\Permission;

class PermissionResource extends Resource
{
    public static string $model = Permission::class;
    public static string $controller = PermissionController::class;
    public static string $title = 'name';
    public static string $label = 'Permissions';
    public static string $prefix = 'permissions';
    public static string $singularLabel = 'Permission';
    public static array $search = ['id', 'name'];
    public static array $defaultColumns = ['id', 'name', 'group', 'created_at'];

    /**
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            Fields\ID::make('ID', 'id', $this)
                ->copyable(),

            Fields\Text::make('Name', 'name', $this)
                ->rules(['required', 'string', 'min:3', 'max:255'])
                ->creationRules(['unique:permissions,name'])
                ->updateRules([
                    Rule::unique('permissions', 'name')->ignore($this->id)
                ])
                ->copyable()
                ->sortable(),

            Fields\Select::make('Group', 'group', $this)
                ->rules(['required'])
                ->default('global')
                ->options([
                    'global' => 'Global',
                    'activity' => 'Activities',
                    'currency' => 'Currencies',
                    'country' => 'Countries',
                    'user' => 'Users',
                    'role' => 'Roles',
                    'permission' => 'Permissions',
                    'productBrand' => 'Brands',
                    'productCategory' => 'Categories',
                    'productAttribute' => 'Attribute',
                    'product' => 'Products',
                    'productReview' => 'Reviews',
                    'cart' => 'Carts',
                    'order' => 'Orders',
                ]),

            Fields\Select::make('Guard Name', 'guard_name', $this)
                ->rules(['required'])
                ->options(['web' => 'Web']),

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
            Filters\Select::make('Group', 'group')
                ->separatable()
                ->options([
                    'global' => 'Global',
                    'activity' => 'Activities',
                    'currency' => 'Currencies',
                    'country' => 'Countries',
                    'user' => 'Users',
                    'role' => 'Roles',
                    'permission' => 'Permissions',
                    'productBrand' => 'Brands',
                    'productCategory' => 'Categories',
                    'productAttribute' => 'Attribute',
                    'product' => 'Products',
                    'productReview' => 'Reviews',
                    'cart' => 'Carts',
                    'order' => 'Orders',
                ]),
        ];
    }
}
