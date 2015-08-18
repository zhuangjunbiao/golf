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

// 二级域名
$domain = env('DOMAIN');

// API组
Route::group(['domain' => "api.{$domain}", 'middleware' => 'api.key'], function() {

    // API调试工具
    Route::controller('test', 'Test\ApiController');

    // 版本1
    Route::group(['prefix' => 'v1'], function() {
        // 控制器组
        Route::controllers([
            'user'     => 'Api1\UserController'
        ]);
    });
});

// 后台组
Route::group([
        'domain'        => "golf.{$domain}",
        'middleware'    => ['csrf', 'rbac']
    ], function() {
        // 控制器组
        Route::controllers([
            'auth'      => 'Admin\AuthController',
            '/'         => 'Admin\IndexController'
        ]);
    }
);