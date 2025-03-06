<?php

namespace Exxtensio\EcommerceDashboard\Console\Commands;

use Illuminate\Console\Command;

class UpdateCommand extends Command
{
    protected $signature = 'dashboard:update';

    public function handle(): void
    {
        $this->callSilent('down', ['--render' => 'dashboard::errors.update']);

        $this->comment('Step 1: Upgrading Composer...');
        $this->callSilent('composer:run', ['action' => 'update', 'name' => 'exxtensio/ecommerce-core']);
        $this->callSilent('composer:run', ['action' => 'update', 'name' => 'exxtensio/ecommerce-dashboard']);
        $this->info('Upgrade completed successfully.');

        $this->comment('Step 2: Migrating...');
        $this->callSilent('migrate');
        $this->info('Migration completed successfully.');

        $this->callSilent('reverb:restart');
        $this->callSilent('optimize:clear');

        $this->callSilent('up');
        $this->info('All steps completed successfully.');
    }
}
