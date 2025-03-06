<?php

namespace Exxtensio\EcommerceDashboard\Http\Controllers;

use Exxtensio\EcommerceDashboard\Resources\RoleResource;

class RoleController extends CollectionController
{
    public function __construct()
    {
        parent::__construct(RoleResource::class);
    }
}
