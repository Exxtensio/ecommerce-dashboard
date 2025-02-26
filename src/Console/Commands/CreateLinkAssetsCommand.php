<?php

namespace Exxtensio\EcommerceDashboard\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class CreateLinkAssetsCommand extends Command
{
    protected $signature = 'dashboard:link-assets';
    protected $description = 'Command description';

    public function handle(Filesystem $filesystem): void
    {
        $target = base_path('vendor/exxtensio/ecommerce-dashboard/public/build');
        $link = public_path('vendor-dashboard-assets');

        if ($filesystem->exists($link)) {
            $this->info('The assets symbolic link already exists.');
        } else {
            $filesystem->link($target, $link);
            $this->info('Assets symbolic link created successfully.');
        }
    }
}
