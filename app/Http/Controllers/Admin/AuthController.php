<?php

namespace App\Http\Controllers\Admin;

use App\Services\OAuth;
use App\Validation\CustomValidator;
use Illuminate\Http\Request;
use Lang;
use Session;

class AuthController extends Controller
{

    public function __construct()
    {
        // 游客模式能访问的接口(登录状态不可访问)
        $this->middleware('guest', ['only' => [
            'getForgetPassword',
            'postForgetPassword',
            'postSms'
        ]]);

        // 登录状态才能访问的接口
        $this->middleware('auth', ['only' => [
            'getModifyPassword',
            'postModifyPassword'
        ]]);

        // 添加自定义验证规则
        \Validator::resolver(function($translator, $data, $rules, $messages)
        {
            return new CustomValidator($translator, $data, $rules, $messages);
        });
    }

    /**
     * 修改密码
     *
     * @return \Illuminate\View\View
     */
    public function getModifyPassword()
    {
        return view('admin.auth.modify_password');
    }

    /**
     * 修改密码逻辑
     *
     * @param Request $request
     * @param OAuth $auth
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postModifyPassword(Request $request, OAuth $auth)
    {
        $this->validate($request, [
            'sms_code'      => ['required', "sms_code:{$request->input('phone')},"],
            'phone'         => ['required', 'oauth_phone', 'user_deny'],
            'now_password'  => ['required', 'user_password'],
            'password'      => ['required', 'length:6,18', 'confirmed']
        ]);

        try
        {
            $phone = $auth->getUser()->getAttribute('phone');
            $password = $request->input('password');
            $auth->setPassword($phone, $password);
            $auth->logout();

            // 重置成功
            return url_jump(Lang::get('admin.auth.reset.success'), url('/auth/login'), 1);
        }
        catch (\Exception $e)
        {
            // 重置失败
            return redirect()->back()->withErrors([Lang::get('admin.auth.reset.failed')]);
        }
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
        if (Session::get('forget_password') == Session::getId() && Session::has('phone'))
        {
            return redirect(url('/auth/set-password'));
        }

        return view('admin.auth.forget_password');
    }

    /**
     * 忘记密码逻辑处理
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postForgetPassword(Request $request)
    {
        if (Session::get('forget_password') == Session::getId() && Session::has('phone'))
        {
            return redirect(url('/auth/set-password'));
        }

        $this->validate($request, [
            'sms_code'  => ['required', "sms_code:{$request->input('phone')},{$request->input('device')}"],
            'phone'     => ['required', 'exists:users,phone', 'user_deny']
        ]);

        Session::put('forget_password', Session::getId());
        Session::put('phone', $request->input('phone'));
        return redirect(url('/auth/set-password'));
    }

    /**
     * 重置密码
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function getSetPassword()
    {
        if (!(Session::get('forget_password') == Session::getId() && Session::has('phone')))
        {
            return redirect(url('/auth/forget-password'));
        }

        return view('admin.auth.set_password');
    }

    /**
     * 重置密码逻辑
     *
     * @param Request $request
     * @param OAuth $auth
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function postSetPassword(Request $request, OAuth $auth)
    {
        if (!(Session::get('forget_password') == Session::getId() && Session::has('phone')))
        {
            return redirect(url('/auth/forget-password'));
        }

        $this->validate($request, [
            'password'      => ['required', 'length:6,18', 'confirmed']
        ]);

        $phone = Session::get('phone');
        $password = $request->input('password');


        try
        {
            $auth->setPassword($phone, $password);
            Session::forget('phone');
            Session::forget('forget_password');

            // 重置成功
            return url_jump(Lang::get('admin.auth.reset.success'), url('/auth/login'), 1);
        }
        catch (\Exception $e)
        {
            if (config('app.debug'))
            {
                throw $e;
            }

            // 重置失败
            return redirect()->back()->withErrors([Lang::get('admin.auth.reset.failed')]);
        }
    }

    /**
     * 发送验证码
     *
     * @param Request $request
     * @param OAuth $auth
     * @return array
     * @throws \Exception
     */
    public function getSmsCode(Request $request, OAuth $auth)
    {
        $this->ajaxValidate($request, [
            'phone'     => ['required', 'phone', 'exists:users,phone', 'user_deny']
        ], 0);

        try
        {
            if ($auth->sendVerifySMS($request))
            {
                return ajax_return($auth->getSMSResidueTime($request));
            }
            else
            {
                // 距离下次获取短信还有second秒
                $second = $auth->getSMSResidueTime($request);
                return ajax_return($second, -3, Lang::get('validation.sms.expiration_time', [
                    'second'   => $second
                ]));
            }
        }
        catch (\Exception $e)
        {
            if (config('app.debug'))
            {
                throw $e;
            }

            // 短信发送失败
            return ajax_error(0, Lang::get('admin.auth.sms_send_failed'));
        }
    }
}
