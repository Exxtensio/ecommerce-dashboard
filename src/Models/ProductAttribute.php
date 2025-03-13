<?php

namespace Exxtensio\EcommerceDashboard\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Exxtensio\EcommerceDashboard\Database\Factories\ProductAttributeFactory;
use Exxtensio\EcommerceDashboard\Traits;
use Illuminate\Database\Eloquent\Relations;

class ProductAttribute extends \Exxtensio\EcommerceCore\Models\Product\ProductAttribute
{
    use Traits\HasActivity,
        HasFactory;

    protected static function newFactory(): Factory|ProductAttributeFactory|null
    {
        return ProductAttributeFactory::new();
    }

    public function translations(): Relations\MorphMany
    {
        return $this->morphMany(
            Translation::class,
            'model'
        );
    }
}
