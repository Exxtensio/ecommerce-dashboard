<?php

namespace Exxtensio\EcommerceDashboard\Console\Commands;

use Illuminate\Console\Command;
use Exxtensio\EcommerceDashboard\PermissionRegistrar;

class CacheResetCommand extends Command
{
    protected $signature = 'dashboard:cache-reset';
    protected $description = 'Reset the permission cache';

    public function handle(): void
    {
        $permissionRegistrar = app(PermissionRegistrar::class);
        $cacheExists = $permissionRegistrar->getCacheRepository()->has($permissionRegistrar->cacheKey);

        if ($permissionRegistrar->forgetCachedPermissions()) {
            $this->info('Permission cache flushed.');
        } elseif ($cacheExists) {
            $this->error('Unable to flush cache.');
        }
    }
}
