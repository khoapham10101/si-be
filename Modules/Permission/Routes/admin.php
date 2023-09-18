<?php

use Illuminate\Support\Facades\Route;
use Modules\Permission\Http\Controllers\PermissionController;

Route::group([
    'prefix'     => 'v1',
], function () {
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::prefix('/permissions')->controller(PermissionController::class)->group(function () {
            Route::post('/', 'index');
        });
    });
});
