<?php

namespace Exxtensio\EcommerceDashboard\Console\Commands;

use Illuminate\Console\Command;
use Exxtensio\EcommerceDashboard\Contracts\Permission as PermissionContract;

class CreatePermissionCommand extends Command
{
    protected $signature = 'dashboard:create-permission
                {name : The name of the permission}
                {group : The group of the permission}
                {guard? : The name of the guard}';

    protected $description = 'Create a permission';

    public function handle(): void
    {
        $permissionClass = app(PermissionContract::class);
        $permission = $permissionClass::findOrCreate($this->argument('name'), $this->argument('group'), $this->argument('guard'));

        $this->info("Permission `$permission->name` ".($permission->wasRecentlyCreated ? 'created' : 'already exists'));
    }
}
