<?php

namespace Exxtensio\EcommerceDashboard\Resources;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Exxtensio\EcommerceDashboard\Fields;
use Exxtensio\EcommerceDashboard\Http\Controllers\RoleController;
use Exxtensio\EcommerceDashboard\Models;

class RoleResource extends Resource
{
    public static string $model = Models\Role::class;
    public static string $controller = RoleController::class;
    public static string $title = 'name';
    public static string $label = 'Roles';
    public static string $prefix = 'roles';
    public static string $singularLabel = 'Role';
    public static array $search = ['id', 'name'];
    public static array $defaultColumns = ['id', 'name', 'created_at'];
    public static array $defaultRelations = ['permissions'];

    public array $withOptions = [
        PermissionResource::class => Models\Permission::class
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

            Fields\Text::make('Name', 'name', $this)
                ->readonly($this->name === 'artisan')
                ->rules(['required', 'string', 'min:3', 'max:255'])
                ->creationRules(['unique:roles,name'])
                ->updateRules([
                    Rule::unique('roles', 'name')->ignore($this->id)
                ])
                ->copyable()
                ->sortable(),

            Fields\Select::make('Guard Name', 'guard_name', $this)
                ->rules(['required'])
                ->options(['web' => 'Web']),

            Fields\Permissions::make('Permissions', 'permissions', $this, self::class)
                ->panel('permissions')
                ->fullWidth()
                ->rules(['nullable', 'array']),

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
}
