<?php

namespace Exxtensio\EcommerceDashboard\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Exxtensio\EcommerceDashboard\Traits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Exxtensio\EcommerceDashboard\Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Relations;

class Order extends \Exxtensio\EcommerceCore\Models\Order
{
     use Traits\HasActivity,
         HasFactory,
         Traits\OrderOptions,
         Traits\OrderDefaults;

     protected $with = [
         'items',
         'customer'
    ];

     public function items(): Relations\HasMany
    {
        return $this->hasMany(
            OrderItem::class,
            'order_id'
        );
    }

    public function customer(): Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function currentCountry(): Relations\BelongsTo
    {
        return $this->belongsTo(Country::class, 'country', 'code');
    }

    protected static function newFactory(): Factory|OrderFactory|null
    {
        return OrderFactory::new();
    }
}
