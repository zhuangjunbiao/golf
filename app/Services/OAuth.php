<?php
/**
 * Created by PhpStorm.
 * User: Lenbo
 * Date: 2015/8/17
 * Time: 14:56
 */

namespace App\Services;

use App\Models\RoleNode;
use App\Models\Users;
use Session;
use Cache;

class OAuth {

    /**
     * 无需验证的路由
     *
     * @var array
     */
    protected $except = [

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
     * 构造方法
     */
    function __construct()
    {
        $this->user = Session::get($this->userSession);
        $this->default_gateway = config('global.rbac.default_gateway');
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
        foreach ($this->nodes() as $node)
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
        $rid = $this->user->getAttribute('rid');
        $key = 'rbac_role_node';
        $cache = Cache::get($key);
        if (empty($cache) || !isset($cache[$rid]))
        {
            $nodes = RoleNode::model()->getRoleNodes($rid);
            if (empty($nodes))
            {
                return [];
            }
dd($nodes);
            $cache[$rid] = $nodes;
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
        return in_array($request->path(), $this->getExcept());
    }

    /**
     * 登录
     *
     * @param  \Illuminate\Http\Request $request
     * @return bool
     */
    public function login($request)
    {
        $user_name = $request->input('user_name');
        $password = $request->input('password');
        $user = Users::model()->getUserInfo($user_name);

        // 用户不存在
        if (empty($user))
        {
            return false;
        }

        // 密码错误
        if ($user->getAttribute('password') != bcrypt($password))
        {
            return false;
        }

        // 更新登录信息
        dd($user);

        // 写入session
        Session::put($this->userSession, $user);

        return true;
    }
}