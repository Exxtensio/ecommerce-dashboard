<?php

namespace Exxtensio\EcommerceDashboard\Http\Controllers\API;

use Exxtensio\EcommerceDashboard\Frontend;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CartController
{
    public function create(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'country_code' => [
                'required',
                Rule::exists('countries', 'code')->where('active', 1)
            ],
            'product_id' => 'required|ulid|exists:products,id',
            'quantity' => 'required|decimal:1|gt:0.0'
        ]);

        try {
            $data = Frontend::createOrUpdateCart(
                $request->get('user_id'),
                $request->get('country_code'),
                $request->get('product_id'),
                $request->get('quantity')
            );
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], $e->getCode() ?? 500);
        }
    }
}
