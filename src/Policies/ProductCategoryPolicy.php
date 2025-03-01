<?php

namespace Exxtensio\EcommerceDashboard\Policies;

use Exxtensio\EcommerceDashboard\Models\ProductCategory as Model;
use Exxtensio\EcommerceDashboard\Models\User as User;
use ReflectionException;

class ProductCategoryPolicy extends AbstractPolicy
{
    /** @throws ReflectionException */
    public function viewAny(User $user): bool
    {
        $check = $this->beforeMethod($user, 'viewAny');
        if(!is_null($check)) return $check;

        return $user->hasPermissionTo('ProductCategory viewAny');
    }

    /** @throws ReflectionException */
    public function view(User $user, Model $model): bool
    {
        $check = $this->beforeMethod($user, 'view', $model);
        if(!is_null($check)) return $check;

        return $user->hasPermissionTo('ProductCategory view');
    }

    /** @throws ReflectionException */
    public function create(User $user): bool
    {
        $check = $this->beforeMethod($user, 'create');
        if(!is_null($check)) return $check;

        return $user->hasPermissionTo('ProductCategory create');
    }

    /** @throws ReflectionException */
    public function update(User $user, Model $model): bool
    {
        $check = $this->beforeMethod($user, 'update', $model);
        if(!is_null($check)) return $check;

        return $user->hasPermissionTo('ProductCategory update');
    }

    /** @throws ReflectionException */
    public function delete(User $user, Model $model): bool
    {
        $check = $this->beforeMethod($user, 'delete', $model);
        if(!is_null($check)) return $check;

        return $user->hasPermissionTo('ProductCategory delete');
    }

    /** @throws ReflectionException */
    public function restore(User $user, Model $model): bool
    {
        $check = $this->beforeMethod($user, 'restore', $model);
        if(!is_null($check)) return $check;

        return $user->hasPermissionTo('ProductCategory restore');
    }

    /** @throws ReflectionException */
    public function forceDelete(User $user, Model $model): bool
    {
        $check = $this->beforeMethod($user, 'forceDelete', $model);
        if(!is_null($check)) return $check;

        return $user->hasPermissionTo('ProductCategory forceDelete');
    }
}
