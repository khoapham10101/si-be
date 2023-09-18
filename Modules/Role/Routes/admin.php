<?php

use Illuminate\Support\Facades\Route;
use Modules\Role\Http\Controllers\RoleController;

Route::group([
    'prefix'     => 'v1',
], function () {
    Route::group(['middleware' => ['auth:sanctum',]], function () {
        Route::prefix('/roles')->controller(RoleController::class)->group(function () {
            Route::post('/', [
                'as' => 'List Roles',
                'uses' => 'index',
                'middleware' => 'can:admin.roles.index',
            ]);

            Route::post('/create', [
                'as' => 'Create Role',
                'uses' => 'store',
                'middleware' => 'can:admin.roles.create',
            ]);

            Route::patch('/update/{roleId}', [
                'as' => 'Update Role',
                'uses' => 'update',
                'middleware' => 'can:admin.roles.edit',
            ]);

            Route::get('/single/{roleId}', [
                'as' => 'Detail Role',
                'uses' => 'show',
                'middleware' => 'can:admin.roles.index',
            ]);

            Route::delete('/delete/{roleId}', [
                'as' => 'Delete Role',
                'uses' => 'destroy',
                'middleware' => 'can:admin.roles.destroy',
            ]);

        });
    });
});
