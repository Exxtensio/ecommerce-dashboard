<?php

namespace Exxtensio\EcommerceDashboard\Http\Controllers;

use Exxtensio\EcommerceDashboard\Resources\CurrencyResource;

class CurrencyController extends CollectionController
{
    public function __construct()
    {
        parent::__construct(CurrencyResource::class);
    }
}
