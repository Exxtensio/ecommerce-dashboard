<?php

namespace Exxtensio\EcommerceDashboard\Http\Controllers;

use Exxtensio\EcommerceDashboard\Resources\CartResource;

class CartController extends CollectionController
{
    public function __construct()
    {
        parent::__construct(CartResource::class);
    }
}
