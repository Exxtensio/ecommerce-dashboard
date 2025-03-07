<?php

namespace Exxtensio\EcommerceDashboard\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Exxtensio\EcommerceDashboard\Database\Factories\ProductPriceFactory;

class ProductPrice extends \Exxtensio\EcommerceCore\Models\Product\ProductPrice
{
    use HasFactory;

    protected static function newFactory(): Factory|ProductPriceFactory|null
    {
        return ProductPriceFactory::new();
    }
}
