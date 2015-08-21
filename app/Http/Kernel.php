<?php

namespace App\Http;

use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        CheckForMaintenanceMode::class,
        Middleware\EncryptCookies::class,
        AddQueuedCookiesToResponse::class,
        StartSession::class,
        ShareErrorsFromSession::class,
        Middleware\ConstMiddleware::class
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth'      => Middleware\Authenticate::class,
        'guest'     => Middleware\RedirectIfAuthenticated::class,
        'csrf'      => Middleware\VerifyCsrfToken::class,
        'api.key'   => Middleware\ApiKeyMiddleware::class,
        'rbac'      => Middleware\RbacMiddleware::class,
    ];
}
