<?php

namespace Exxtensio\EcommerceDashboard\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Exxtensio\EcommerceDashboard\Contracts;
use Exxtensio\EcommerceDashboard\Models;
use Exxtensio\EcommerceDashboard\PermissionRegistrar;
use ReflectionException;
use BackedEnum;
use TypeError;

trait HasRoles
{
    use HasPermissions;

    private ?string $roleClass = null;

    public static function bootHasRoles(): void
    {
        static::deleting(function ($model) {
            if (method_exists($model, 'isForceDeleting') && !$model->isForceDeleting())
                return;

            $model->roles()->detach();
            if (is_a($model, Contracts\Permission::class))
                $model->users()->detach();
        });
    }

    public function getRoleClass(): string
    {
        if (!$this->roleClass)
            $this->roleClass = app(PermissionRegistrar::class)->getRoleClass();

        return $this->roleClass;
    }

    public function roles(): Relations\MorphToMany
    {
        return $this->morphToMany(
            Models\Role::class,
            'model',
            'model_has_roles',
            'model_id',
            app(PermissionRegistrar::class)->pivotRole
        );
    }

    /** @throws ReflectionException */
    public function scopeRole(Builder $query, $roles, $guard = null, $without = false): Builder
    {
        if ($roles instanceof Collection)
            $roles = $roles->all();

        $roles = array_map(function ($role) use ($guard) {
            if ($role instanceof Contracts\Role)
                return $role;

            if ($role instanceof BackedEnum)
                $role = $role->value;

            $method = is_int($role) || PermissionRegistrar::isUid($role) ? 'findById' : 'findByName';

            return $this->getRoleClass()::{$method}($role, $guard ?: $this->getDefaultGuardName());
        }, Arr::wrap($roles));

        $key = (new ($this->getRoleClass())())->getKeyName();

        return $query->{!$without ? 'whereHas' : 'whereDoesntHave'}('roles', fn(Builder $subQuery) => $subQuery
            ->whereIn("roles.$key", array_column($roles, $key))
        );
    }

    /** @throws ReflectionException */
    public function scopeWithoutRole(Builder $query, $roles, $guard = null): Builder
    {
        return $this->scopeRole($query, $roles, $guard, true);
    }

    /** @throws ReflectionException */
    private function collectRoles(...$roles): array
    {
        return collect($roles)
            ->flatten()
            ->reduce(function ($array, $role) {
                if (empty($role))
                    return $array;

                $role = $this->getStoredRole($role);
                if (!$role instanceof Contracts\Role)
                    return $array;

                if (!in_array($role->getKey(), $array)) {
                    $this->ensureModelSharesGuard($role);
                    $array[] = $role->getKey();
                }

                return $array;
            }, []);
    }

    /** @throws ReflectionException */
    public function assignRole(...$roles): static
    {
        $roles = $this->collectRoles($roles);

        $model = $this->getModel();

        if ($model->exists) {
            $currentRoles = $this->roles->map(fn($role) => $role->getKey())->toArray();

            $this->roles()->attach(array_diff($roles, $currentRoles));
            $model->unsetRelation('roles');
        } else {
            $class = get_class($model);
            $saved = false;

            $class::saved(
                function ($object) use ($roles, $model, &$saved) {
                    if ($saved || $model->getKey() != $object->getKey())
                        return;
                    $model->roles()->attach($roles);
                    $model->unsetRelation('roles');
                    $saved = true;
                }
            );
        }

        if (is_a($this, Contracts\Permission::class))
            $this->forgetCachedPermissions();

        return $this;
    }

    /** @throws ReflectionException */
    public function removeRole($role): static
    {
        $this->roles()->detach($this->getStoredRole($role));

        $this->unsetRelation('roles');

        if (is_a($this, Contracts\Permission::class))
            $this->forgetCachedPermissions();

        return $this;
    }

    /** @throws ReflectionException */
    public function syncRoles(...$roles): static
    {
        if ($this->getModel()->exists) {
            $this->collectRoles($roles);
            $this->roles()->detach();
            $this->setRelation('roles', collect());
        }

        return $this->assignRole($roles);
    }

