<?php

namespace Exxtensio\EcommerceDashboard\Traits\Frontend;

use Illuminate\Support\Collection;
use Exxtensio\EcommerceDashboard\Models;
use Exception;
use Illuminate\Support\Facades\DB;

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

            $cart = DB::transaction(function () use ($userId, $product, $countryCode, $quantity) {
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

                return $cart;
            });

            return collect(json_decode(json_encode(
                Models\Cart::find($cart->id)->toArray()
            )));
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), is_int($e->getCode()) ? $e->getCode() : 500);
        }
    }
}
