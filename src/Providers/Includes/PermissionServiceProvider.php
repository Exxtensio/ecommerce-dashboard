<?php

namespace Exxtensio\EcommerceDashboard\Providers\Includes;

use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Routing\Route;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Exxtensio\EcommerceDashboard\Console\Commands;
use Exxtensio\EcommerceDashboard\Contracts\Permission as PermissionContract;
use Exxtensio\EcommerceDashboard\Contracts\Role as RoleContract;
use Exxtensio\EcommerceDashboard\Models;
use Exxtensio\EcommerceDashboard\PermissionRegistrar;

class PermissionServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->offerPublishing();
        $this->registerMacroHelpers();
        $this->registerCommands();
        $this->registerModelBindings();

        $this->callAfterResolving(
            Gate::class, function (Gate $gate, Application $app) {
            $permissionLoader = $app->get(PermissionRegistrar::class);
            $permissionLoader->clearPermissionsCollection();
            $permissionLoader->registerPermissions($gate);
        });

        $this->app->singleton(PermissionRegistrar::class);
    }

    public function register(): void
    {
        $this->callAfterResolving(
            'blade.compiler',
            fn(BladeCompiler $bladeCompiler) => $this->registerBladeExtensions($bladeCompiler)
        );
    }

    protected function offerPublishing(): void
    {
        if (!$this->app->runningInConsole())
            return;

        if (!function_exists('config_path'))
            return;
    }

    protected function registerCommands(): void
    {
        if (!$this->app->runningInConsole())
            return;

        $this->commands([
            Commands\CacheResetCommand::class,
            Commands\CreateRoleCommand::class,
            Commands\CreatePermissionCommand::class,
            Commands\ShowCommand::class,
        ]);
    }

    protected function registerModelBindings(): void
    {
        $this->app->bind(PermissionContract::class, fn($app) => $app->make(Models\Permission::class));
        $this->app->bind(RoleContract::class, fn($app) => $app->make(Models\Role::class));
    }

    public static function bladeMethodWrapper($method, $role, $guard = null): bool
    {
        return auth($guard)->check() && auth($guard)->user()->{$method}($role);
    }

    protected function registerBladeExtensions(BladeCompiler $bladeCompiler): void
    {
        $bladeMethodWrapper = '\\Exxtensio\\EcommerceDashboard\\Includes\\PermissionServiceProvider::bladeMethodWrapper';

        // permission checks
        $bladeCompiler->if('haspermission', fn() => $bladeMethodWrapper('checkPermissionTo', ...func_get_args()));

        // role checks
        $bladeCompiler->if('role', fn() => $bladeMethodWrapper('hasRole', ...func_get_args()));
        $bladeCompiler->if('hasrole', fn() => $bladeMethodWrapper('hasRole', ...func_get_args()));
        $bladeCompiler->if('hasanyrole', fn() => $bladeMethodWrapper('hasAnyRole', ...func_get_args()));
        $bladeCompiler->if('hasallroles', fn() => $bladeMethodWrapper('hasAllRoles', ...func_get_args()));
        $bladeCompiler->if('hasexactroles', fn() => $bladeMethodWrapper('hasExactRoles', ...func_get_args()));
        $bladeCompiler->directive('endunlessrole', fn() => '<?php endif; ?>');
    }

    protected function registerMacroHelpers(): void
    {
        if (!method_exists(Route::class, 'macro'))
            return;

        Route::macro('role', function ($roles = []) {
            /** @var Route $this */
            return $this->middleware('role:' . implode('|', Arr::wrap($roles)));
        });

        Route::macro('permission', function ($permissions = []) {
            /** @var Route $this */
            return $this->middleware('permission:' . implode('|', Arr::wrap($permissions)));
        });
    }
}
