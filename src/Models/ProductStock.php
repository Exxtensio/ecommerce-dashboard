<?php

namespace Exxtensio\EcommerceDashboard\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Exxtensio\EcommerceDashboard\Database\Factories\ProductStockFactory;

class ProductStock extends \Exxtensio\EcommerceCore\Models\Product\ProductStock
{
    use HasFactory;

    protected static function newFactory(): Factory|ProductStockFactory|null
    {
        return ProductStockFactory::new();
    }
}
