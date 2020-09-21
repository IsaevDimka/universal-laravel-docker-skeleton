<?php

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
    Route::get('status', 'StatusController')->name('status');

    Route::get('webhook/test', 'WebhookController@test')->name('webhook.test');

    Route::apiResource('countries', 'CountryController')->only(['index']);
    Route::apiResource('storages', 'StorageController')->only([
        'index', 'show', 'store'
    ]);
    Route::apiResource('news', 'NewsController')->only(['index', 'show']);

    Route::apiResource('feedback', 'FeedbackController')->only(['store']);

    Route::group(['middleware' => ['guest:api', 'throttle:60,1']], function () {
        Route::group(['prefix' => 'auth'], function () {
            Route::post('login', 'Auth\LoginController@login');
            Route::post('register', 'Auth\RegisterController');

            Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
            Route::post('password/reset', 'Auth\ResetPasswordController@reset');

            Route::post('email/verify/{user}', 'Auth\VerificationController@verify')->name('verification.verify');
            Route::post('email/resend', 'Auth\VerificationController@resend');
        });

        Route::post('oauth/{driver}', 'Auth\OAuthController@redirectToProvider');
        Route::get('oauth/{driver}/callback', 'Auth\OAuthController@handleProviderCallback')->name('oauth.callback');
    });

    Route::group(['middleware' => 'auth:api'], function() {
        Route::group(['prefix' => 'auth'], function () {
            Route::get('user', 'Auth\UserController@current');
            Route::post('logout', 'Auth\LoginController@logout');
        });
//        Route::patch('settings/profile', 'Settings\ProfileController@update');
//        Route::patch('settings/password', 'Settings\PasswordController@update');
        Route::group(['middleware' => 'role:root|admin'], function() {
            Route::apiResource('roles', 'RoleController');
            Route::apiResource('users', 'UserController');
            Route::apiResource('permissions', 'PermissionController');
            Route::put('users/{user}', 'UserController@update');
            Route::get('users/{user}/permissions', 'UserController@permissions');
            Route::put('users/{user}/permissions', 'UserController@updatePermissions');
            Route::get('roles/{role}/permissions', 'RoleController@permissions');
        });

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

    Route::group(['prefix' => 'telegram', 'as' => 'telegram.'], function(){
        Route::any('webhook', 'TelegramWebhookController')->name('webhook');
    });
});
