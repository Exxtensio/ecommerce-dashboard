<?php

namespace Exxtensio\EcommerceDashboard\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    /**
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!config('dashboard.key'))
            abort(403, 'Please ensure environment variable SELLEXX_DASHBOARD_KEY is defined in your .env file');

        if(!config('dashboard.secret'))
            abort(403, 'Please ensure environment variable SELLEXX_DASHBOARD_SECRET is defined in your .env file');

        if (!auth()->check()) return redirect('login');

        return $next($request);
    }
}
