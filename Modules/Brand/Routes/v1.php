<?php

use Illuminate\Support\Facades\Route;
use Modules\Brand\Http\Controllers\BrandController;

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
        Route::prefix('/brands')->controller(BrandController::class)->group(function () {
            Route::post('/', 'index');
            Route::post('/create', 'store')->name('Create Brand');
            Route::post('/dropdown', 'dropdown');
            Route::get('/single/{brandId}', 'show');
            Route::patch('/update/{brandId}', 'update')->name('Update Brand');
            Route::delete('/delete/{brandId}', 'delete')->name('Delete Brand');
        });
    });
});

