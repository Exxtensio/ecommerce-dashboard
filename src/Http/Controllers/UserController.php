<?php

namespace Exxtensio\EcommerceDashboard\Http\Controllers;

use Exxtensio\EcommerceDashboard\Resources\UserResource;

class UserController extends CollectionController
{
    public function __construct()
    {
        parent::__construct(UserResource::class);
    }
}
