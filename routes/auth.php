<?php

use Illuminate\Support\Facades\Route;

Route::controller(\Exxtensio\EcommerceDashboard\Http\Controllers\AuthController::class)
    ->middleware('sellexx-data')
    ->group(function () {
        Route::middleware('sellexx-authenticated')->group(function () {
            Route::get('login', 'showLoginForm')->name('showLoginForm');
        });

        Route::get('register', 'showRegisterForm')->name('showRegisterForm');

        Route::post('login', 'login')->name('login');
        Route::post('signup', 'signup')->name('signup');

        Route::post('logout', 'logout')->name('logout');
    });
