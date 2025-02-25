<?php

namespace Exxtensio\EcommerceDashboard\Console\Commands;

use Illuminate\Console\Command;
use Exception;

class UpdateCurrencyRateCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'dashboard:update-currency-rate';

    /**
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @throws Exception
     */
    public function handle(): void
    {
        app('dashboard')->updateCurrencyRate();
    }
}
