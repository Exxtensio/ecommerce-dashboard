<?php

namespace Exxtensio\EcommerceDashboard\Providers\Includes;

use Illuminate\Support\ServiceProvider;
use Exxtensio\EcommerceDashboard\ActivityLogger;
use Exxtensio\EcommerceDashboard\ActivityLogStatus;
use Exxtensio\EcommerceDashboard\CauserResolver;
use Exxtensio\EcommerceDashboard\Console;
use Exxtensio\EcommerceDashboard\LogBatch;
use Exxtensio\EcommerceDashboard\Models\Activity;
use Exxtensio\EcommerceDashboard\Contracts\Activity as ActivityContract;

class ActivityLogServiceProvider extends ServiceProvider
{
    public function boot(): void {}

    public function register(): void {
        $this->app->bind(ActivityLogger::class);
        $this->app->scoped(LogBatch::class);
        $this->app->scoped(CauserResolver::class);
        $this->app->scoped(ActivityLogStatus::class);
    }

    public static function getActivityModelInstance(): ActivityContract
    {
        $activityModelClassName = Activity::class;

        return new $activityModelClassName();
    }
}
