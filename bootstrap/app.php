<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\KeycloakAuthenticated;
use KeycloakGuard\KeycloakGuardServiceProvider;
// use KeycloakGuard\Middleware\KeycloakAuthenticated;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(KeycloakAuthenticated::class);
    })
    // ->withProviders([
    //     KeycloakGuardServiceProvider::class,
    // ])
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
