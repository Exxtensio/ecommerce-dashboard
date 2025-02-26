<?php

namespace Exxtensio\EcommerceDashboard\Traits;

trait ProductDefaults
{
    protected static function getDefaultType(): ?string
    {
        return config('dashboard.defaults.product_type');
    }

    protected static function getDefaultUnit(): ?string
    {
        return config('dashboard.defaults.product_unit');
    }

    protected static function getDefaultStep(): float|int
    {
        return config('dashboard.defaults.product_step');
    }

    protected static function getDefaultPlaces(): float|int
    {
        return config('dashboard.defaults.product_places');
    }

    protected static function getDefaultMin(): float|int
    {
        return config('dashboard.defaults.product_min');
    }

    protected static function getDefaultMax(): float|int
    {
        return config('dashboard.defaults.product_max');
    }

    protected static function getDefaultStatus(): string
    {
        return config('dashboard.defaults.product_status');
    }
}
