<?php

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;
use \App\Models\Faker;

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

    Route::group(['middleware' => ['guest:api', 'throttle:60,1']], function () {
        Route::group(['prefix' => 'auth'], function () {
            Route::post('login', 'Auth\LoginController@login');
            Route::post('register', 'Auth\RegisterController@register');

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

        Route::apiResource('roles', 'RoleController')
//             ->middleware('permission:' . \App\Models\Permission::PERMISSION_PERMISSION_MANAGE)
        ;
        Route::apiResource('users', 'UserController')
//             ->middleware('permission:' . \App\Models\Permission::PERMISSION_USER_MANAGE)
        ;
        Route::apiResource('permissions', 'PermissionController')
//             ->middleware('permission:' . \App\Models\Permission::PERMISSION_PERMISSION_MANAGE)
        ;

        Route::put('users/{user}', 'UserController@update');
        Route::get('users/{user}/permissions', 'UserController@permissions')
//             ->middleware('permission:' . \App\Models\Permission::PERMISSION_PERMISSION_MANAGE)
        ;
        Route::put('users/{user}/permissions', 'UserController@updatePermissions')
//             ->middleware('permission:' .\App\Models\Permission::PERMISSION_PERMISSION_MANAGE)
        ;
        Route::get('roles/{role}/permissions', 'RoleController@permissions')
//             ->middleware('permission:' . \App\Models\Permission::PERMISSION_PERMISSION_MANAGE)
        ;
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


    // Fake APIs
    Route::get('/table/list', function () {
        $rowsNumber = mt_rand(20, 30);
        $data = [];
        for ($rowIndex = 0; $rowIndex < $rowsNumber; $rowIndex++) {
            $row = [
                'author' => Faker::randomString(mt_rand(5, 10)),
                'display_time' => Faker::randomDateTime()->format('Y-m-d H:i:s'),
                'id' => mt_rand(100000, 100000000),
                'pageviews' => mt_rand(100, 10000),
                'status' => Faker::randomInArray(['deleted', 'published', 'draft']),
                'title' => Faker::randomString(mt_rand(20, 50)),
            ];

            $data[] = $row;
        }

        return api()->ok('', [], ['items' => $data]);
    });

    Route::get('/orders', function () {
        $rowsNumber = 8;
        $data = [];
        for ($rowIndex = 0; $rowIndex < $rowsNumber; $rowIndex++) {
            $row = [
                'order_no' => 'LARAVUE' . mt_rand(1000000, 9999999),
                'price' => mt_rand(10000, 999999),
                'status' => Faker::randomInArray(['success', 'pending']),
            ];

            $data[] = $row;
        }

        return api()->ok('',  [], ['items' => $data]);
    });

    Route::get('/articles', function () {
        $rowsNumber = 10;
        $data = [];
        for ($rowIndex = 0; $rowIndex < $rowsNumber; $rowIndex++) {
            $row = [
                'id' => mt_rand(100, 10000),
                'display_time' => Faker::randomDateTime()->format('Y-m-d H:i:s'),
                'title' => Faker::randomString(mt_rand(20, 50)),
                'author' => Faker::randomString(mt_rand(5, 10)),
                'comment_disabled' => Faker::randomBoolean(),
                'content' => Faker::randomString(mt_rand(100, 300)),
                'content_short' => Faker::randomString(mt_rand(30, 50)),
                'status' => Faker::randomInArray(['deleted', 'published', 'draft']),
                'forecast' => mt_rand(100, 9999) / 100,
                'image_uri' => 'https://via.placeholder.com/400x300',
                'importance' => mt_rand(1, 3),
                'pageviews' => mt_rand(10000, 999999),
                'reviewer' => Faker::randomString(mt_rand(5, 10)),
                'timestamp' => Faker::randomDateTime()->getTimestamp(),
                'type' => Faker::randomInArray(['US', 'VI', 'JA']),

            ];

            $data[] = $row;
        }

        return api()->ok('', [], ['items' => $data, 'total' => mt_rand(1000, 10000)]);
    });

    Route::get('articles/{id}', function ($id) {
        $article = [
            'id' => $id,
            'display_time' => Faker::randomDateTime()->format('Y-m-d H:i:s'),
            'title' => Faker::randomString(mt_rand(20, 50)),
            'author' => Faker::randomString(mt_rand(5, 10)),
            'comment_disabled' => Faker::randomBoolean(),
            'content' => Faker::randomString(mt_rand(100, 300)),
            'content_short' => Faker::randomString(mt_rand(30, 50)),
            'status' => Faker::randomInArray(['deleted', 'published', 'draft']),
            'forecast' => mt_rand(100, 9999) / 100,
            'image_uri' => 'https://via.placeholder.com/400x300',
            'importance' => mt_rand(1, 3),
            'pageviews' => mt_rand(10000, 999999),
            'reviewer' => Faker::randomString(mt_rand(5, 10)),
            'timestamp' => Faker::randomDateTime()->getTimestamp(),
            'type' => Faker::randomInArray(['US', 'VI', 'JA']),

        ];

        return api()->ok('', [], $article);
    });

    Route::get('articles/{id}/pageviews', function ($id) {
        $pageviews = [
            'PC' => mt_rand(10000, 999999),
            'Mobile' => mt_rand(10000, 999999),
            'iOS' => mt_rand(10000, 999999),
            'android' => mt_rand(10000, 999999),
        ];
        $data = [];
        foreach ($pageviews as $device => $pageview) {
            $data[] = [
                'key' => $device,
                'pv' => $pageview,
            ];
        }

        return api()->ok('', [], ['pvData' => $data]);
    });

});
