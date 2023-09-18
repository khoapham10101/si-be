<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::prefix('v1')->group(function () {
//     Route::post('login', 'AuthController@login')->name('login');
//     Route::middleware(['auth:sanctum'])->group(function () {
//         Route::post('logout', 'AuthController@logout');
//     });
// });

Route::group([
    'prefix'     => 'v1',
], function () {
    Route::post('login', 'AuthController@login')->name('login');
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('logout', 'AuthController@logout');
    });
});
