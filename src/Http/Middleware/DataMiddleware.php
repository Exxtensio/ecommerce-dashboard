<?php

namespace Exxtensio\EcommerceDashboard\Http\Middleware;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Inertia\Middleware;
use Composer\InstalledVersions;

class DataMiddleware extends Middleware
{
    /**
     * @throws ConnectionException
     */
    public function share(Request $request): array
    {
        $this->rootView = 'dashboard::app';

        if(class_exists(InstalledVersions::class) && InstalledVersions::isInstalled('exxtensio/ecommerce-dashboard')) {
            $currentVersion = InstalledVersions::getPrettyVersion('exxtensio/ecommerce-dashboard');

            $releases = Cache::get('sellexx.dashboard.version.releases');
            if(!Cache::has('sellexx.dashboard.version.releases') || ($releases && str_replace('v', '', $releases['currentVersion']) >= $currentVersion)) {
                $response = Http::withHeaders([
                    'Accept' => 'application/vnd.github.v3+json'
                ])->get('https://api.github.com/repos/Exxtensio/ecommerce-dashboard/releases');

                if($response->status() === 200) {
                    $latestReleases = collect($response->json())->map(function ($release) use ($currentVersion) {
                        if(str_replace('v', '', $release['name']) > $currentVersion) {
                            return [
                                'version' => $release['name'],
                                'url' => $release['html_url'],
                                'published_at' => Carbon::parse($release['published_at'])->toISOString(),
                                'prerelease' => $release['prerelease'],
                            ];
                        }
                        return null;
                    })->filter()->toArray();
                }

                Cache::put('sellexx.dashboard.version.releases', [
                    'currentVersion' => "v$currentVersion",
                    'uninstalledList' => $latestReleases ?? [],
                ], now()->addHours(6));
            }
        }

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
            'xApiKey' => 'YCwWdyr7JNfZM92mh4zQGxDsVFHk6n5aebpEvB8LA3RucKUtPT',
            'releases' => Cache::get('sellexx.dashboard.version.releases')
        ]);
    }
}
