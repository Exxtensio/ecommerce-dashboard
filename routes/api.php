<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('v1.')->group(function () {
    Route::controller(\Exxtensio\EcommerceDashboard\Http\Controllers\API\CurrencyController::class)
        ->prefix('currencies')
        ->name('currencies.')
        ->group(function () {
            Route::get('', 'index')->name('index');
            Route::get('{id}', 'show')->name('show');
        });

    Route::controller(\Exxtensio\EcommerceDashboard\Http\Controllers\API\CountryController::class)
        ->prefix('countries')
        ->name('countries.')
        ->group(function () {
            Route::get('', 'index')->name('index');
            Route::get('active', 'activeIndex')->name('activeIndex');
            Route::get('{id}', 'show')->name('show');
        });

    Route::controller(\Exxtensio\EcommerceDashboard\Http\Controllers\API\ProductCategoryController::class)
        ->prefix('categories')
        ->name('categories.')
        ->group(function () {
            Route::get('', 'index')->name('index');
            Route::get('{id}', 'show')->name('show');
        });

    Route::controller(\Exxtensio\EcommerceDashboard\Http\Controllers\API\ProductAttributeController::class)
        ->prefix('attributes')
        ->name('attributes.')
        ->group(function () {
            Route::get('', 'index')->name('index');
            Route::get('{id}', 'show')->name('show');
        });

    Route::controller(\Exxtensio\EcommerceDashboard\Http\Controllers\API\ProductBrandController::class)
        ->prefix('brands')
        ->name('brands.')
        ->group(function () {
            Route::get('', 'index')->name('index');
            Route::get('{id}', 'show')->name('show');
        });

    Route::controller(\Exxtensio\EcommerceDashboard\Http\Controllers\API\ProductController::class)
        ->prefix('products')
        ->name('products.')
        ->group(function () {
            Route::get('', 'index')->name('index');
            Route::get('{id}', 'show')->name('show');
        });

    Route::controller(\Exxtensio\EcommerceDashboard\Http\Controllers\API\UserController::class)
        ->prefix('users')
        ->name('users.')
        ->group(function () {
            Route::get('{id}', 'show')->name('show');
        });

    Route::controller(\Exxtensio\EcommerceDashboard\Http\Controllers\API\CartController::class)
        ->prefix('carts')
        ->name('carts.')
        ->group(function () {
            Route::post('', 'create')->name('create');
        });

    Route::controller(\Exxtensio\EcommerceDashboard\Http\Controllers\API\OrderController::class)
        ->prefix('orders')
        ->name('orders.')
        ->group(function () {
            Route::post('', 'create')->name('create');
            Route::post('{id}', 'update')->name('update');
        });
});
