<?php

namespace Exxtensio\EcommerceDashboard\Http\Controllers;

use Exxtensio\EcommerceDashboard\Resources\ProductBrandResource;

class ProductBrandController extends CollectionController
{
    public function __construct()
    {
        parent::__construct(ProductBrandResource::class);
    }
}
