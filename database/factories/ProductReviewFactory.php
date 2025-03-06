<?php

namespace Exxtensio\EcommerceDashboard\Database\Factories;

use Exxtensio\EcommerceDashboard\Models\ProductReview;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductReview>
 */
class ProductReviewFactory extends Factory
{
    protected $model = ProductReview::class;

    public function definition(): array
    {
        return [
            'rating' => rand(1, 5),
            'comment' => fake()->text(500),
            'created_at' => $this->faker->dateTimeBetween('-14 days'),
        ];
    }
}
