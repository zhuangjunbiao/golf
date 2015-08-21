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

/*
 * 注：不要在路由中写闭包，否则权限验证可能会失效
 */

// 通用测试
if (config('app.debug'))
{
    Route::resource('test', 'TestController');
}

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
});

// 后台组
Route::group([
    'domain'        => env('ADMIN_DOMAIN'),
    'middleware'    => ['csrf', 'rbac']
], function() {

    // RESTful 控制器组
    Route::resources([
        'user'      => 'Admin\UserManagerController',
        'app'       => 'Admin\AppManagerController',
        'options'   => 'Admin\OptionsManagerController',
        'node'      => 'Admin\NodeManagerController',
    ]);

    // 控制器组
    Route::controllers([
        'auth'      => 'Admin\AuthController',
        '/'         => 'Admin\IndexController'
    ]);
});