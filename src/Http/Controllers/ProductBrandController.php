<?php

namespace Exxtensio\EcommerceDashboard\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Exxtensio\EcommerceDashboard\Resources\ProductBrandResource;

class ProductBrandController extends CollectionController
{
    public function __construct()
    {
        parent::__construct(ProductBrandResource::class);
    }

    public function deleteImage(Request $request): JsonResponse
    {
        $model = $this->resourceClass::$model::where('src', $request->get('src'))->first();
        Storage::disk($request->get('disk') ?: config('dashboard.storage_disk', 'public'))
            ->delete($request->get('src'));

        $model->update(['src' => null]);
        return response()->json();
    }
}
