<?php

namespace App\Http\Controllers\Api1;

use App\Services\OAuth;
use App\Validation\CustomValidator;
use Illuminate\Http\Request;
use Lang;

/**
 * 用户相关操作
 *
 * Class UserController
 * @package App\Http\Controllers\Api1
 */
class UserController extends Controller
{

    public function __construct()
    {
        // 添加自定义验证规则
        \Validator::resolver(function($translator, $data, $rules, $messages)
        {
            return new CustomValidator($translator, $data, $rules, $messages);
        });
    }

    /**
     * 用户登录
     *
     * 请求方式：
     *      POST
     *
     * 地址：
     *      SERVER/v1/user/login
     *
     * 参数：
     *      phone-手机号，必需
     *      password-密码，必需
     *
     * 自定义错误码：
     *      3001-密码错误
     *
     * @param Request $request
     * @param OAuth $auth
     * @return array
     */
    public function postLogin(Request $request, OAuth $auth)
    {
        $this->params($request, [
            'phone'     => ['required'],
            'password'  => ['required']
        ]);

        if ($auth->login($request))
        {
            $user = $auth->getUser()->toArray();
            unset($user['rid']);
            unset($user['password']);
            return golf_return($user);
        }
        else
        {
            return golf_error(3001, Lang::get('validation.login_failed'));
        }
    }

    /**
     * 用户注册
     *
     * 请求方式：
     *      POST
     *
     * 地址：
     *      SERVER/v1/user/sms-code
     *
     * 参数：
     *      sms_code-短信验证码，必需
     *      device-设备号，必需
     *      user_name-用户名，必需，不能含有非法字符，4-18个字符
     *      phone-手机号，唯一
     *      password-密码，6-18个字符
     *
     * 自定义错误码：
     *      3001-验证码错误
     *
     * @param Request $request
     * @param OAuth $auth
     * @return array
     * @throws \Exception
     */
    public function postRegister(Request $request, OAuth $auth)
    {

        $this->params($request, [
            'sms_code'  => ['required'],
            'device'    => ['required'],
            'user_name' => ['required', 'uname_deny', 'length:4,18'],
            'phone'     => ['required', 'phone', 'unique:users'],
            'password'  => ['required', 'length:6-18']
        ]);

        // 验证码错误
        if (!$auth->verifySMSCode($request))
        {
            return golf_error(3001, Lang::get('validation.custom.sms_code.error'));
        }

        try
        {
            $user = $auth->register($request);
            unset($user['password']);
            return golf_return($user);
        }
        catch (\Exception $e)
        {
            if (config('app.debug'))
            {
                throw $e;
            }
            return golf_error(1001);
        }
    }

    /**
     * 获取短信验证码
     *
     * 请求方式：
     *      GET
     *
     * 地址：
     *      SERVER/v1/user/sms-code
     *
     * 参数：
     *      phone-手机号，必需
     *      device-设备号，必需，在APP生命周期内唯一即可
     *
     * 自定义错误码：
     *      3001-距离下次获取短信还有second秒
     *
     * @param Request $request
     * @param OAuth $auth
     * @return array
     * @throws \Exception
     */
    public function getSmsCode(Request $request, OAuth $auth)
    {
        // 验证参数
        $this->params($request, [
            'phone'     => ['required', 'phone'],
            'device'    => ['required']
        ]);

        try
        {
            if ($auth->sendVerifySMS($request))
            {
                return golf_return(null);
            }
            else
            {
                // 距离下次获取短信还有second秒
                $second = $auth->getSMSResidueTime($request);
                return golf_return($second, 3001, Lang::get('validation.sms.expiration_time', [
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

            return golf_error(1002);
        }
    }
}
