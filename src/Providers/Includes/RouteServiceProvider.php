<?php

namespace Exxtensio\EcommerceDashboard\Providers\Includes;

use Illuminate\Support\ServiceProvider;
use Exxtensio\EcommerceDashboard\RouteRegistration;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->booted(function () {
            if ($this->app->routesAreCached())
                return;

            (new RouteRegistration())
                ->withAuthRoutes()
                ->withAdminRoutes()
                ->withApiRoutes();
        });
    }

    public function register(): void {}
}
