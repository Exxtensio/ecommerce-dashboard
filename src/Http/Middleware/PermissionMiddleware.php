<?php

namespace Exxtensio\EcommerceDashboard\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Exxtensio\EcommerceDashboard\Exceptions\UnauthorizedException;

class PermissionMiddleware
{
    public function handle($request, Closure $next, $permission, $guard = null)
    {
        $authGuard = Auth::guard($guard);

        $user = $authGuard->user();

        if (!$user) throw UnauthorizedException::notLoggedIn();

        if (!method_exists($user, 'hasAnyPermission')) throw UnauthorizedException::missingTraitHasRoles($user);

        $permissions = is_array($permission) ? $permission : explode('|', $permission);

        if (! $user->canAny($permissions)) throw UnauthorizedException::forPermissions($permissions);

        return $next($request);
    }

    /**
     * Specify the permission and guard for the middleware.
     *
     * @param array|string $permission
     * @param string|null $guard
     * @return string
     */
    public static function using(array|string $permission, string $guard = null): string
    {
        $permissionString = is_string($permission) ? $permission : implode('|', $permission);
        $args = is_null($guard) ? $permissionString : "$permissionString,$guard";

        return static::class.':'.$args;
    }
}
