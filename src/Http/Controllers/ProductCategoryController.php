<?php

namespace Exxtensio\EcommerceDashboard\Http\Controllers;

use Exxtensio\EcommerceDashboard\Resources\ProductCategoryResource;

class ProductCategoryController extends CollectionController
{
    public function __construct()
    {
        parent::__construct(ProductCategoryResource::class);
    }
}
