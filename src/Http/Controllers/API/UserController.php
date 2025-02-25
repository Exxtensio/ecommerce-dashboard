<?php

namespace Exxtensio\EcommerceDashboard\Http\Controllers\API;

use Exxtensio\EcommerceDashboard\Frontend;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController
{
    public function show(Request $request, $id): JsonResponse
    {
        $data = Frontend::user($id, [
            'with' => $request->has('with') ? explode('|', $request->get('with')) : null,
            'only' => $request->has('only') ? explode(',', $request->get('only')) : ['*']
        ]);

        return response()->json($data);
    }
}
