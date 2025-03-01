<?php

namespace Exxtensio\EcommerceDashboard\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Exxtensio\EcommerceDashboard\Database\Factories\ProductBrandFactory;
use Exxtensio\EcommerceDashboard\Traits;
use Illuminate\Support\Facades\Storage;

class ProductBrand extends \Exxtensio\EcommerceCore\Models\Product\ProductBrand
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

    protected static function newFactory(): Factory|ProductBrandFactory|null
    {
        return ProductBrandFactory::new();
    }
}
