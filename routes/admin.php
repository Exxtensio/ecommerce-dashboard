<?php

use Illuminate\Support\Facades\Route;

Route::controller(\Exxtensio\EcommerceDashboard\Http\Controllers\HomeController::class)
    ->group(function () {
        Route::get('', 'index')->name('home.index');
        Route::post('dashboard', 'create')->name('home.create');
        Route::delete('dashboard/{id}', 'delete')->name('home.delete');
        Route::post('dashboard/range', 'updateRange')->name('home.updateRange');
        Route::post('dashboard/lang/{value}', 'updateLang')->name('home.updateLang');
        Route::post('dashboard/update', 'update')->name('home.update');
    });

app('dashboard')->resources()->map(function ($resource) {
    Route::controller($resource::$controller)
        ->prefix($resource::$prefix)
        ->name($resource::$prefix . '.')
        ->group(function () use ($resource) {
            collect([
                ['method' => 'get', 'uri' => '', 'action' => 'index', 'name' => 'index'], // INDEX PAGE
                $resource::$canCreate ? ['method' => 'get', 'uri' => 'create', 'action' => 'create', 'name' => 'create'] : null, // CREATE PAGE
                $resource::$canPreview ? ['method' => 'get', 'uri' => '{id}', 'action' => 'show', 'name' => 'show'] : null, // SHOW PAGE
                $resource::$canEdit ? ['method' => 'get', 'uri' => '{id}/edit', 'action' => 'edit', 'name' => 'edit'] : null, // EDIT PAGE

                ['method' => 'post', 'uri' => 's', 'action' => 'search', 'name' => 'search'], // SEARCH
                ['method' => 'post', 'uri' => 'c', 'action' => 'setCache', 'name' => 'setCache'], // CACHE
                ['method' => 'post', 'uri' => 'r', 'action' => 'removeLocked', 'name' => 'removeLocked'], // REMOVE LOCKED

                ['method' => 'post', 'uri' => 'action/delete', 'action' => 'actionDelete', 'name' => 'actionDelete'], // ACTION DELETE
                ['method' => 'post', 'uri' => 'action/restore', 'action' => 'actionRestore', 'name' => 'actionRestore'], // ACTION RESTORE
                ['method' => 'post', 'uri' => 'action/force', 'action' => 'actionForce', 'name' => 'actionForce'], // ACTION FORCE

                $resource::$canCreate ? ['method' => 'post', 'uri' => '', 'action' => 'store', 'name' => 'store'] : null, // STORE
                $resource::$canEdit ? ['method' => 'post', 'uri' => '{id}', 'action' => 'update', 'name' => 'update'] : null, // UPDATE
                $resource::$canDelete ? ['method' => 'delete', 'uri' => '{id}', 'action' => 'delete', 'name' => 'delete'] : null, // DELETE
                ['method' => 'put', 'uri' => '{id}/restore', 'action' => 'restore', 'name' => 'restore'], // RESTORE
                ['method' => 'delete', 'uri' => '{id}/force', 'action' => 'force', 'name' => 'force'], // FORCE

            ])->filter()->values()->map(function ($i) use ($resource) {
                Route::{$i['method']}($i['uri'], $i['action'])->name($i['name']);
                if($resource::$prefix === 'brands' || $resource::$prefix === 'categories')
                    Route::post('action/delete-image', 'deleteImage')->name('deleteImage'); // ACTION DELETE IMAGE

                if($resource::$prefix === 'brands' || $resource::$prefix === 'categories' || $resource::$prefix === 'products' || $resource::$prefix === 'attributes')
                    Route::post('action/delete-translation', 'deleteTranslation')->name('deleteTranslation'); // ACTION DELETE TRANSLATION
            });
        });
});
