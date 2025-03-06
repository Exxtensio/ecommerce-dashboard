<?php

namespace Exxtensio\EcommerceDashboard\Database\Factories;

use Illuminate\Support\Str;
use Exxtensio\EcommerceDashboard\Models\ProductBrand;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductBrand>
 */
class ProductBrandFactory extends Factory
{
    protected $model = ProductBrand::class;

    public function definition(): array
    {
        return [
            'name' => $company = fake()->unique()->company(),
            'slug' => Str::slug($company),
            'summary' => fake()->text(55),
            'description' => fake()->text(500),
            'created_at' => $this->faker->dateTimeBetween('-14 days'),
        ];
    }
}
