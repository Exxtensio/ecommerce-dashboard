<?php

namespace Exxtensio\EcommerceDashboard\Http\Controllers;

use Exxtensio\EcommerceDashboard\Resources\ActivityResource;

class ActivityController extends CollectionController
{
    public function __construct()
    {
        parent::__construct(ActivityResource::class);
    }
}
