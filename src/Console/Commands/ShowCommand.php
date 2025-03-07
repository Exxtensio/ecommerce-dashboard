<?php

namespace Exxtensio\EcommerceDashboard\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Exxtensio\EcommerceDashboard\Contracts\Permission as PermissionContract;
use Exxtensio\EcommerceDashboard\Contracts\Role as RoleContract;
use Symfony\Component\Console\Helper\TableCell;

class ShowCommand extends Command
{
    protected $signature = 'dashboard:show
            {guard? : The name of the guard}
            {style? : The display style (default|borderless|compact|box)}';

    protected $description = 'Show a table of roles and permissions per guard';

    public function handle(): void
    {
        $permissionClass = app(PermissionContract::class);
        $roleClass = app(RoleContract::class);

        $style = $this->argument('style') ?? 'default';
        $guard = $this->argument('guard');

        $guards = $guard
            ? Collection::make([$guard])
            : $permissionClass::pluck('guard_name')->merge($roleClass::pluck('guard_name'))->unique();

        foreach ($guards as $guard) {
            $this->info("Guard: $guard");

            $roles = $roleClass::whereGuardName($guard)
                ->with('permissions')
                ->orderBy('name')->get()->mapWithKeys(fn ($role) => [
                    $role->name.'_' => [
                        'permissions' => $role->permissions->pluck($permissionClass->getKeyName())
                    ],
                ]);

            $permissions = $permissionClass::whereGuardName($guard)
                ->orderBy('name')
                ->pluck('name', $permissionClass->getKeyName());

            $body = $permissions->map(fn ($permission, $id) => $roles->map(
                fn (array $role_data) => $role_data['permissions']->contains($id) ? ' ✔' : ' ·'
            )->prepend($permission));

            $this->table(
                array_merge(
                    [],
                    $roles->keys()->map(function ($val) {
                        $name = explode('_', $val);
                        array_pop($name);

                        return implode('_', $name);
                    })->prepend(new TableCell(''))->toArray(),
                ),
                $body->toArray(),
                $style
            );
        }
    }
}
