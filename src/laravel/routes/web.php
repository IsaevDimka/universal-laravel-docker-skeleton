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

Route::group(['prefix' => 'debug', 'as' => 'debug.'], function () {
    Route::get('/', [App\Http\Controllers\DebugController::class , 'index'])->name('index')->middleware('env:local|develop');
    Route::get('renderView', [App\Http\Controllers\DebugController::class, 'renderView'])->name('renderView');
});