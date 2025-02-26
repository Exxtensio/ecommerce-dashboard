<?php

namespace Exxtensio\EcommerceDashboard\Console\Commands\Demo;

use Illuminate\Console\Command;
use Exxtensio\EcommerceDashboard\Models\ProductBrand;

class DemoBrandCommand extends Command
{
    protected $signature = 'dashboard:demo-brand';

    public function handle(): void
    {
        $this->info('Step 4: Processing brands data...');

        ProductBrand::factory()->count(500)->create();
    }
}
