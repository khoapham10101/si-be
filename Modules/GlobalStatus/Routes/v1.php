<?php

use Illuminate\Support\Facades\Route;
use Modules\GlobalStatus\Http\Controllers\GlobalStatusController;

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
        Route::prefix('/globalStatus')->controller(GlobalStatusController::class)->group(function () {
            Route::post('/', 'index');
            Route::post('/dropdown', 'dropdown');
            Route::get('/single/{globalStatusId}', 'show');
        });
    });
});
