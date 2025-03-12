<?php

namespace Exxtensio\EcommerceDashboard\Models;

use Illuminate\Support\Facades\Storage;

class ProductImage extends \Exxtensio\EcommerceCore\Models\Product\ProductImage
{
    protected $appends = [
        'url'
    ];

    public function getUrlAttribute(): ?string
    {
        return $this->src ? Storage::disk(config('dashboard.storage_disk', 'public'))->url($this->src) : null;
    }
}
