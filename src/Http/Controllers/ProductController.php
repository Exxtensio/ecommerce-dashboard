<?php

namespace Exxtensio\EcommerceDashboard\Http\Controllers;

use Exxtensio\EcommerceDashboard\Resources\ProductResource;

class ProductController extends CollectionController
{
    public function __construct()
    {
        parent::__construct(ProductResource::class);
    }
}
