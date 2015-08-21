<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// API组
Route::group([
        'domain' => env('API_DOMAIN'),
        'middleware' => 'api.key'
    ], function() {

        // API调试工具
        Route::controller('test', 'Test\ApiController');

        // 版本1
        Route::group(['prefix' => 'v1'], function() {
            // 控制器组
            Route::controllers([
                'config'    => 'Api1\ConfigController',
                'user'      => 'Api1\UserController'
            ]);
        });
    }
);

// 后台组
Route::group([
        'domain'        => env('ADMIN_DOMAIN'),
        'middleware'    => ['csrf', 'rbac']
    ], function() {
        Route::get('jump', 'Admin\Controller@getJump');

        // 控制器组
        Route::controllers([
            'auth'      => 'Admin\AuthController',
            '/'         => 'Admin\IndexController'
        ]);
    }
);