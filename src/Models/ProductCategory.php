<?php

namespace Exxtensio\EcommerceDashboard\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations;
use Exxtensio\EcommerceDashboard\Database\Factories\ProductCategoryFactory;
use Exxtensio\EcommerceDashboard\Traits;
use Illuminate\Support\Facades\Storage;

class ProductCategory extends \Exxtensio\EcommerceCore\Models\Product\ProductCategory
{
    use Traits\HasActivity,
        HasFactory;

    protected $appends = [
        'url'
    ];

    public function getUrlAttribute(): ?string
    {
        return $this->src ? Storage::disk(config('dashboard.storage_disk', 'public'))->url($this->src) : null;
    }

    protected static function newFactory(): Factory|ProductCategoryFactory|null
    {
        return ProductCategoryFactory::new();
    }

    public function parent(): Relations\BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'parent_id');
    }

    public function translations(): Relations\MorphMany
    {
        return $this->morphMany(
            Translation::class,
            'model'
        );
    }
}
