<?php

namespace Exxtensio\EcommerceDashboard\Http\Middleware;

use Exxtensio\EcommerceDashboard\Models\Product;
use Exxtensio\EcommerceDashboard\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Middleware;

class DataMiddleware extends Middleware
{
    public function share(Request $request): array
    {
        $this->rootView = 'dashboard::app';

        return array_merge(parent::share($request), [
            'appKey' => config('dashboard.key'),
            'appSecret' => config('dashboard.secret'),
            'appLanguage' => config('dashboard.language'),
            'appName' => config('app.name'),
            'appUrl' => config('app.url'),
            'appAdminUrl' => Str::finish(config('app.url'), '/admin'),
            'appMenu' => fn() => $request->user()
                ? app('dashboard')->menu($request->user())
                : null,
            'appPanels' => app('dashboard')->panels(),
            'appPrefixes' => collect(app('dashboard')->resources())->map(function ($resource) {
                return $resource::$prefix;
            })->toArray(),
            'auth' => fn() => $request->user()
                ? $request->user()->only('id', 'name', 'email')
                : null,
        ]);
    }
}
