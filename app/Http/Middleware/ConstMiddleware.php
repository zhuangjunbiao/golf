<?php

namespace App\Http\Middleware;

use Closure;
use DB;

class ConstMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (config('app.debug'))
        {
            DB::enableQueryLog();
        }

        define('REQUEST_TIME', $request->server('REQUEST_TIME'));

        return $next($request);
    }
}
