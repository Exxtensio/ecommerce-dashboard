<?php

namespace Exxtensio\EcommerceDashboard\Console\Commands\Demo;

use Illuminate\Console\Command;
use Exxtensio\EcommerceDashboard\Models;

class DemoCartCommand extends Command
{
    protected $signature = 'dashboard:demo-cart';

    public function handle(): void
    {
        $this->info('Step 11: Processing carts data...');
        $users = Models\User::all();
        $products = Models\Product::all();

        Models\Cart::factory()
            ->count(1000)
            ->has(
                Models\CartItem::factory()
                    ->count(rand(3,5))
                    ->state(function (array $attributes) use ($products) {
                        $random = $products->random();
                        return [
                            'product_id' => $random->id,
                            'price' => $random->prices->firstWhere('country', 'US')->price,
                        ];
                    }),
                'items'
            )
            ->state(fn(array $attributes) => [
                'user_id' => $users->random()->id,
                'country' => 'US',
            ])
            ->create();
    }
}
