<?php

namespace Exxtensio\EcommerceDashboard\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Exxtensio\EcommerceDashboard\Exceptions\UnauthorizedException;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role, $guard = null)
    {
        $authGuard = Auth::guard($guard);

        $user = $authGuard->user();

        if (!$user) throw UnauthorizedException::notLoggedIn();

        if (!method_exists($user, 'hasAnyRole')) throw UnauthorizedException::missingTraitHasRoles($user);

        $roles = is_array($role) ? $role : explode('|', $role);

        if (!$user->hasAnyRole($roles)) throw UnauthorizedException::forRoles($roles);

        return $next($request);
    }

    /**
     * Specify the role and guard for the middleware.
     *
     * @param array|string $role
     * @param string|null $guard
     * @return string
     */
    public static function using(array|string $role, string $guard = null): string
    {
        $roleString = is_string($role) ? $role : implode('|', $role);
        $args = is_null($guard) ? $roleString : "$roleString,$guard";

        return static::class.':'.$args;
    }
}
