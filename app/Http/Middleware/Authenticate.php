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
        if (!$this->auth->isLogin())
        {
            return $this->auth->defaultGateway($request);
        }

        return $next($request);
    }
}
