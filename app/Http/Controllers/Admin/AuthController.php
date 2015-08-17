<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\OAuth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * 登录
     * @return \Illuminate\View\View
     */
    public function getLogin()
    {
        return view('admin.auth.login');
    }

    public function postLogin(Request $request, OAuth $auth)
    {
        if ($auth->login($request))
        {

        }
        else
        {

        }
    }
}
