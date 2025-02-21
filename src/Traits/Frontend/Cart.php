<?php

namespace Exxtensio\EcommerceDashboard\Traits\Frontend;

use Illuminate\Support\Collection;
use Exxtensio\EcommerceDashboard\Models;
use Exception;

trait Cart
{
    /**
     * @throws Exception
     */
    public static function createOrUpdateCart(int $userId, string $countryCode, string $productId, float $quantity): Collection|array|string
    {
        try {
            $product = Models\Product::findOrFail($productId);

            if($quantity > $product->stocks->firstWhere('country', $countryCode)->stock)
                throw new Exception('Not enough stock available.', 404);

            $cart = Models\Cart::firstOrCreate([
                'user_id' => $userId,
                'country' => $countryCode
            ]);

            Models\CartItem::updateOrCreate(
                [
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                ],
                [
                    'quantity' => $quantity,
                    'price' => $product->prices->firstWhere('country', $countryCode)->price,
                ]
            );

            return collect(json_decode(json_encode(
                Models\Cart::find($cart->id)->toArray()
            )));
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }
}
