<?php

namespace Exxtensio\EcommerceDashboard\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class CreateLinkDataCommand extends Command
{
    protected $signature = 'dashboard:link-data';
    protected $description = 'Command description';

    public function handle(Filesystem $filesystem): void
    {
        $target = base_path('vendor/exxtensio/ecommerce-dashboard/public/data');
        $link = public_path('vendor-dashboard-data');

        if ($filesystem->exists($link)) {
            $this->info('The data symbolic link already exists.');
        } else {
            $filesystem->link($target, $link);
            $this->info('Data symbolic link created successfully.');
        }
    }
}
