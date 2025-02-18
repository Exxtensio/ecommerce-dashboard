<?php

namespace Exxtensio\EcommerceDashboard\Policies;

use Exxtensio\EcommerceDashboard\Models;

abstract class AbstractPolicy
{
    public function beforeMethod(Models\User $user, string $ability, mixed $model = null): bool|null
    {
        $artisan = $user->hasRole('artisan') ? true : null;
        return match (true) {
            $model instanceof Models\Role => match ($ability) {
                'update','delete','restore','forceDelete' => match (true) {
                    $model->name == 'artisan' => false,
                    default => $artisan,
                },
                default => $artisan,
            },
            $model instanceof Models\Permission => match ($ability) {
                'update','delete','restore','forceDelete' => match (true) {
                    in_array($model->name, app('dashboard')->requiredPermissions()) => false,
                    default => $artisan,
                },
                default => $artisan,
            },
            $model instanceof Models\User => match ($ability) {
                'delete','restore','forceDelete' => match (true) {
                    $model->hasRole('artisan') => false,
                    default => $artisan,
                },
                'update' => match (true) {
                    $model->hasRole('artisan') && $model->id !== $user->id => false,
                    default => $artisan,
                },
                default => $artisan,
            },
            default => $artisan,
        };
    }
}
