<?php

namespace Exxtensio\EcommerceDashboard\Console\Commands\Demo;

use Illuminate\Console\Command;
use Exxtensio\EcommerceDashboard\Models\User;
use Exxtensio\EcommerceDashboard\Models\Role;

class DemoUserCommand extends Command
{
    protected $signature = 'dashboard:demo-user';

    public function handle(): void
    {
        $this->info('Step 8: Processing users data...');

        $role = Role::where('name', 'customer')->first();
        User::factory()
            ->count(100)
            ->hasAttached($role)
            ->create();
    }
}
