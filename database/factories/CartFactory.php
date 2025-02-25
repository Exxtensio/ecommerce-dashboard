<?php

namespace Exxtensio\EcommerceDashboard\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Exxtensio\EcommerceDashboard\Models\Cart;

/**
 * @extends Factory<Cart>
 */
class CartFactory extends Factory
{
    protected $model = Cart::class;

    public function definition(): array
    {
        return [];
    }
}
