<?php

namespace Exxtensio\EcommerceDashboard\Facades;

use Illuminate\Support\Facades\Facade;
use Exxtensio\EcommerceDashboard\CauserResolver as ActivitylogCauserResolver;

/**
 * @method static \Illuminate\Database\Eloquent\Model|null resolve(\Illuminate\Database\Eloquent\Model|int|string|null $subject = null)
 * @method static \Exxtensio\EcommerceDashboard\CauserResolver resolveUsing(\Closure $callback)
 * @method static \Exxtensio\EcommerceDashboard\CauserResolver setCauser(\Illuminate\Database\Eloquent\Model|null $causer)
 * @see \Exxtensio\EcommerceDashboard\CauserResolver
 */
final class CauserResolver extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ActivitylogCauserResolver::class;
    }
}
