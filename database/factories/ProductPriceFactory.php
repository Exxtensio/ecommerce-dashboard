<?php

namespace Exxtensio\EcommerceDashboard\Database\Factories;

use Exxtensio\EcommerceDashboard\Models\ProductPrice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductPrice>
 */
class ProductPriceFactory extends Factory
{
    protected $model = ProductPrice::class;

    public function definition(): array
    {
        return [
            'price' => rand(100, 500)
        ];
    }
}
