<?php

namespace Exxtensio\EcommerceDashboard\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Exxtensio\EcommerceDashboard\Models\CartItem;

/**
 * @extends Factory<CartItem>
 */
class CartItemFactory extends Factory
{
    protected $model = CartItem::class;

    public function definition(): array
    {
        return [
            'quantity' => rand(3, 9)
        ];
    }
}
