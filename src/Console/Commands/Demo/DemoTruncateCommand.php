<?php

namespace Exxtensio\EcommerceDashboard\Console\Commands\Demo;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Exxtensio\EcommerceDashboard\Models;

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
            'cart_items'
        ] as $item) {
            DB::table($item)->truncate();
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('roles')->where('name', '!=', 'artisan')->get()->map(function ($role) {
            DB::table('role_has_permissions')->where('role_id', $role->id)->delete();
            DB::table('roles')->where('id', $role->id)->delete();
        });
    }
}
