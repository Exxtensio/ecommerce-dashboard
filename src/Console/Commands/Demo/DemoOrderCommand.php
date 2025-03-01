<?php

namespace Exxtensio\EcommerceDashboard\Console\Commands\Demo;

use Illuminate\Console\Command;
use Exxtensio\EcommerceDashboard\Models;

class DemoOrderCommand extends Command
{
    protected $signature = 'dashboard:demo-order';

    public function handle(): void
    {
        $this->info('Step 12: Processing orders data...');
        $users = Models\User::all();
        $products = Models\Product::all();

        Models\Order::factory()
            ->count(1000)
            ->has(
                Models\OrderItem::factory()
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
                'amount' => 0,
                'status' => 'new',
                'payment_status' => 'processing',
            ])
            ->afterCreating(function (Models\Order $order) {
                $order->update([
                    'amount' => $order->items->sum('price')
                ]);
            })
            ->create();

        Models\Order::factory()
            ->count(1000)
            ->has(
                Models\OrderItem::factory()
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
                'amount' => 0,
                'status' => 'completed',
                'payment_status' => 'paid',
            ])
            ->afterCreating(function (Models\Order $order) {
                $order->update([
                    'amount' => $order->items->sum('price')
                ]);
            })
            ->create();

        Models\Order::factory()
            ->count(200)
            ->has(
                Models\OrderItem::factory()
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
                'amount' => 0,
                'status' => 'failed',
                'payment_status' => 'failed',
            ])
            ->afterCreating(function (Models\Order $order) {
                $order->update([
                    'amount' => $order->items->sum('price')
                ]);
            })
            ->create();
    }
}
