<?php

namespace Exxtensio\EcommerceDashboard\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations;
use Exxtensio\EcommerceDashboard\Database\Factories\ProductCategoryFactory;
use Exxtensio\EcommerceDashboard\Traits;

class ProductCategory extends \Exxtensio\EcommerceCore\Models\Product\ProductCategory
{
    use Traits\HasActivity,
        HasFactory;

    protected $with = ['parent'];

    protected static function newFactory(): Factory|ProductCategoryFactory|null
    {
        return ProductCategoryFactory::new();
    }

    public function parent(): Relations\BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'parent_id');
    }
}
