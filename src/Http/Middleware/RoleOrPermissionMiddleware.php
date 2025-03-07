<?php

namespace Exxtensio\EcommerceDashboard\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Exxtensio\EcommerceDashboard\Exceptions\UnauthorizedException;

class RoleOrPermissionMiddleware
{
    public function handle($request, Closure $next, $roleOrPermission, $guard = null)
    {
        $authGuard = Auth::guard($guard);

        $user = $authGuard->user();

        if (!$user) throw UnauthorizedException::notLoggedIn();

        if (!method_exists($user, 'hasAnyRole') || ! method_exists($user, 'hasAnyPermission'))
            throw UnauthorizedException::missingTraitHasRoles($user);

        $rolesOrPermissions = is_array($roleOrPermission) ? $roleOrPermission : explode('|', $roleOrPermission);

        if (!$user->canAny($rolesOrPermissions) && !$user->hasAnyRole($rolesOrPermissions))
            throw UnauthorizedException::forRolesOrPermissions($rolesOrPermissions);

        return $next($request);
    }

    /**
     * Specify the role or permission and guard for the middleware.
     *
     * @param array|string $roleOrPermission
     * @param string|null $guard
     * @return string
     */
    public static function using(array|string $roleOrPermission, string $guard = null): string
    {
        $roleOrPermissionString = is_string($roleOrPermission) ? $roleOrPermission : implode('|', $roleOrPermission);
        $args = is_null($guard) ? $roleOrPermissionString : "$roleOrPermissionString,$guard";

        return static::class.':'.$args;
    }
}
