<?php

use \Illuminate\Support\Facades\Route;
use \RaadaaPartners\RaadaaBase\Http\Controllers\ResourceController;

Route::group(['as' => 'raadaa.'], function () {
    //resource controller routes
    Route::as('resources.')->prefix('general')->group(function () {
        Route::post('{endpoint}', [ResourceController::class, 'store'])->name('store');
        Route::get('{endpoint}/{id}', [ResourceController::class, 'show'])->name('show');
        Route::match(['POST', 'PATCH', 'PUT'],'{endpoint}/{id}', [ResourceController::class, 'update'])->name('update');
        Route::delete('{endpoint}/{id}', [ResourceController::class, 'destroy'])->name('delete');
    });
});