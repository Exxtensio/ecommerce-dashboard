<?php

namespace Exxtensio\EcommerceDashboard;

use Illuminate\Support\Facades\Route;

class RouteRegistration
{
    public function withAuthRoutes(): RouteRegistration
    {
        Route::middleware('web')
            ->name('dashboard.auth.')
            ->group(__DIR__ . '/../routes/auth.php');

        return $this;
    }

    public function withAdminRoutes(): RouteRegistration
    {
        Route::middleware(['web', 'sellexx-data', 'sellexx-auth'])
            ->prefix('admin')
            ->name('dashboard.admin.')
            ->group(__DIR__ . '/../routes/admin.php');

        return $this;
    }
}
