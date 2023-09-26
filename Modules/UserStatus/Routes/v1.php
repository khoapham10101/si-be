<?php

use Illuminate\Support\Facades\Route;
use Modules\UserStatus\Http\Controllers\UserStatusController;

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
        Route::prefix('/userStatus')->controller(UserStatusController::class)->group(function () {
            Route::post('/', 'index')->middleware('can:admin.user_statuses.index');
            Route::post('/create', 'store')->name('Create User Status')->middleware('can:admin.user_statuses.create');
            Route::post('/dropdown', 'dropdown');
            Route::get('/single/{userStatusId}', 'show');
            Route::patch('/update/{userStatusId}', 'update')->name('Update User Status')
            ->middleware('can:admin.user_statuses.edit');
            Route::delete('/delete/{userStatusId}', 'delete')->name('Delete User Status')
            ->middleware('can:admin.user_statuses.destroy');
        });
    });
});
