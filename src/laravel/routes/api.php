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

Route::group(['namespace' => 'API\v1', 'prefix' => 'v1', 'middleware' => 'throttle:600', 'as' => 'api.v1.'], function() {
    Route::group(['middleware' => ['guest:api', 'throttle:60,1']], function () {
        Route::post('login', 'AuthController@login')->name('login');
        Route::post('signup', 'AuthController@signup')->name('signup');
        Route::get('status', 'StatusController')->name('status');
    });

    Route::group(['middleware' => 'auth:api'], function() {
        Route::post('logout', 'AuthController@logout')->name('logout');
        Route::post('getUser', 'AuthController@getUser')->name('getUser');
    });

    /**
     * token_scopes:root
     */
    Route::group(['middleware' => ['token']], function() {
    });

    /**
     * Debug & testing controller
     */
    Route::group(['prefix' => 'debug', 'as' => 'debug.', 'middleware' => ['token']], function() {
        Route::match(['GET', 'POST'], '/', 'DebugController@index')->name('index');
    });
});
