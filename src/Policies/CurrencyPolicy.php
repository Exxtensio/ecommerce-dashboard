<?php

namespace Exxtensio\EcommerceDashboard\Policies;

use Exxtensio\EcommerceDashboard\Models\Currency as Model;
use Exxtensio\EcommerceDashboard\Models\User as User;
use ReflectionException;

class CurrencyPolicy extends AbstractPolicy
{
    /** @throws ReflectionException */
    public function viewAny(User $user): bool
    {
        $check = $this->beforeMethod($user, 'viewAny');
        if(!is_null($check)) return $check;

        return $user->hasPermissionTo('Currency viewAny');
    }

    /** @throws ReflectionException */
    public function view(User $user, Model $model): bool
    {
        $check = $this->beforeMethod($user, 'view', $model);
        if(!is_null($check)) return $check;

        return $user->hasPermissionTo('Currency view');
    }

    /** @throws ReflectionException */
    public function create(User $user): bool
    {
        $check = $this->beforeMethod($user, 'create');
        if(!is_null($check)) return $check;

        return $user->hasPermissionTo('Currency create');
    }

    /** @throws ReflectionException */
    public function update(User $user, Model $model): bool
    {
        $check = $this->beforeMethod($user, 'update', $model);
        if(!is_null($check)) return $check;

        return $user->hasPermissionTo('Currency update');
    }

    public function delete(User $user, Model $model): bool
    {
        return false;
    }

    public function restore(User $user, Model $model): bool
    {
        return false;
    }

    public function forceDelete(User $user, Model $model): bool
    {
        return false;
    }
}
