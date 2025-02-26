<?php

namespace Exxtensio\EcommerceDashboard\Console\Commands\Demo;

use Illuminate\Console\Command;
use Exxtensio\EcommerceDashboard\Models\Cart;
use Exxtensio\EcommerceDashboard\Models\CartItem;
use Exxtensio\EcommerceDashboard\Models\Product;
use Exxtensio\EcommerceDashboard\Models\User;

class DemoCartCommand extends Command
{
    protected $signature = 'dashboard:demo-cart';

    public function handle(): void
    {
        $this->info('Step 10: Processing carts data...');
        $users = User::all();
        $products = Product::all();

        Cart::factory()
            ->count(100)
            ->has(
                CartItem::factory()
                    ->count(rand(1,5))
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

        Cart::factory()
            ->count(20)
            ->has(
                CartItem::factory()
                    ->count(rand(1,5))
                    ->state(function (array $attributes) use ($products) {
                        $random = $products->random();
                        return [
                            'product_id' => $random->id,
                            'price' => $random->prices->firstWhere('country', 'PA')->price,
                        ];
                    }),
                'items'
            )
            ->state(fn(array $attributes) => [
                'user_id' => $users->random()->id,
                'country' => 'PA',
            ])
            ->create();

        Cart::factory()
            ->count(30)
            ->has(
                CartItem::factory()
                    ->count(rand(1,5))
                    ->state(function (array $attributes) use ($products) {
                        $random = $products->random();
                        return [
                            'product_id' => $random->id,
                            'price' => $random->prices->firstWhere('country', 'EC')->price,
                        ];
                    }),
                'items'
            )
            ->state(fn(array $attributes) => [
                'user_id' => $users->random()->id,
                'country' => 'EC',
            ])
            ->create();
    }
}
