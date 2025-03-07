<?php

namespace Exxtensio\EcommerceDashboard\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class LanguageMiddleware
{
    /**
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if($user) {
            if(Cache::has("sellexx.language:{$request->user()->id}")) {
                $request->header('Accept-Language', Cache::get("sellexx.language:{$request->user()->id}"));
            } else {
                $request->header('Accept-Language', 'en');
                Cache::put("sellexx.language:{$request->user()->id}", 'en');
            }
            app()->setLocale(Cache::get("sellexx.language:{$request->user()->id}"));
        }
        return $next($request);
    }
}
