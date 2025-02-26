<?php

namespace Exxtensio\EcommerceDashboard\Models;

use Exxtensio\EcommerceDashboard\Traits;
use Illuminate\Database\Eloquent\Relations;

class OrderItem extends \Exxtensio\EcommerceCore\Models\OrderItem
{
    use Traits\HasActivity;

    protected $with = [];

    public function order(): Relations\BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function product(): Relations\BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
