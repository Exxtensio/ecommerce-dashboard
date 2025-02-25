<?php

namespace Exxtensio\EcommerceDashboard\Console\Commands\Demo;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Exxtensio\EcommerceDashboard\Models\Product;
use Exxtensio\EcommerceDashboard\Models\ProductBrand;
use Exxtensio\EcommerceDashboard\Models\ProductPrice;
use Exxtensio\EcommerceDashboard\Models\ProductStock;

class DemoProductCommand extends Command
{
    protected $signature = 'dashboard:demo-product';

    public function handle(): void
    {
        $this->info('Step 7: Processing products data...');
        $brands = ProductBrand::all();

        Product::factory()
            ->count(1500)
            ->active()
            ->state(new Sequence(fn (Sequence $sequence) => ['product_brand_id' => $brands->random()->id]))
            ->has(ProductPrice::factory()->state(fn(array $attributes) => ['country' => 'US']), 'prices')
            ->has(ProductPrice::factory()->state(fn(array $attributes) => ['country' => 'PA']), 'prices')
            ->has(ProductPrice::factory()->state(fn(array $attributes) => ['country' => 'EC']), 'prices')
            ->has(ProductStock::factory()->state(fn(array $attributes) => ['country' => 'US']), 'stocks')
            ->has(ProductStock::factory()->state(fn(array $attributes) => ['country' => 'PA']), 'stocks')
            ->has(ProductStock::factory()->state(fn(array $attributes) => ['country' => 'EC']), 'stocks')
            ->create();

        Product::factory()
            ->count(500)
            ->draft()
            ->state(new Sequence(fn (Sequence $sequence) => ['product_brand_id' => $brands->random()->id]))
            ->has(ProductPrice::factory()->state(fn(array $attributes) => ['country' => 'US']), 'prices')
            ->has(ProductPrice::factory()->state(fn(array $attributes) => ['country' => 'PA']), 'prices')
            ->has(ProductPrice::factory()->state(fn(array $attributes) => ['country' => 'EC']), 'prices')
            ->has(ProductStock::factory()->state(fn(array $attributes) => ['country' => 'US']), 'stocks')
            ->has(ProductStock::factory()->state(fn(array $attributes) => ['country' => 'PA']), 'stocks')
            ->has(ProductStock::factory()->state(fn(array $attributes) => ['country' => 'EC']), 'stocks')
            ->create();

        Product::factory()
            ->count(500)
            ->inactive()
            ->state(new Sequence(fn (Sequence $sequence) => ['product_brand_id' => $brands->random()->id]))
            ->has(ProductPrice::factory()->state(fn(array $attributes) => ['country' => 'US']), 'prices')
            ->has(ProductPrice::factory()->state(fn(array $attributes) => ['country' => 'PA']), 'prices')
            ->has(ProductPrice::factory()->state(fn(array $attributes) => ['country' => 'EC']), 'prices')
            ->has(ProductStock::factory()->state(fn(array $attributes) => ['country' => 'US']), 'stocks')
            ->has(ProductStock::factory()->state(fn(array $attributes) => ['country' => 'PA']), 'stocks')
            ->has(ProductStock::factory()->state(fn(array $attributes) => ['country' => 'EC']), 'stocks')
            ->create();
    }
}
