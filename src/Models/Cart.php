<?php

namespace Exxtensio\EcommerceDashboard\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations;
use Exxtensio\EcommerceDashboard\Database\Factories\CartFactory;
use Exxtensio\EcommerceDashboard\Traits;

class Cart extends \Exxtensio\EcommerceCore\Models\Cart
{
    use Traits\HasActivity,
        HasFactory;

    public function items(): Relations\HasMany
    {
        return $this->hasMany(
            CartItem::class,
            'cart_id',
        );
    }

    public function user(): Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function currentCountry(): Relations\BelongsTo
    {
        return $this->belongsTo(Country::class, 'country', 'code');
    }

    protected static function newFactory(): Factory|CartFactory|null
    {
        return CartFactory::new();
    }
}
