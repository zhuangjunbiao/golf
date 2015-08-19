<?php

namespace App\Http\Middleware;

use Closure;

class ApiKeyMiddleware
{
    /**
     * 无需验证的路由
     *
     * @var array
     */
    protected $except = [
        'test',
        'test/api',
        'test/key',
        'config/init'
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // 过滤
        if ($this->shouldPassThrough($request))
        {
            return $next($request);
        }

        // 未知客户端
        if (!$this->verifyClient($request))
        {
            return golf_error(2003);
        }

        // 时间校验
        if (!$this->verifyTime($request))
        {
            return golf_error(1005);
        }

        // 验证key
        if (!$this->verifyKey($request))
        {
            return golf_error(1003);
        }

        return $next($request);
    }

    /**
     * 验证
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    private function verifyKey($request)
    {
        // 分隔符
        $separate = env('API_SEPARATE');

        // 私钥
        $private_key = config('app.key');

        $params = $request->except('key');
        $key = $request->input('key');

        $str = '';
        foreach ($params as $k => $v)
        {
            $str .= $k . $separate . $v;
        }
        $str .= $private_key;

        return md5($str) == $key;
    }

    /**
     * Determine if the request has a URI that should pass through CSRF verification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    private function shouldPassThrough($request)
    {
        foreach ($this->except as $except)
        {
            if ($request->is($except))
            {
                return true;
            }
        }

        return false;
    }

    /**
     * 验证客户端
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    private function verifyClient($request)
    {
        $client = $request->input('client');
        foreach (config('global.clients') as $c)
        {
            if (strtoupper($client) == strtoupper($c))
            {
                return true;
            }
        }

        return false;
    }

    /**
     * 验证时间戳
     *
     * @param  \Illuminate\Http\Request $request
     * @return bool
     */
    private function verifyTime($request)
    {
        $ts = $request->input('ts');
        if (abs(REQUEST_TIME - $ts) > 60)
        {
            return false;
        }

        return true;
    }
}
