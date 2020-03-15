<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::namespace('Api')->group(function () {
    Route::post('login', 'Auth\JwtController@login')->name('login');

    Route::middleware('jwt.auth')->group(function () {
        Route::ApiResource('areas', 'AreasController');
        Route::ApiResource('entities', 'EntitiesController');
        Route::ApiResource('kinds', 'KindsController');
        Route::ApiResource('personas', 'PersonasController');

        Route::post('logout', 'Auth\JwtController@logout')->name('logout');
        Route::post('refresh', 'Auth\JwtController@refresh')->name('fresh');
        Route::post('me', 'Auth\JwtController@me')->name('me');
    });
});

Route::fallback(function () {
    return response()->json([
        'message' => 'Page Not Found. If error persists, contact suport'
    ], 404);
});
