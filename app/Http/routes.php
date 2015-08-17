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
Route::group([
    'domain'        => "api.{$domain}",
    'middleware'    => 'api.key'
    ], function() {
        // 控制器组
        Route::controllers([
            'user'     => 'Api\UserController'
        ]);
    }
);

// 后台组
Route::group(['domain' => "golf.{$domain}"], function() {
    // 控制器组
    Route::controllers([
        '/'         => 'Admin\IndexController'
    ]);
});