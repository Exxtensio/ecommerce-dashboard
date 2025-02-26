<?php

namespace Exxtensio\EcommerceDashboard\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Exxtensio\EcommerceDashboard\Contracts;
use Exxtensio\EcommerceDashboard\Exceptions;
use Exxtensio\EcommerceDashboard\Guard;
use Exxtensio\EcommerceDashboard\Models\Permission;
use Exxtensio\EcommerceDashboard\PermissionRegistrar;
use BackedEnum;
use ReflectionException;

trait HasPermissions
{
    private ?string $permissionClass = null;

    public static function bootHasPermissions(): void
    {
        static::deleting(function ($model) {
            if (method_exists($model, 'isForceDeleting') && !$model->isForceDeleting())
                return;

            if (!is_a($model, Contracts\Permission::class))
                $model->permissions()->detach();

            if (is_a($model, Contracts\Role::class))
                $model->users()->detach();
        });
    }

    public function getPermissionClass(): string
    {
        if (!$this->permissionClass)
            $this->permissionClass = app(PermissionRegistrar::class)->getPermissionClass();

        return $this->permissionClass;
    }

    public function permissions(): Relations\MorphToMany
    {
        return $this->morphToMany(
            Permission::class,
            'model',
            'model_has_permissions',
            'model_id',
            app(PermissionRegistrar::class)->pivotPermission
        );
    }

    /** @throws ReflectionException */
    public function scopePermission(Builder $query, BackedEnum|array|int|Contracts\Permission|string|Collection $permissions, bool $without = false): Builder
    {
        $permissions = $this->convertToPermissionModels($permissions);

        $permissionKey = (new ($this->getPermissionClass())())->getKeyName();
        $roleKey = (new (is_a($this, Contracts\Role::class)
            ? static::class
            : $this->getRoleClass())())->getKeyName();

        $rolesWithPermissions = is_a($this, Contracts\Role::class)
            ? [] :
            array_unique(
                array_reduce($permissions, fn($result, $permission) => array_merge($result, $permission->roles->all()), [])
            );

        return $query->where(fn(Builder $query) => $query
            ->{!$without ? 'whereHas' : 'whereDoesntHave'}('permissions', fn(Builder $subQuery) => $subQuery
                ->whereIn("permissions.$permissionKey", array_column($permissions, $permissionKey))
            )->when(count($rolesWithPermissions), fn($whenQuery) => $whenQuery
                ->{!$without ? 'orWhereHas' : 'whereDoesntHave'}('roles', fn(Builder $subQuery) => $subQuery
                    ->whereIn("roles.$roleKey", array_column($rolesWithPermissions, $roleKey))
                )
            )
        );
    }

    /** @throws ReflectionException */
    public function scopeWithoutPermission(Builder $query, BackedEnum|array|int|Contracts\Permission|string|Collection $permissions): Builder
    {
        return $this->scopePermission($query, $permissions, true);
    }

    /** @throws ReflectionException */
    protected function convertToPermissionModels(BackedEnum|array|int|Contracts\Permission|string|Collection $permissions): array
    {
        if ($permissions instanceof Collection)
            $permissions = $permissions->all();

        return array_map(function ($permission) {
            if ($permission instanceof Contracts\Permission)
                return $permission;

            if ($permission instanceof BackedEnum)
                $permission = $permission->value;

            $method = is_int($permission) || PermissionRegistrar::isUid($permission) ? 'findById' : 'findByName';

            return $this->getPermissionClass()::{$method}($permission, $this->getDefaultGuardName());
        }, Arr::wrap($permissions));
    }

    /** @throws ReflectionException */
    public function filterPermission(BackedEnum|int|Contracts\Permission|string $permission, $guardName = null): Contracts\Permission
    {
        if ($permission instanceof BackedEnum)
            $permission = $permission->value;

        if (is_int($permission) || PermissionRegistrar::isUid($permission))
            $permission = $this->getPermissionClass()::findById(
                $permission,
                $guardName ?? $this->getDefaultGuardName()
            );

        if (is_string($permission))
            $permission = $this->getPermissionClass()::findByName(
                $permission,
                $guardName ?? $this->getDefaultGuardName()
            );

        if (!$permission instanceof Contracts\Permission)
            throw new Exceptions\PermissionDoesNotExist();

        return $permission;
    }

    /** @throws ReflectionException */
    public function hasPermissionTo(BackedEnum|int|Contracts\Permission|string $permission, string $guardName = null): bool
    {
        $permission = $this->filterPermission($permission, $guardName);

        return $this->hasDirectPermission($permission) || $this->hasPermissionViaRole($permission);
    }

    /** @throws ReflectionException */
    public function checkPermissionTo(BackedEnum|int|Contracts\Permission|string $permission, string $guardName = null): bool
    {
        try {
            return $this->hasPermissionTo($permission, $guardName);
        } catch (Exceptions\PermissionDoesNotExist $e) {
            return false;
        }
    }

    /** @throws ReflectionException */
    public function hasAnyPermission(...$permissions): bool
    {
        $permissions = collect($permissions)->flatten();

        foreach ($permissions as $permission) {
            if ($this->checkPermissionTo($permission))
                return true;
        }

        return false;
    }

    /** @throws ReflectionException */
    public function hasAllPermissions(...$permissions): bool
    {
        $permissions = collect($permissions)->flatten();

        foreach ($permissions as $permission) {
            if (!$this->checkPermissionTo($permission))
                return false;
        }

        return true;
    }

