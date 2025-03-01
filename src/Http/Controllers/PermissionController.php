<?php

namespace Exxtensio\EcommerceDashboard\Http\Controllers;

use Exxtensio\EcommerceDashboard\Resources\PermissionResource;

class PermissionController extends CollectionController
{
    public function __construct()
    {
        parent::__construct(PermissionResource::class);
    }
}
