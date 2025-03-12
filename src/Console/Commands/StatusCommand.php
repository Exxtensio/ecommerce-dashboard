<?php

namespace Exxtensio\EcommerceDashboard\Console\Commands;

use Exxtensio\EcommerceDashboard\Events\Status;
use Illuminate\Console\Command;

class StatusCommand extends Command
{
    protected $signature = 'dashboard:status';

    public function handle(): void
    {
        tryBroadcast(new Status());
    }
}
