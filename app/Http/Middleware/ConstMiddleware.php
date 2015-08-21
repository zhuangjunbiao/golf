<?php namespace App\Http\Middleware;

use App\Services\OAuth;
use Closure;
use DB;
use View;

class ConstMiddleware
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // 开启查询日志
        if (config('app.debug'))
        {
            DB::enableQueryLog();
        }

        // 请求时间
        define('REQUEST_TIME', $request->server('REQUEST_TIME'));

        // 用户节点
        View::share('_USES', $this->auth->getUses());

        return $next($request);
    }
}
