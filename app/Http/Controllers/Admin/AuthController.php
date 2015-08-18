<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\OAuth;
use Illuminate\Http\Request;
use Lang;

class AuthController extends Controller
{
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
     * 重置密码
     *
     * @return \Illuminate\View\View
     */
    public function getResetPassword()
    {
        return view('admin.auth.reset_password');
    }

    public function postResetPassword(Request $request, OAuth $auth)
    {
        dd($request->all());
    }
}
