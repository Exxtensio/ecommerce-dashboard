<?php

namespace Exxtensio\EcommerceDashboard\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Exxtensio\EcommerceDashboard\Traits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Exxtensio\EcommerceDashboard\Database\Factories\OrderItemFactory;
use Illuminate\Database\Eloquent\Relations;

class OrderItem extends \Exxtensio\EcommerceCore\Models\OrderItem
{
    use Traits\HasActivity,
        HasFactory;

    protected $with = [];

    public function order(): Relations\BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function product(): Relations\BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    protected static function newFactory(): Factory|OrderItemFactory|null
    {
        return OrderItemFactory::new();
    }
}
