<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'prefix'     => 'v1',
], function () {
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::prefix('/users')->controller(UserController::class)->group(function () {
            Route::post('/', 'index')->middleware('can:admin.users.index');
            Route::post('/create', 'store')->name('Create User')->middleware('can:admin.users.create');
            Route::patch('/update/{userId}', 'update')->name('Update User')->middleware('can:admin.users.edit');
            Route::delete('/delete/{userId}', 'delete')->name('Delete User')->middleware('can:admin.users.destroy');
        });
    });
});

