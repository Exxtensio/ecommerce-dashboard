<?php

namespace Exxtensio\EcommerceDashboard\Http\Controllers\API;

use Exxtensio\EcommerceDashboard\Frontend;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductCategoryController
{
    public function index(Request $request): JsonResponse
    {
        $data = Frontend::categories([
            'return' => 'array',
            'only' => $request->has('only') ? explode(',', $request->get('only')) : ['*'],
            'with' => $request->has('with') ? explode('|', $request->get('with')) : null,
            'take' => $request->get('take') ?? null,
            'skip' => $request->get('skip') ?? null,
            'orderBy' => $request->has('orderBy') ? function ($query) use ($request) {
                $columns = explode('|', $request->get('orderBy'));
                foreach ($columns as $column) {
                    [$field, $direction] = explode(',', $column);
                    $query->orderBy(trim($field), trim($direction));
                }
            } : null,
            'where' => $request->has('where') ? function ($query) use ($request) {
                $columns = explode('|', $request->get('where'));
                foreach ($columns as $column) {
                    [$field, $operator, $value] = explode(',', $column);
                    $query->where(trim($field), trim($operator), trim($value));
                }
            } : null,
        ]);

        return response()->json($data);
    }

    public function show(Request $request, $id): JsonResponse
    {
        $data = Frontend::category($id, [
            'with' => $request->has('with') ? explode('|', $request->get('with')) : null,
            'only' => $request->has('only') ? explode(',', $request->get('only')) : ['*']
        ]);

        return response()->json($data);
    }
}
