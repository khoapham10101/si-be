<?php

use Illuminate\Http\Request;
use Modules\Cart\Http\Controllers\CartController;

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
        Route::prefix('/carts')->controller(CartController::class)->group(function () {
            Route::post('/', 'index');
            Route::post('/create', 'addToCart')->name('add Cart');
            Route::put('/update-mutiple', 'updateMutiple')->name('Update Cart');
            Route::delete('/delete/{cartId}', 'delete')->name('Delete Cart');
        });
    });
});

