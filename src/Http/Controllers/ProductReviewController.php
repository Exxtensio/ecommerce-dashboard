<?php

namespace Exxtensio\EcommerceDashboard\Http\Controllers;

use Exxtensio\EcommerceDashboard\Resources\ProductReviewResource;

class ProductReviewController extends CollectionController
{
    public function __construct()
    {
        parent::__construct(ProductReviewResource::class);
    }
}
