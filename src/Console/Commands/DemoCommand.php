<?php

namespace Exxtensio\EcommerceDashboard\Console\Commands;

use Illuminate\Console\Command;

class DemoCommand extends Command
{
    protected $signature = 'dashboard:demo';

    public function handle(): void
    {
        $this->call('down', ['--render' => 'dashboard::errors.demo']);
        $this->call('dashboard:demo-truncate'); // 1
        $this->call('dashboard:demo-storage'); // 2
        $this->call('dashboard:demo-country'); // 3
        $this->call('dashboard:demo-brand'); // 4
        $this->call('dashboard:demo-category'); // 5
        $this->call('dashboard:demo-attribute'); // 6
        $this->call('dashboard:demo-product'); // 7
        $this->call('dashboard:demo-role'); // 8
        $this->call('dashboard:demo-user'); // 9
        $this->call('dashboard:demo-review'); // 10
        $this->call('dashboard:demo-cart'); // 11
        $this->call('dashboard:demo-order'); // 12
        $this->call('dashboard:demo-dashboard'); // 13
        $this->call('up');

        $this->info('All steps completed successfully.');
    }
}
