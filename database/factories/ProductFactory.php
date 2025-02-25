<?php

namespace Exxtensio\EcommerceDashboard\Database\Factories;

use Illuminate\Support\Str;
use Exxtensio\EcommerceDashboard\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $random = Str::random(3);
        $name = fake()->sentence(2);
        return [
            'type' => 'qty',
            'unit' => 'o',
            'step' => 1,
            'name' => "$name $random",
            'slug' => Str::slug("$name $random"),
            'summary' => fake()->text(255),
            'description' => fake()->text(1000)
        ];
    }

    public function active(): Factory
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'active'
        ]);
    }

    public function inactive(): Factory
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'inactive'
        ]);
    }

    public function draft(): Factory
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'draft'
        ]);
    }
}
