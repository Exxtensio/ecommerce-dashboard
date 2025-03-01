<?php

namespace Exxtensio\EcommerceDashboard\Http\Controllers;

use Exxtensio\EcommerceDashboard\Resources\OrderResource;

class OrderController extends CollectionController
{
    public function __construct()
    {
        parent::__construct(OrderResource::class);
    }
}
