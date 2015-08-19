<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\OAuth;
use Illuminate\Http\Request;
use Lang;

class AuthController extends Controller
{

    public function __construct()
    {
        // 游客模式能访问的接口
        $this->middleware('guest', ['only' => [
            'getForgetPassword',
            'postForgetPassword',
            'postSms'
        ]]);
    }
    
    /**
     * 登录
     *
     * @return \Illuminate\View\View
     */
    public function getLogin()
    {
        return view('admin.auth.login');
    }

    /**
     * 登录处理
     *
     * @param Request $request
     * @param OAuth $auth
     * @return array|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function postLogin(Request $request, OAuth $auth)
    {
        if ($auth->login($request))
        {
            return $auth->defaultHome($request);
        }
        else
        {
            // 用户名或密码错误
            return view('admin.auth.login', ['error' => Lang::get('admin.auth.login_failed')]);
        }
    }

    /**
     * 退出登录
     *
     * @param Request $request
     * @param OAuth $auth
     * @return array|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function getLogout(Request $request, OAuth $auth)
    {
        return $auth->logout()->defaultGateway($request);
    }

    /**
     * 忘记密码
     *
     * @return \Illuminate\View\View
     */
    public function getForgetPassword()
    {
        return view('admin.auth.forget_password');
    }

    public function postForgetPassword(Request $request, OAuth $auth)
    {
    }

    public function postSms(Request $request, OAuth $auth)
    {
        if ($auth->sendSMS($request))
        {
            return ajax_return($auth->getSMSResidueTime($request), 1, '');
        }
        else
        {
            return ajax_error(0, '发送失败');
        }
    }
}
