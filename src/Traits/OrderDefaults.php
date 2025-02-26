<?php

namespace Exxtensio\EcommerceDashboard\Traits;

trait OrderDefaults
{
    protected static function getDefaultStatus(): string
    {
        return config('dashboard.defaults.order_status');
    }

    protected static function getDefaultPaymentStatus(): string
    {
        return config('dashboard.defaults.order_payment_status');
    }
}
