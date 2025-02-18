<?php

namespace Exxtensio\EcommerceDashboard\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Exxtensio\EcommerceDashboard\Database\Factories\ProductBrandFactory;
use Exxtensio\EcommerceDashboard\Traits;

class ProductBrand extends \Exxtensio\EcommerceCore\Models\Product\ProductBrand
{
    use Traits\HasActivity,
        HasFactory;

    protected static function newFactory(): Factory|ProductBrandFactory|null
    {
        return ProductBrandFactory::new();
    }
}
