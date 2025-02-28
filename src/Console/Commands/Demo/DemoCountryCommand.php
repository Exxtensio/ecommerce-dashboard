<?php

namespace Exxtensio\EcommerceDashboard\Console\Commands\Demo;

use Illuminate\Console\Command;
use Exxtensio\EcommerceDashboard\Models;

class DemoCountryCommand extends Command
{
    protected $signature = 'dashboard:demo-country';

    public function handle(): void
    {
        $this->info('Step 3: Setting countries...');
        $currency = Models\Currency::where('code', 'USD')->first();

        Models\Country::where('active', 1)->update(['active' => 0, 'currency_id' => null]);
        Models\Country::where('code', 'US')
            ->update([
                'active' => 1,
                'currency_id' => $currency->id ?? null
            ]);
    }
}
