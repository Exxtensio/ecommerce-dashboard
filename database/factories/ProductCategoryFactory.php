<?php

namespace Exxtensio\EcommerceDashboard\Database\Factories;

use Exxtensio\EcommerceDashboard\Models\ProductBrand;
use Illuminate\Database\Eloquent\Factories\Factory;
use Exxtensio\EcommerceDashboard\Models\ProductCategory;

/**
 * @extends Factory<ProductBrand>
 */
class ProductCategoryFactory extends Factory
{
    protected $model = ProductCategory::class;

    public function definition(): array
    {
        return [
            'summary' => fake()->text(55),
            'description' => fake()->text(500),
            'created_at' => $this->faker->dateTimeBetween('-14 days'),
        ];
    }
}
