<?php

namespace Exxtensio\EcommerceDashboard\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations;
use Exxtensio\EcommerceDashboard\Database\Factories\ProductFactory;
use Exxtensio\EcommerceDashboard\Traits;

class Product extends \Exxtensio\EcommerceCore\Models\Product\Product
{
    use Traits\HasActivity,
        Traits\ProductDefaults,
        Traits\ProductOptions,
        HasFactory;

    protected $with = [];

    public function brand(): Relations\BelongsTo
    {
        return $this->belongsTo(
            ProductBrand::class,
            'product_brand_id'
        );
    }

    public function categories(): Relations\BelongsToMany
    {
        return $this->belongsToMany(
            ProductCategory::class,
            'product_category',
            'product_id',
            'product_category_id'
        );
    }

    public function attributes(): Relations\BelongsToMany
    {
        return $this->belongsToMany(
            ProductAttribute::class,
            'product_attribute',
            'product_id',
            'product_attribute_id'
        );
    }

    public function images(): Relations\HasMany
    {
        return $this->hasMany(
            ProductImage::class,
            'product_id'
        );
    }

    public function prices(): Relations\HasMany
    {
        return $this->hasMany(
            ProductPrice::class,
            'product_id'
        );
    }

    public function stocks(): Relations\HasMany
    {
        return $this->hasMany(
            ProductStock::class,
            'product_id'
        );
    }

    public function reviews(): Relations\HasMany
    {
        return $this->hasMany(
            ProductReview::class,
            'product_id'
        );
    }

    public function translations(): Relations\MorphMany
    {
        return $this->morphMany(
            Translation::class,
            'model'
        );
    }

    protected static function newFactory(): Factory|ProductFactory|null
    {
        return ProductFactory::new();
    }
}
