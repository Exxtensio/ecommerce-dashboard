<?php

namespace Exxtensio\EcommerceDashboard\Policies;

use Exxtensio\EcommerceDashboard\Models\Role as Model;
use Exxtensio\EcommerceDashboard\Models\User as User;
use ReflectionException;

class RolePolicy extends AbstractPolicy
{
    /** @throws ReflectionException */
    public function viewAny(User $user): bool
    {
        $check = $this->beforeMethod($user, 'viewAny');
        if(!is_null($check)) return $check;

        return $user->hasPermissionTo('Role viewAny');
    }

    /** @throws ReflectionException */
    public function view(User $user, Model $model): bool
    {
        $check = $this->beforeMethod($user, 'view', $model);
        if(!is_null($check)) return $check;

        return $user->hasPermissionTo('Role view');
    }

    /** @throws ReflectionException */
    public function create(User $user): bool
    {
        $check = $this->beforeMethod($user, 'create');
        if(!is_null($check)) return $check;

        return $user->hasPermissionTo('Role create');
    }

    /** @throws ReflectionException */
    public function update(User $user, Model $model): bool
    {
        $check = $this->beforeMethod($user, 'update', $model);
        if(!is_null($check)) return $check;

        return $user->hasPermissionTo('Role update');
    }

    /** @throws ReflectionException */
    public function delete(User $user, Model $model): bool
    {
        $check = $this->beforeMethod($user, 'delete', $model);
        if(!is_null($check)) return $check;

        return $user->hasPermissionTo('Role delete');
    }

    /** @throws ReflectionException */
    public function restore(User $user, Model $model): bool
    {
        $check = $this->beforeMethod($user, 'restore', $model);
        if(!is_null($check)) return $check;

        return $user->hasPermissionTo('Role restore');
    }

    /** @throws ReflectionException */
    public function forceDelete(User $user, Model $model): bool
    {
        $check = $this->beforeMethod($user, 'forceDelete', $model);
        if(!is_null($check)) return $check;

        return $user->hasPermissionTo('Role forceDelete');
    }
}
