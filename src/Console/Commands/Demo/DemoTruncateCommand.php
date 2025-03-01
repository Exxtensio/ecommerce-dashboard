<?php

namespace Exxtensio\EcommerceDashboard\Console\Commands\Demo;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DemoTruncateCommand extends Command
{
    protected $signature = 'dashboard:demo-truncate';

    public function handle(): void
    {
        $this->info('Step 1: Truncating data...');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        foreach ([
            'log_activity',
            'product_attribute',
            'product_attributes',
            'product_brands',
            'product_categories',
            'product_category',
            'product_images',
            'product_prices',
            'product_reviews',
            'product_stocks',
            'products',
            'carts',
            'cart_items',
            'orders',
            'order_items',
            'role_has_permissions',
            'model_has_permissions',
            'jobs',
            'job_batches',
            'failed_jobs',
            'dashboard',
            'cache_locks',
            'cache',
        ] as $item) {
            DB::table($item)->truncate();
        }

        DB::table('roles')
            ->where('name', '!=', 'artisan')
            ->delete();

        DB::table('permissions')
            ->whereNotIn('name', app('dashboard')->requiredPermissions())
            ->delete();

        DB::table('users')
            ->where('id', '!=', 1)
            ->delete();

        DB::statement("ALTER TABLE users AUTO_INCREMENT = 2");


        DB::table('model_has_roles')
            ->where('model_id', '!=', 1)
            ->delete();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
