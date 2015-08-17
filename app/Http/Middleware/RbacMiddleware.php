<?php

namespace App\Http\Middleware;

use App\Services\OAuth;
use Closure;

class RbacMiddleware
{

    /**
     * @var OAuth
     */
    private $auth;

    public function __construct(OAuth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if ($this->auth->isLogin())
        {
            // 已登录且访问默认网关
            if ($this->auth->isDefaultGateway($request))
            {
                // 重定向到首页
                return $this->auth->defaultHome($request);
            }

            // 验证访问权限
            if (!$this->auth->permissions($request))
            {
                return $this->auth->deny($request);
            }
        }
        else
        {
            // 未登录且不是访问默认网关
            if (!$this->auth->isDefaultGateway($request))
            {
                return $this->auth->defaultGateway($request);
            }
        }

        return $next($request);
    }
}
