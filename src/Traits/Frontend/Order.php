<?php

namespace Exxtensio\EcommerceDashboard\Traits\Frontend;

use Illuminate\Support\Collection;
use Exxtensio\EcommerceDashboard\Models;
use Exception;
use Illuminate\Support\Facades\DB;

trait Order
{
    /**
     * @throws Exception
     */
    public static function createOrder(string $cardId, ?string $status = null, ?string $paymentStatus = null): Collection|array|string
    {
        try {
            $cart = Models\Cart::findOrFail($cardId);

            if($status && !in_array($status, collect(Models\Order::getStatusesMap())->keys()->toArray()))
                throw new Exception("The specified order status does not exist.", 404);

            if($paymentStatus && !in_array($paymentStatus, collect(Models\Order::getPaymentStatusesMap())->keys()->toArray()))
                throw new Exception("The specified order payment status does not exist.", 404);

            $result = $cart->items->map(function ($item) use ($cart) {
                if($item->quantity > $item->product->stocks->firstWhere('country', $cart->country)->stock)
                    throw new Exception("Not enough `{$item->product->id}` stock available.", 404);

                if($item->price !== $item->product->prices->firstWhere('country', $cart->country)->price)
                    throw new Exception("The cart data does not match the product `{$item->product->id}`.", 404);

                return [
                    'product_id' => $item->product->id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'amount' => $item->quantity * $item->price,
                ];
            });

            $order = DB::transaction(function () use ($cart, $status, $paymentStatus, $result) {
                $order = Models\Order::create([
                    'user_id' => $cart->user_id,
                    'country' => $cart->country,
                    'amount' => $result->sum('amount'),
                    'status' => $status ?? Models\Order::getDefaultStatus(),
                    'payment_status' => $paymentStatus ?? Models\Order::getDefaultPaymentStatus(),
                ]);

                $result->map(function ($item) use ($cart, $order) {
                    $product = Models\Product::find($item['product_id']);
                    $product->stocks->firstWhere('country', $cart->country)->decrement('stock', $item['quantity']);

                    Models\OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                    ]);
                });

                $cart->items()->delete();
                $cart->delete();

                return $order;
            });

            return collect(json_decode(json_encode(
                Models\Order::find($order->id)->toArray()
            )));
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), is_int($e->getCode()) ? $e->getCode() : 500);
        }
    }

    /**
     * @throws Exception
     */
    public static function updateOrder(string $orderId, string $key, string $value): Collection|array|string
    {
        try {
            $order = Models\Order::findOrFail($orderId);

            if(!in_array($key, ['status', 'payment_status']))
                throw new Exception("You can only update the status and payment status.", 404);

            if($key === 'status' && !in_array($value, collect(Models\Order::getStatusesMap())->keys()->toArray()))
                throw new Exception("The specified order status does not exist.", 404);

            if($key === 'payment_status' && !in_array($value, collect(Models\Order::getPaymentStatusesMap())->keys()->toArray()))
                throw new Exception("The specified order payment status does not exist.", 404);

            $order = DB::transaction(function () use ($order, $key, $value) {
                $order->update([$key => $value]);
                return $order;
            });

            return collect(json_decode(json_encode(
                Models\Order::find($order->id)->toArray()
            )));
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), is_int($e->getCode()) ? $e->getCode() : 500);
        }
    }
}
