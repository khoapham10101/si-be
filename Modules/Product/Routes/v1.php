<?php

use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Controllers\ProductController;
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
        Route::prefix('/products')->controller(ProductController::class)->group(function () {
            Route::post('/', 'index')->middleware('can:admin.products.index');
            Route::post('/create', 'store')->name('Create Product')->middleware('can:admin.products.create');
            Route::post('/dropdown', 'dropdown');
            Route::get('/single/{productId}', 'show');
            Route::post('/update/{productId}', 'update')->name('Update Product')->middleware('can:admin.products.edit');
            Route::delete('/delete/{productId}', 'delete')->name('Delete Product')->middleware('can:admin.products.destroy');
        });
    });
});

