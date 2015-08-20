<?php

namespace App\Http\Middleware;

use App\Services\OAuth;
use Closure;

class Authenticate
{
    /**
     * @var OAuth
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  OAuth  $auth
     */
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
        // TODO 验证用户是否登录，没登录不给访问
//        if ($this->auth->guest()) {
//            if ($request->ajax()) {
//                return response('Unauthorized.', 401);
//            } else {
//                return redirect()->guest('auth/login');
//            }
//        }

        return $next($request);
    }
}
