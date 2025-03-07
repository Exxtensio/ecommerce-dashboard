<?php

namespace Exxtensio\EcommerceDashboard\Providers;

use Exxtensio\EcommerceDashboard\Models\User;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Illuminate\Filesystem\Filesystem;
use Exxtensio\EcommerceDashboard\Http\Middleware;
use Exception;

class DashboardServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(Includes\LoadServiceProvider::class);
        $this->app->register(Includes\ConsoleServiceProvider::class);
        $this->app->register(Includes\RouteServiceProvider::class);
        $this->app->register(Includes\EventServiceProvider::class);
        $this->app->register(Includes\PermissionServiceProvider::class);
        $this->app->register(Includes\ActivityLogServiceProvider::class);
        $this->app->register(Includes\PolicyServiceProvider::class);

        $this->registerMiddlewares();
        $this->registerHelper();
    }

    public function boot(Filesystem $filesystem): void
    {
        if (!$filesystem->exists(config_path('dashboard.php')))
            $filesystem->copy(__DIR__ . '/../../config/dashboard.php', config_path('dashboard.php'));

        Blade::directive('sellexxVite', function ($path) use ($filesystem) {
            $manifestPath = base_path('vendor/exxtensio/ecommerce-dashboard/public/build/manifest.json');

            if (!$filesystem->exists($manifestPath))
                throw new Exception("Vite manifest not found at: $manifestPath");

            $manifest = json_decode($filesystem->get($manifestPath), true);
            $file = $manifest[trim($path, "'")] ?? null;

            if (!$file)
                throw new Exception("Unable to find Vite asset: $path");

            $jsUrl = asset("vendor-dashboard-assets/{$file['file']}");
            $cssTag = isset($file['css'])
                ? "<link rel='stylesheet' href='" . asset("vendor-dashboard-assets/{$file['css'][0]}") . "' />"
                : '';

            return "$cssTag<script type='module' src='$jsUrl'></script>";
        });

        Config::set('auth.providers.users.model', User::class);
        Config::set('ecommerce.exchangerateApiKey', '376fcc872c85403fd0f3704b');

        Config::set('broadcasting.default', 'reverb');
    }

    public function registerMiddlewares(): void
    {
        $router = $this->app['router'];
        $router->aliasMiddleware('sellexx-data', Middleware\DataMiddleware::class);
        $router->aliasMiddleware('sellexx-auth', Middleware\AuthMiddleware::class);
        $router->aliasMiddleware('sellexx-response', Middleware\APIResponseMiddleware::class);
        $router->aliasMiddleware('sellexx-authenticated', Middleware\AuthenticatedMiddleware::class);
        $router->aliasMiddleware('sellexx-lang', Middleware\LanguageMiddleware::class);
    }

    protected function registerHelper(): void
    {
        require_once __DIR__.'/../../helpers/helpers.php';
    }
}
