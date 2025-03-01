<?php

namespace Exxtensio\EcommerceDashboard\Http\Controllers;

use Exxtensio\EcommerceDashboard\Resources\CountryResource;

class CountryController extends CollectionController
{
    public function __construct()
    {
        parent::__construct(CountryResource::class);
    }
}
