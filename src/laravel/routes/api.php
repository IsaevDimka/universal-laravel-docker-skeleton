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
Route::group(['prefix' => 'v1', 'as' => 'api.v1.'], function() {
    Route::get('localization/{locale}', App\Http\Controllers\API\v1\LocalizationController::class)->where(['locale' => '[a-zA-Z]{2}'])->name('localization');
    Route::get('status', App\Http\Controllers\API\v1\StatusController::class)->name('status');
    Route::any('webhook', [App\Http\Controllers\API\v1\WebhookController::class, 'any'])->name('webhook.any');
    Route::get('webhook/test', [App\Http\Controllers\API\v1\WebhookController::class, 'test'])->name('webhook.test');

    Route::apiResource('timezones', App\Http\Controllers\API\v1\TimezoneController::class)->only(['index']);
    Route::apiResource('countries', App\Http\Controllers\API\v1\CountryController::class)->only(['index']);
    Route::apiResource('storages', App\Http\Controllers\API\v1\StorageController::class)->only(['index', 'show', 'store']);
    Route::apiResource('news', App\Http\Controllers\API\v1\NewsController::class)->only(['index', 'show']);
    Route::apiResource('feedback', App\Http\Controllers\API\v1\FeedbackController::class)->only(['store']);
    Route::apiResource('storages', App\Http\Controllers\API\v1\StorageController::class)->only(['index', 'show', 'store']);

    Route::group(['middleware' => ['guest:api', 'throttle:60,1']], function () {
        Route::group(['prefix' => 'auth'], function () {
            Route::post('login', [App\Http\Controllers\API\v1\Auth\LoginController::class, 'login']);
            Route::post('register', App\Http\Controllers\API\v1\Auth\RegisterController::class);

            Route::post('password/email', [App\Http\Controllers\API\v1\Auth\ForgotPasswordController::class, 'sendResetLinkEmail']);
            Route::post('password/reset', [App\Http\Controllers\API\v1\Auth\ResetPasswordController::class, 'reset']);

            Route::post('email/verify/{user}', [App\Http\Controllers\API\v1\Auth\VerificationController::class, 'verify'])->name('verification.verify');
            Route::post('email/resend', [App\Http\Controllers\API\v1\Auth\VerificationController::class, 'resend']);
        });

        Route::post('oauth/{driver}', [App\Http\Controllers\API\v1\Auth\OAuthController::class, 'redirectToProvider']);
        Route::get('oauth/{driver}/callback', [App\Http\Controllers\API\v1\Auth\OAuthController::class, 'handleProviderCallback'])->name('oauth.callback');
    });

    Route::group(['middleware' => 'auth:api'], function() {
        Route::group(['prefix' => 'auth'], function () {
            Route::get('user', [App\Http\Controllers\API\v1\Auth\UserController::class, 'current']);
            Route::post('logout', [App\Http\Controllers\API\v1\Auth\LoginController::class, 'logout']);
            Route::apiResource('loginActivities', App\Http\Controllers\API\v1\Auth\UserLoginActivityController::class)->only(['index']);
        });
        //        Route::patch('settings/profile', 'Settings\ProfileController@update');
//        Route::patch('settings/password', 'Settings\PasswordController@update');
        Route::group(['middleware' => 'role:root|admin'], function() {
            Route::apiResource('roles', App\Http\Controllers\API\v1\RoleController::class);
            Route::apiResource('users', App\Http\Controllers\API\v1\UserController::class);
            Route::apiResource('permissions', App\Http\Controllers\API\v1\PermissionController::class);
            Route::put('users/{user}', [App\Http\Controllers\API\v1\UserController::class, 'update']);
            Route::get('users/{user}/permissions', [App\Http\Controllers\API\v1\UserController::class, 'permissions']);
            Route::put('users/{user}/permissions', [App\Http\Controllers\API\v1\UserController::class, 'updatePermissions']);
            Route::get('roles/{role}/permissions', [App\Http\Controllers\API\v1\RoleController::class, 'permissions']);
        });

        Route::apiResource('currencies', App\Http\Controllers\API\v1\CurrencyController::class)->only(['index', 'show']);
        Route::get('currencies/getByIsoCode/{iso_code}', [App\Http\Controllers\API\v1\CurrencyController::class, 'getByIsoCode'])->name('currencies.getByIsoCode');
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
        Route::match(['GET', 'POST'], '/', [App\Http\Controllers\API\v1\DebugController::class, 'index'])->name('index');
    });

    Route::group(['prefix' => 'telegram', 'as' => 'telegram.'], function(){
        Route::any('webhook', [App\Http\Controllers\API\v1\TelegramWebhookController::class, 'webhook'])->name('webhook');
        Route::get('webhook/get', [App\Http\Controllers\API\v1\TelegramWebhookController::class, 'get'])->name('webhook.get');
    });
});
