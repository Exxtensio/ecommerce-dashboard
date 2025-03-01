<?php

namespace Exxtensio\EcommerceDashboard\Console\Commands\Demo;

use Illuminate\Console\Command;
use Exxtensio\EcommerceDashboard\Models\Role;

class DemoRoleCommand extends Command
{
    protected $signature = 'dashboard:demo-role';

    public function handle(): void
    {
        $this->info('Step 8: Processing roles data...');

        Role::factory()->count(10)->sequence(
            ['name' => 'customer', 'guard_name' => 'web'],
            ['name' => 'admin', 'guard_name' => 'web'],
            ['name' => 'junior-customer-manager', 'guard_name' => 'web'],
            ['name' => 'middle-customer-manager', 'guard_name' => 'web'],
            ['name' => 'senior-customer-manager', 'guard_name' => 'web'],
            ['name' => 'lead-customer-manager', 'guard_name' => 'web'],
            ['name' => 'junior-sales-manager', 'guard_name' => 'web'],
            ['name' => 'middle-sales-manager', 'guard_name' => 'web'],
            ['name' => 'senior-sales-manager', 'guard_name' => 'web'],
            ['name' => 'lead-sales-manager', 'guard_name' => 'web'],
        )->create();
    }
}
