<?php

use \Illuminate\Support\Facades\Route;
use \RaadaaPartners\RaadaaBase\Http\Controllers\ResourceController;

if (config('raadaa.set_routes')) {
    $prefix = config('endpoints.prefix')? config('endpoints.prefix') : 'general';

    Route::group(['as' => 'raadaa.'], function () use($prefix){
        //resource controller routes
        Route::as('resources.')->prefix($prefix)->group(function () {
            Route::get('{endpoint}', [ResourceController::class, 'index'])->name('index');
            Route::post('{endpoint}', [ResourceController::class, 'store'])->name('store');
            Route::get('{endpoint}/{id}', [ResourceController::class, 'show'])->name('show');
            Route::match(['POST', 'PATCH', 'PUT'],'{endpoint}/{id}', [ResourceController::class, 'update'])->name('update');
            Route::delete('{endpoint}/{id}', [ResourceController::class, 'destroy'])->name('delete');
        });
    });
}
