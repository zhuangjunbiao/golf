<?php namespace App\Services;

use App\Library\Console;
use App\Library\SMS;
use App\Models\RoleNode;
use App\Models\Users;
use Session;
use Cache;
use Lang;

class OAuth {

    /**
     * 无需验证的路由
     *
     * @var array
     */
    protected $except = [
        'auth/forget-password',
        'auth/sms-code',
        'auth/set-password',
        'auth/modify-password'
    ];

    /**
     * 默认网关
     *
     * @var string
     */
    private $default_gateway = 'auth/login';

    /**
     * 默认首页
     *
     * @var string
     */
    private $default_home = '/';

    /**
     * @var Users
     */
    private $user;

    /**
     * 用户session key
     *
     * @var string
     */
    private $userSession = 'user';

    /**
     * 重复获取短信时间(秒)
     *
     * @var int
     */
    private $sms_gain_time = 60;

    /**
     * 构造方法
     */
    function __construct()
    {
        $this->user = Session::get($this->userSession);
        $this->default_gateway = config('global.rbac.default_gateway');
        $this->sms_gain_time = config('global.sms_gain_time') * 60;
        array_push($this->except, $this->default_gateway);
    }


    /**
     * 是否登录
     * @return bool
     */
    public function isLogin()
    {
        return !empty($this->user);
    }

    /**
     * 默认网关地址
     *
     * @param  \Illuminate\Http\Request $request
     * @return array|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function defaultGateway($request)
    {
        $url = url($this->default_gateway);
        if ($request->ajax())
        {
            return ajax_error(-1, null, $url);
        }
        else
        {
            return redirect($url);
        }
    }

    /**
     * 返回默认首页
     *
     * @param  \Illuminate\Http\Request $request
     * @return array|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function defaultHome($request)
    {
        if ($request->ajax())
        {
            return ajax_return(null, 1, null, $this->default_home);
        }
        else
        {
            return redirect($this->default_home);
        }
    }

    /**
     * 获取排除名单
     * @return array
     */
    public function getExcept()
    {
        return $this->except;
    }

    /**
     * 验证权限
     *
     * @param  \Illuminate\Http\Request $request
     * @return bool
     */
    public function permissions($request)
    {
        $nodes = array_merge($this->nodes(), $this->getExcept());
        foreach ($nodes as $node)
        {
            if ($request->is($node) || $node == '*')
            {
                return true;
            }
        }

        return false;
    }

    /**
     * 禁止访问
     * @param  \Illuminate\Http\Request $request
     * @return array|\Illuminate\View\View
     */
    public function deny($request)
    {
        if ($request->ajax())
        {
            return ajax_error(-2, null);
        }
        else
        {
            return view('errors.404');
        }
    }

    /**
     * 可访问节点
     * @return array
     */
    private function nodes()
    {
        $rid = empty($this->user) ? null : $this->user->getAttribute('rid');
        $key = 'rbac_role_node';
        $cache = Cache::get($key);
        if (empty($cache) || !isset($cache[$rid]))
        {
            $nodes = RoleNode::model()->getRoleNodes($rid);
            if (empty($nodes))
            {
                return [];
            }

            $tmp = array();
            if ($nodes[0]['nid'] == '*')
            {
                array_push($tmp, '*');
            }
            else
            {
                foreach ($nodes as $node)
                {
                    array_push($tmp, $node['nname']);
                }

            }

            $cache[$rid] = $tmp;
            Cache::put($key, $cache, 24*60);
        }

        return $cache[$rid];
    }

    /**
     * 是否访问默认网关
     * @param  \Illuminate\Http\Request $request
     * @return bool
     */
    public function isDefaultGateway($request)
    {
        return $request->path() == $this->default_gateway;
    }

    /**
     * 登录
     *
     * @param  \Illuminate\Http\Request $request
     * @return bool
     */
    public function login($request)
    {
        $phone = $request->input('phone');
        $password = $request->input('password');
        $user = Users::model()->getUserInfo($phone);

        // 用户不存在
        if (empty($user))
        {
            return false;
        }

        // 密码错误
        if ($user->getAttribute('password') != $this->password($password))
        {
            return false;
        }

        // 更新登录信息
        $user->setAttribute('login_time', REQUEST_TIME);
        $user->save();
        $this->user = $user;

        // 写入session
        Session::put($this->userSession, $user);

        return true;
    }

    /**
     * 退出登录
     *
     * @return $this
     */
    public function logout()
    {
        Session::forget($this->userSession);
        return $this;
    }

