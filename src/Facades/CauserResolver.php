<?php

namespace Exxtensio\EcommerceDashboard\Facades;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Facade;
use Exxtensio\EcommerceDashboard\CauserResolver as ActivitylogCauserResolver;

/**
 * @method static Model|null resolve(Model|int|string|null $subject = null)
 * @method static ActivitylogCauserResolver resolveUsing(Closure $callback)
 * @method static ActivitylogCauserResolver setCauser(Model|null $causer)
 * @see ActivitylogCauserResolver
 */
final class CauserResolver extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ActivitylogCauserResolver::class;
    }
}
