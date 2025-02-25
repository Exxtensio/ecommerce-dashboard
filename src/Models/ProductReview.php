<?php

namespace Exxtensio\EcommerceDashboard\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Exxtensio\EcommerceDashboard\Traits;
use Illuminate\Database\Eloquent\Relations;
use Exxtensio\EcommerceDashboard\Database\Factories\ProductReviewFactory;

class ProductReview extends \Exxtensio\EcommerceCore\Models\Product\ProductReview
{
    use Traits\HasActivity,
        HasFactory;

    protected static function newFactory(): Factory|ProductReviewFactory|null
    {
        return ProductReviewFactory::new();
    }

    public function user(): Relations\BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }

    public function product(): Relations\BelongsTo
    {
        return $this->belongsTo(
            Product::class,
            'product_id'
        );
    }
}
