<?php

namespace App\Http\Middleware;

use App\Services\OAuth;
use Closure;

class RedirectIfAuthenticated
{
    /**
     * The Guard implementation.
     *
     * @var OAuth
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param OAuth $auth
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
        if ($this->auth->isLogin()) {
            return $this->auth->defaultHome($request);
        }

        return $next($request);
    }
}