    /**
     * 验证短信验证码
     *
     * @param $phone
     * @param $code
     * @param $device
     * @return bool
     */
    public static function verifySMSCode($phone, $code, $device)
    {
        $sms_verify = 'sms_verify';
        $d_key = 'sms_record_device';
        $p_key = 'sms_record_phone';
        $cache = Cache::get($sms_verify);
        $key = md5($phone);

        if (empty($phone) || empty($code) || !isset($cache[$key]))
        {
            return false;
        }

        $verify = $cache[$key];
        $outtime = config('global.sms_out_time') * 60;

        // 删除验证码缓存
        unset($cache[$key]);
        Cache::forever($sms_verify, $cache);

        // 删除设备号缓存
        $dCache = Cache::has($d_key) ? (array) Cache::get($d_key) : array();
        unset($dCache[md5($device)]);
        Cache::forever($d_key, $dCache);

        // 删除手机号缓存
        $pCache = Cache::has($p_key) ? (array) Cache::get($p_key) : array();
        unset($pCache[md5($phone)]);
        Cache::forever($p_key, $pCache);

        return ($code == $verify['code']) && ($verify['start'] + $outtime > REQUEST_TIME);
    }

    /**
     * 发送验证短信
     *
     * @param  \Illuminate\Http\Request $request
     * @return bool
     * @throws \Exception
     */
    public function sendVerifySMS($request)
    {
        // 距离下次获取大于3秒，则不给获取
        if ($this->getSMSResidueTime($request) > 3)
        {
            return false;
        }

        // TODO 一小时内只能发10条信息

        $phone = $request->input('phone');
        $device = $request->input('device');

        // device针对客户端，如果为空，则使用session id
        if (empty($device))
        {
            $device = $request->getSession()->getId();
        }

        $code = rand_str(5);
        $content = Lang::get('global.sms_verify_code', ['code' => $code, 'minute' => config('global.sms_out_time')]);

        // 调试模式下只打印短信，不发送信息
        if (config('app.debug'))
        {
            Console::log("{$phone} ： {$code}");
        }

        SMS::send($phone, $content);

        // 写入缓存
        $d_key = 'sms_record_device';
        $p_key = 'sms_record_phone';
        $sms_verify = 'sms_verify';
        $time = time();// 这里使用time()更精准

        // 验证码写入缓存
        $sCache = Cache::has($sms_verify) ? (array) Cache::get($sms_verify) : array();
        $sCache[md5($phone)] = array(
            'start' => $time,
            'code'  => $code
        );
        Cache::forever($sms_verify, $sCache);

        // 设备号写入缓存
        $dCache = Cache::has($d_key) ? (array) Cache::get($d_key) : array();
        $dCache[md5($device)] = $time;
        Cache::forever($d_key, $dCache);

        // 手机号写入缓存
        $pCache = Cache::has($p_key) ? (array) Cache::get($p_key) : array();
        $pCache[md5($phone)] = $time;
        Cache::forever($p_key, $pCache);

        return true;
    }

    /**
     * 获取短信的剩余时间（秒）
     *
     * @param  \Illuminate\Http\Request $request
     * @return int
     */
    public function getSMSResidueTime($request)
    {
        // 调试模式下，剩余时间为0
        if (config('app.debug') && $request->has('debug'))
        {
            return 0;
        }

        // 同部设备或同个手机号
        $phone = md5($request->input('phone'));
        $device = $request->input('device');

        // device针对客户端，如果为空，则使用session id
        if (empty($device))
        {
            $device = $request->getSession()->getId();
        }

        $device = md5($device);
        $d_key = 'sms_record_device';
        $p_key = 'sms_record_phone';
        $d_time = 0;
        $p_time = 0;

        // 设备剩余时间
        if (isset(Cache::get($d_key)[$device]))
        {
            $d_time = $this->sms_gain_time - (REQUEST_TIME - Cache::get($d_key)[$device]);
            $d_time = $d_time > $this->sms_gain_time ? $this->sms_gain_time : $d_time;
        }

        // 手机剩余时间
        if (isset(Cache::get($p_key)[$phone]))
        {
            $p_time = $this->sms_gain_time - (REQUEST_TIME - Cache::get($p_key)[$phone]);
            $p_time = $p_time > $this->sms_gain_time ? $this->sms_gain_time : $p_time;
        }

        if ($d_time == 0 && $p_time == 0)
        {
            return 0;
        }
        else
        {
            return $d_time > $p_time ? $d_time : $p_time;
        }
    }

    /**
     * 用户注册
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function register($request)
    {
        $attributes = $request->only(['password', 'user_name', 'phone']);
        $attributes['password'] = $this->password($attributes['password']);
        $attributes['form'] = fix_apps($request->input('client'));
        $attributes['create_time'] = REQUEST_TIME;
        $user = Users::create($attributes)->toArray();
        return $user;
    }

    /**
     * 密码生成
     *
     * @param $password
     * @return string
     */
    public static function password($password)
    {
        return md5(config('global.password_prefix') . $password);
    }

    /**
     * 获取用户
     *
     * @return Users|null
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * 修改密码
     *
     * @param  \Illuminate\Http\Request $request
     * @return bool
     */
    public function resetPassword($request)
    {
        $phone = $request->input('phone');
        $password = $this->password($request->input('password'));
        return Users::where('phone', '=', $phone)->update(['password' => $password]);
    }

    /**
     * 设置密码
     *
     * @param $phone
     * @param $password
     * @return bool|int
     */
    public function setPassword($phone, $password)
    {
        return Users::where('phone', '=', $phone)->update([
            'password'  => $this->password($password)
        ]);
    }
}