    public function hasRole($roles, ?string $guard = null): bool
    {
        $this->loadMissing('roles');

        if (is_string($roles) && str_contains($roles, '|'))
            $roles = $this->convertPipeToArray($roles);

        if ($roles instanceof BackedEnum) {
            $roles = $roles->value;

            return $this->roles
                ->when($guard, fn($q) => $q->where('guard_name', $guard))
                ->pluck('name')
                ->contains(function ($name) use ($roles) {
                    if ($name instanceof BackedEnum)
                        return $name->value == $roles;

                    return $name == $roles;
                });
        }

        if (is_int($roles) || PermissionRegistrar::isUid($roles)) {
            $key = (new ($this->getRoleClass())())->getKeyName();

            return $guard
                ? $this->roles->where('guard_name', $guard)->contains($key, $roles)
                : $this->roles->contains($key, $roles);
        }

        if (is_string($roles))
            return $guard
                ? $this->roles->where('guard_name', $guard)->contains('name', $roles)
                : $this->roles->contains('name', $roles);

        if ($roles instanceof Contracts\Role)
            return $this->roles->contains($roles->getKeyName(), $roles->getKey());

        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ($this->hasRole($role, $guard))
                    return true;
            }

            return false;
        }

        if ($roles instanceof Collection)
            return $roles->intersect($guard ? $this->roles->where('guard_name', $guard) : $this->roles)->isNotEmpty();

        throw new TypeError('Unsupported type for $roles parameter to hasRole().');
    }

    public function hasAnyRole(...$roles): bool
    {
        return $this->hasRole($roles);
    }

    public function hasAllRoles($roles, ?string $guard = null): bool
    {
        $this->loadMissing('roles');

        if ($roles instanceof BackedEnum)
            $roles = $roles->value;

        if (is_string($roles) && str_contains($roles, '|'))
            $roles = $this->convertPipeToArray($roles);

        if (is_string($roles))
            return $this->hasRole($roles, $guard);

        if ($roles instanceof Contracts\Role)
            return $this->roles->contains($roles->getKeyName(), $roles->getKey());

        $roles = collect()->make($roles)->map(function ($role) {
            if ($role instanceof BackedEnum)
                return $role->value;

            return $role instanceof Contracts\Role ? $role->name : $role;
        });

        $roleNames = $guard
            ? $this->roles->where('guard_name', $guard)->pluck('name')
            : $this->getRoleNames();

        $roleNames = $roleNames->transform(function ($roleName) {
            if ($roleName instanceof BackedEnum)
                return $roleName->value;

            return $roleName;
        });

        return $roles->intersect($roleNames) == $roles;
    }

    public function hasExactRoles($roles, ?string $guard = null): bool
    {
        $this->loadMissing('roles');

        if (is_string($roles) && str_contains($roles, '|'))
            $roles = $this->convertPipeToArray($roles);

        if (is_string($roles))
            $roles = [$roles];

        if ($roles instanceof Contracts\Role)
            $roles = [$roles->name];

        $roles = collect()->make($roles)->map(fn($role) => $role instanceof Contracts\Role ? $role->name : $role);

        return $this->roles->count() == $roles->count() && $this->hasAllRoles($roles, $guard);
    }

    public function getDirectPermissions(): Collection
    {
        return $this->permissions;
    }

    public function getRoleNames(): Collection
    {
        $this->loadMissing('roles');

        return $this->roles->pluck('name');
    }

    /** @throws ReflectionException */
    protected function getStoredRole($role): Contracts\Role
    {
        if ($role instanceof BackedEnum)
            $role = $role->value;

        if (is_int($role) || PermissionRegistrar::isUid($role))
            return $this->getRoleClass()::findById($role, $this->getDefaultGuardName());

        if (is_string($role))
            return $this->getRoleClass()::findByName($role, $this->getDefaultGuardName());

        return $role;
    }

    protected function convertPipeToArray(string $pipeString): array
    {
        $pipeString = trim($pipeString);

        if (strlen($pipeString) <= 2)
            return [str_replace('|', '', $pipeString)];

        $quoteCharacter = substr($pipeString, 0, 1);
        $endCharacter = substr($quoteCharacter, -1, 1);

        if ($quoteCharacter !== $endCharacter)
            return explode('|', $pipeString);

        if (!in_array($quoteCharacter, ["'", '"']))
            return explode('|', $pipeString);

        return explode('|', trim($pipeString, $quoteCharacter));
    }
}
