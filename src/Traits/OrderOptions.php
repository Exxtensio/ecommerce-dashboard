<?php

namespace Exxtensio\EcommerceDashboard\Traits;

use Illuminate\Support\Str;

trait OrderOptions
{
    protected static function getStatuses(): array
    {
        return collect(self::getStatusesMap())
            ->keys()->mapWithKeys(fn($key) => [$key => Str::title(str_replace('_', ' ', $key))])
            ->toArray();
    }

    protected static function getPaymentStatuses(): array
    {
        return collect(self::getPaymentStatusesMap())
            ->keys()->mapWithKeys(fn($key) => [$key => Str::title(str_replace('_', ' ', $key))])
            ->toArray();
    }

    protected static function getStatusesMap(): array
    {
        return config('dashboard.order_statuses', [
            'new' => 'warning',
            'pending' => 'warning',
            'processing' => 'warning',
            'on_hold' => 'warning',
            'completed' => 'warning',
            'shipped' => 'warning',
            'delivered' => 'success',
            'canceled' => 'danger',
            'failed' => 'danger',
            'refunded' => 'danger',
            'partially_refunded' => 'danger',
            'returned' => 'danger',
            'rejected' => 'danger'
        ]);
    }

    protected static function getPaymentStatusesMap(): array
    {
        return config('dashboard.order_payment_statuses', [
            'processing' => 'warning',
            'paid' => 'success',
            'failed' => 'danger',
            'refunded' => 'danger',
            'partially_refunded' => 'danger',
            'canceled' => 'danger',
            'expired' => 'danger',
            'chargeback' => 'danger',
            'on_hold' => 'warning',
            'authorized' => 'warning'
        ]);
    }
}
