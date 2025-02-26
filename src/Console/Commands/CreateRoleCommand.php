<?php

namespace Exxtensio\EcommerceDashboard\Console\Commands;

use Illuminate\Console\Command;
use Exxtensio\EcommerceDashboard\Contracts\Permission as PermissionContract;
use Exxtensio\EcommerceDashboard\Contracts\Role as RoleContract;
use Exxtensio\EcommerceDashboard\PermissionRegistrar;

class CreateRoleCommand extends Command
{
    protected $signature = 'dashboard:create-role
        {name : The name of the role}
        {guard? : The name of the guard}
        {permissions? : A list of permissions to assign to the role, separated by | }';

    protected $description = 'Create a role';

    public function handle(PermissionRegistrar $permissionRegistrar): void
    {
        $roleClass = app(RoleContract::class);
        $role = $roleClass::findOrCreate($this->argument('name'), $this->argument('guard'));
        $role->givePermissionTo($this->makePermissions($this->argument('permissions')));

        $this->info("Role `$role->name` " . ($role->wasRecentlyCreated ? 'created' : 'updated'));
    }

    protected function makePermissions(array|string $string = null)
    {
        if (empty($string)) return;
        $permissionClass = app(PermissionContract::class);
        $permissions = explode('|', $string);
        $models = [];

        foreach ($permissions as $permission) {
            $models[] = $permissionClass::findOrCreate(trim($permission), $this->argument('guard'));
        }

        return collect($models);
    }
}
