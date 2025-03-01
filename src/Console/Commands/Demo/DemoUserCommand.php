<?php

namespace Exxtensio\EcommerceDashboard\Console\Commands\Demo;

use Illuminate\Console\Command;
use Exxtensio\EcommerceDashboard\Models;

class DemoUserCommand extends Command
{
    protected $signature = 'dashboard:demo-user';

    public function handle(): void
    {
        $this->info('Step 9: Processing users data...');

        $role = Models\Role::where('name', 'customer')->first();
        Models\User::factory()
            ->count(500)
            ->afterCreating(function (Models\User $user) use ($role) {
                $user->roles()->sync([$role->id]);
            })
            ->create();
    }
}
