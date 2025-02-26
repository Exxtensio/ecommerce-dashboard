<?php

use Exxtensio\EcommerceDashboard\ActivityLogger;
use Exxtensio\EcommerceDashboard\ActivityLogStatus;
use Illuminate\Support\Facades\Cache;

if (!function_exists('getModelForGuard')) {
    /**
     * @param string $guard
     * @return string|null
     */
    function getModelForGuard(string $guard): ?string
    {
        return collect(config('auth.guards'))
            ->map(fn($guard) => isset(
                $guard['provider'])
                ? config("auth.providers.{$guard['provider']}.model")
                : null
            )->get($guard);
    }
}

if (!function_exists('activity')) {
    function activity(string $logName = null): ActivityLogger
    {
        $logStatus = app(ActivityLogStatus::class);

        return app(ActivityLogger::class)
            ->useLog($logName ?? 'default')
            ->setLogStatus($logStatus);
    }
}

if (!function_exists('normalizePath')) {
    function normalizePath(string $path): string
    {
        $path = preg_replace('#/{2,}#', '/', $path);
        return ltrim($path, '/');
    }
}

if (!function_exists('getBroadcastingClass')) {
    function getBroadcastingClass($model): string
    {
        return str_replace("\\", ".", is_string($model) ? $model : get_class($model));
    }
}

if (!function_exists('isLocked')) {
    function isLocked($model, $id, $user): bool
    {
        $getClass = getBroadcastingClass($model);
        return Cache::has("$getClass:locked:$id") && Cache::get("$getClass:locked:$id") !== $user->id;
    }
}

if (!function_exists('tryBroadcast')) {
    function tryBroadcast($new): void
    {
        try {
            broadcast($new);
        } catch (Exception $e) {}
    }
}

if (!function_exists('websocketOptions')) {
    function websocketOptions(): array
    {
        return [
            'host' => config('dashboard.websocket.host'),
            'port' => config('dashboard.websocket.port'),
            'scheme' => config('dashboard.websocket.scheme'),
        ];
    }
}
