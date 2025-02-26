<?php

namespace Exxtensio\EcommerceDashboard\Console\Commands\Demo;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DemoStorageCommand extends Command
{
    protected $signature = 'dashboard:demo-storage';

    public function handle(): void
    {
        $this->info('Step 2: Storage clearing...');

        $folders = ['brands', 'categories', 'products'];
        foreach ($folders as $folder) {
            Storage::disk('public')->deleteDirectory($folder);
        }
    }
}
