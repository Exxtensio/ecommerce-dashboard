<?php

namespace Exxtensio\EcommerceDashboard\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Exxtensio\EcommerceDashboard\Models\ProductStock;

/**
 * @extends Factory<ProductStock>
 */
class ProductStockFactory extends Factory
{
    protected $model = ProductStock::class;

    public function definition(): array
    {
        return [
            'stock' => rand(12, 45)
        ];
    }
}
