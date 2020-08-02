<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'MainController@index')->name('index');

Route::group(['prefix' => 'debug', 'as' => 'debug.'], function () {
    Route::get('/', 'DebugController@index')->name('index');
    Route::get('renderView', 'DebugController@renderView')->name('renderView');
});
