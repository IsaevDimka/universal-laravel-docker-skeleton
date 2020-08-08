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

Route::group(['namespace' => 'API\v1', 'prefix' => 'v1', 'as' => 'api.v1.'], function() {

    Route::get('localization/{locale}', 'LocalizationController')->where(['locale' => '[a-zA-Z]{2}'])->name('localization');

    Route::group(['middleware' => ['guest:api', 'throttle:60,1']], function () {
        Route::post('login', 'Auth\LoginController@login');
        Route::post('register', 'Auth\RegisterController@register');

        Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
        Route::post('password/reset', 'Auth\ResetPasswordController@reset');

        Route::post('email/verify/{user}', 'Auth\VerificationController@verify')->name('verification.verify');
        Route::post('email/resend', 'Auth\VerificationController@resend');

        Route::post('oauth/{driver}', 'Auth\OAuthController@redirectToProvider');
        Route::get('oauth/{driver}/callback', 'Auth\OAuthController@handleProviderCallback')->name('oauth.callback');

        Route::get('status', 'StatusController')->name('status');
    });

    Route::group(['middleware' => 'auth:api'], function() {
        Route::post('logout', 'Auth\LoginController@logout');
        Route::get('/user', 'Auth\UserController@current');
        Route::patch('settings/profile', 'Settings\ProfileController@update');
        Route::patch('settings/password', 'Settings\PasswordController@update');
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
