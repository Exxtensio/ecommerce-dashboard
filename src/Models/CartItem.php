<?php

namespace Exxtensio\EcommerceDashboard\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations;
use Exxtensio\EcommerceDashboard\Database\Factories\CartItemFactory;
use Exxtensio\EcommerceDashboard\Traits;

class CartItem extends \Exxtensio\EcommerceCore\Models\CartItem
{
    use Traits\HasActivity,
        HasFactory;

    public function cart(): Relations\BelongsTo
    {
        return $this->belongsTo(
            Cart::class,
            'cart_id'
        );
    }

    public function product(): Relations\BelongsTo
    {
        return $this->belongsTo(
            Product::class,
            'product_id'
        );
    }

    protected static function newFactory(): Factory|CartItemFactory|null
    {
        return CartItemFactory::new();
    }
}
