<?php

namespace Exxtensio\EcommerceDashboard\Providers\Includes;

use Illuminate\Support\ServiceProvider;

class LoadServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('dashboard', function () {
            return new \Exxtensio\EcommerceDashboard\Dashboard();
        });

        $this->app->singleton('frontend', function () {
            return new \Exxtensio\EcommerceDashboard\Frontend();
        });

        $this->mergeConfigFrom(__DIR__.'/../../../config/dashboard.php', 'dashboard');
        $this->loadMigrationsFrom(__DIR__.'/../../../database/migrations');
        $this->loadViewsFrom(__DIR__.'/../../../resources/views', 'dashboard');
    }

    public function boot(): void {}
}
