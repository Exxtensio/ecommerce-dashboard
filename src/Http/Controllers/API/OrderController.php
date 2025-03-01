<?php

namespace Exxtensio\EcommerceDashboard\Http\Controllers\API;

use Exxtensio\EcommerceDashboard\Frontend;
use Exxtensio\EcommerceDashboard\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController
{
    public function create(Request $request): JsonResponse
    {
        $request->validate([
            'cart_id' => 'required|ulid|exists:carts,id',
            'status' => [
                'sometimes',
                Rule::in(collect(Order::getStatusesMap())->keys()->toArray()),
            ],
            'payment_status' => [
                'sometimes',
                Rule::in(collect(Order::getPaymentStatusesMap())->keys()->toArray()),
            ],
        ]);

        try {
            $data = Frontend::createOrder(
                $request->get('cart_id'),
                $request->get('status'),
                $request->get('payment_status'),
            );
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], is_int($e->getCode()) ? $e->getCode() : 500);
        }
    }

    public function update(Request $request): JsonResponse
    {
        $request->request->add(['order_id' => $request->route('id')]);
        $request->validate([
            'order_id' => 'required|ulid|exists:orders,id',
            'key' => 'required|string|in:status,payment_status',
            'value' => [
                'required',
                'string',
                Rule::in($request->get('key') === 'status'
                    ? collect(Order::getStatusesMap())->keys()->toArray()
                    : collect(Order::getPaymentStatusesMap())->keys()->toArray()
                )
            ]
        ]);

        try {
            $data = Frontend::updateOrder(
                $request->get('order_id'),
                $request->get('key'),
                $request->get('value'),
            );
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], is_int($e->getCode()) ? $e->getCode() : 500);
        }
    }
}
