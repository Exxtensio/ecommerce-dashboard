<?php

namespace Exxtensio\EcommerceDashboard\Console\Commands\Demo;

use Exxtensio\EcommerceDashboard\Models\Dashboard;
use Illuminate\Console\Command;

class DemoDashboardCommand extends Command
{
    protected $signature = 'dashboard:demo-dashboard';

    public function handle(): void
    {
        $this->info('Step 13: Processing overview data...');
        $s = now()->subDays(7)->startOfDay();
        $e = now()->endOfDay();

        Dashboard::create(['type' => 'total', 'position' => 1, 'query' => 'users:customer::', 'start' => $s, 'end' => $e]);
        Dashboard::create(['type' => 'total', 'position' => 2, 'query' => 'products:active::', 'start' => $s, 'end' => $e]);
        Dashboard::create(['type' => 'total', 'position' => 3, 'query' => 'reviews:5::', 'start' => $s, 'end' => $e]);
        Dashboard::create(['type' => 'total', 'position' => 4, 'query' => 'reviews:1::', 'start' => $s, 'end' => $e]);
        Dashboard::create(['type' => 'total', 'position' => 5, 'query' => 'carts:US::', 'start' => $s, 'end' => $e]);
        Dashboard::create(['type' => 'total', 'position' => 6, 'query' => 'orders:US:status:completed', 'start' => $s, 'end' => $e]);

        Dashboard::create([
            'title' => 'Top Sales by Brands', 'chart' => 'exx-simple-horizontal-bar-card', 'type' => 'horizontal',
            'position' => 1, 'query' => 'orders:US:completed:amount:brand:', 'start' => $s, 'end' => $e
        ]);
        Dashboard::create([
            'title' => 'New Orders by Brands', 'chart' => 'exx-simple-line-card', 'type' => 'horizontal',
            'position' => 2, 'query' => 'orders:US:new:amount:brand:', 'start' => $s, 'end' => $e
        ]);
        Dashboard::create([
            'title' => 'Order Items by Color', 'chart' => 'exx-simple-line-card', 'type' => 'horizontal',
            'position' => 3, 'query' => 'orders:US:completed:quantity:attributes:Color', 'start' => $s, 'end' => $e
        ]);

        Dashboard::create([
            'title' => 'New Orders by Categories', 'chart' => 'exx-simple-pie-card', 'type' => 'pie',
            'position' => 1, 'query' => 'orders:US:new:amount:categories:', 'start' => $s, 'end' => $e
        ]);
        Dashboard::create([
            'title' => 'Failed Orders by Brands', 'chart' => 'exx-pie-with-variable-radius-card', 'type' => 'pie',
            'position' => 2, 'query' => 'orders:US:failed:quantity:brand:', 'start' => $s, 'end' => $e
        ]);
        Dashboard::create([
            'title' => 'New Orders by Gender', 'chart' => 'exx-simple-donut-card', 'type' => 'pie',
            'position' => 3, 'query' => 'orders:US:new:amount:attributes:Gender', 'start' => $s, 'end' => $e
        ]);
    }
}
