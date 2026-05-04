<?php

use App\Http\Middleware\ForceJsonUtf8;
use App\Http\Middleware\RateLimitPuiRequests;
use App\Http\Middleware\SecureHeaders;
use App\Http\Middleware\ValidatePuiBearer;
use App\Http\Middleware\ValidatePuiIpWhitelist;
use App\Http\Middleware\ValidateMethodOverride;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(SecureHeaders::class);
        $middleware->alias([
            'pui.bearer' => ValidatePuiBearer::class,
            'force.json.utf8' => ForceJsonUtf8::class,
            'pui.ip' => ValidatePuiIpWhitelist::class,
            'pui.rate' => RateLimitPuiRequests::class,
            'method.override.safe' =>ValidateMethodOverride::class,
            'admin.auth' => \App\Http\Middleware\AdminAuth::class,
            'permission' => \App\Http\Middleware\CheckPermission::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();