<?php

namespace Exxtensio\EcommerceDashboard\Resources;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Exxtensio\EcommerceDashboard\Fields;
use Exxtensio\EcommerceDashboard\Filters;
use Exxtensio\EcommerceDashboard\Http\Controllers\UserController;
use Exxtensio\EcommerceDashboard\Models;

class UserResource extends Resource
{
    public static string $model = Models\User::class;
    public static string $controller = UserController::class;
    public static string $title = 'name';
    public static string $label = 'Users';
    public static string $prefix = 'users';
    public static string $singularLabel = 'User';
    public static array $search = ['id', 'name', 'email'];
    public static array $defaultColumns = ['id', 'name', 'email', 'created_at'];
    public static array $defaultRelations = ['roles', 'permissions'];

    public array $withOptions = [
        RoleResource::class => Models\Role::class,
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
                ->rules(['required', 'string', 'max:255'])
                ->creationRules(['unique:users,name'])
                ->updateRules([
                    Rule::unique('users', 'name')->ignore($this->id)
                ])
                ->sortable(),

            Fields\Text::make('Email', 'email', $this)
                ->rules(['required', 'email', 'max:255'])
                ->creationRules(['unique:users,email'])
                ->updateRules([
                    Rule::unique('users', 'email')->ignore($this->id)
                ])
                ->copyable()
                ->sortable(),

            Fields\MorphOne::make('Role', 'roles', $this, RoleResource::class)
                ->panel('role')
                ->rules(['required', 'string', 'exists:roles,id']),

            Fields\Permissions::make('Permissions', 'permissions', $this, self::class)
                ->panel('permissions')
                ->fullWidth()
                ->rules(['nullable', 'array']),

            Fields\Activities::make('Last Activities', 'activities', $this, self::class)
                ->hideFromIndex()->hideWhenCreating(),

            Fields\Activities::make('Last Actions', 'actions', $this, self::class)
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
            Filters\Select::make('Role', 'roles')
                ->searchable()
        ];
    }
}
