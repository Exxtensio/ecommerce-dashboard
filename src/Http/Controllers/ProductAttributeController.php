<?php

namespace Exxtensio\EcommerceDashboard\Http\Controllers;

use Exxtensio\EcommerceDashboard\Resources\ProductAttributeResource;

class ProductAttributeController extends CollectionController
{
    public function __construct()
    {
        parent::__construct(ProductAttributeResource::class);
    }
}