    protected function hasPermissionViaRole(Contracts\Permission $permission): bool
    {
        if (is_a($this, Contracts\Role::class))
            return false;

        return $this->hasRole($permission->roles);
    }

    /** @throws ReflectionException */
    public function hasDirectPermission(BackedEnum|int|Contracts\Permission|string $permission): bool
    {
        $permission = $this->filterPermission($permission);

        return $this->permissions->contains($permission->getKeyName(), $permission->getKey());
    }

    public function getPermissionsViaRoles(): Collection
    {
        if (is_a($this, Contracts\Role::class) || is_a($this, Contracts\Permission::class))
            return collect();

        return $this->loadMissing('roles', 'roles.permissions')
            ->roles->flatMap(fn($role) => $role->permissions)
            ->sort()->values();
    }

    public function getAllPermissions(): Collection
    {
        $permissions = $this->permissions;

        if (method_exists($this, 'roles'))
            $permissions = $permissions->merge($this->getPermissionsViaRoles());

        return $permissions->sort()->values();
    }

    /** @throws ReflectionException */
    private function collectPermissions(...$permissions): array
    {
        return collect($permissions)
            ->flatten()
            ->reduce(function ($array, $permission) {
                if (empty($permission))
                    return $array;

                $permission = $this->getStoredPermission($permission);
                if (!$permission instanceof Contracts\Permission)
                    return $array;

                if (!in_array($permission->getKey(), $array)) {
                    $this->ensureModelSharesGuard($permission);
                    $array[] = $permission->getKey();
                }

                return $array;
            }, []);
    }

    /** @throws ReflectionException */
    public function givePermissionTo(...$permissions): static
    {
        $permissions = $this->collectPermissions($permissions);

        $model = $this->getModel();

        if ($model->exists) {
            $currentPermissions = $this->permissions->map(fn($permission) => $permission->getKey())->toArray();

            $this->permissions()->attach(array_diff($permissions, $currentPermissions));
            $model->unsetRelation('permissions');
        } else {
            $class = \get_class($model);
            $saved = false;

            $class::saved(
                function ($object) use ($permissions, $model, &$saved) {
                    if ($saved || $model->getKey() != $object->getKey())
                        return;
                    $model->permissions()->attach($permissions);
                    $model->unsetRelation('permissions');
                    $saved = true;
                }
            );
        }

        if (is_a($this, Contracts\Role::class))
            $this->forgetCachedPermissions();

        return $this;
    }

    /** @throws ReflectionException */
    public function syncPermissions(...$permissions): static
    {
        if ($this->getModel()->exists) {
            $this->collectPermissions($permissions);
            $this->permissions()->detach();
            $this->setRelation('permissions', collect());
        }

        return $this->givePermissionTo($permissions);
    }

    /** @throws ReflectionException */
    public function revokePermissionTo(array|Contracts\Permission|string|BackedEnum $permission): static
    {
        $this->permissions()->detach($this->getStoredPermission($permission));

        if (is_a($this, Contracts\Role::class))
            $this->forgetCachedPermissions();

        $this->unsetRelation('permissions');

        return $this;
    }

    public function getPermissionNames(): Collection
    {
        return $this->permissions->pluck('name');
    }

    /** @throws ReflectionException */
    protected function getStoredPermission(BackedEnum|array|int|Contracts\Permission|string|Collection $permissions): BackedEnum|int|Contracts\Permission|string|Collection|array
    {
        if ($permissions instanceof BackedEnum)
            $permissions = $permissions->value;

        if (is_int($permissions) || PermissionRegistrar::isUid($permissions))
            return $this->getPermissionClass()::findById($permissions, $this->getDefaultGuardName());

        if (is_string($permissions))
            return $this->getPermissionClass()::findByName($permissions, $this->getDefaultGuardName());

        if (is_array($permissions)) {
            $permissions = array_map(function ($permission) {
                if ($permission instanceof BackedEnum)
                    return $permission->value;

                return is_a($permission, Contracts\Permission::class) ? $permission->name : $permission;
            }, $permissions);

            return $this->getPermissionClass()::whereIn('name', $permissions)
                ->whereIn('guard_name', $this->getGuardNames())
                ->get();
        }

        return $permissions;
    }

    /** @throws Exceptions\GuardDoesNotMatch|ReflectionException */
    protected function ensureModelSharesGuard(Contracts\Role|Contracts\Permission $roleOrPermission): void
    {
        if (!$this->getGuardNames()->contains($roleOrPermission->guard_name))
            throw Exceptions\GuardDoesNotMatch::create($roleOrPermission->guard_name, $this->getGuardNames());
    }

    /** @throws ReflectionException */
    protected function getGuardNames(): Collection
    {
        return Guard::getNames($this);
    }

    /** @throws ReflectionException */
    protected function getDefaultGuardName(): string
    {
        return Guard::getDefaultName($this);
    }

    public function forgetCachedPermissions(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    /** @throws ReflectionException */
    public function hasAllDirectPermissions(...$permissions): bool
    {
        $permissions = collect($permissions)->flatten();

        foreach ($permissions as $permission) {
            if (!$this->hasDirectPermission($permission))
                return false;
        }

        return true;
    }

    /** @throws ReflectionException */
    public function hasAnyDirectPermission(...$permissions): bool
    {
        $permissions = collect($permissions)->flatten();

        foreach ($permissions as $permission) {
            if ($this->hasDirectPermission($permission))
                return true;
        }

        return false;
    }
}
