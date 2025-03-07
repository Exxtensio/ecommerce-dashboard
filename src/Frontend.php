<?php

namespace Exxtensio\EcommerceDashboard;

use Illuminate\Support\Facades\Facade;
use Exxtensio\EcommerceDashboard\Traits\Frontend as FrontendTraits;

class Frontend extends Facade
{
    use FrontendTraits\Base,
        FrontendTraits\Country,
        FrontendTraits\Currency,
        FrontendTraits\Brand,
        FrontendTraits\Category,
        FrontendTraits\Attribute,
        FrontendTraits\Product,
        FrontendTraits\User,
        FrontendTraits\Cart,
        FrontendTraits\Order;

    public function __invoke($request, $next) {}

    protected static function getFacadeAccessor(): string
    {
        return 'frontend';
    }
}
