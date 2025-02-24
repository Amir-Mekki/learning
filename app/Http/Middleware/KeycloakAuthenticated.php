<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class KeycloakAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('web')->check() && !in_array($request->path(), ['/', 'login', 'callback'])) {
            // dump(Auth::guard('keycloak')->check());
            // dump(Auth::guard('web')->check());
            // dd(in_array($request->path(), ['/', 'login', 'callback']));
            return redirect()->route('login');
        }
        
        return $next($request);
    }
}
