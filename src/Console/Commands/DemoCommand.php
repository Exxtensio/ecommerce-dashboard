<?php

namespace Exxtensio\EcommerceDashboard\Console\Commands;

use Illuminate\Console\Command;

class DemoCommand extends Command
{
    protected $signature = 'dashboard:demo';

    public function handle(): void
    {
        $this->call('down', ['--render' => 'dashboard::errors.demo']);
        $this->call('dashboard:demo-truncate');
        $this->call('dashboard:demo-storage');
        $this->call('dashboard:demo-country');
        $this->call('dashboard:demo-brand');
        $this->call('dashboard:demo-category');
        $this->call('dashboard:demo-attribute');
        $this->call('dashboard:demo-product');
        $this->call('dashboard:demo-user');
        $this->call('dashboard:demo-review');
        $this->call('dashboard:demo-cart');
        $this->call('dashboard:demo-role');
        $this->call('up');

        $this->info('All steps completed successfully.');
    }
}
