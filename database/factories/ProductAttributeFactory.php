<?php

namespace Exxtensio\EcommerceDashboard\Database\Factories;

use Exxtensio\EcommerceDashboard\Models\ProductBrand;
use Illuminate\Database\Eloquent\Factories\Factory;
use Exxtensio\EcommerceDashboard\Models\ProductAttribute;

/**
 * @extends Factory<ProductBrand>
 */
class ProductAttributeFactory extends Factory
{
    protected $model = ProductAttribute::class;

    public function definition(): array
    {
        return [
            'created_at' => $this->faker->dateTimeBetween('-14 days'),
        ];
    }
}
