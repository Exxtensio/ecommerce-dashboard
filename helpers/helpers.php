<?php

use Exxtensio\EcommerceDashboard\ActivityLogger;
use Exxtensio\EcommerceDashboard\ActivityLogStatus;

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
