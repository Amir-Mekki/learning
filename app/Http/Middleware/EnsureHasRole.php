<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\AuthorizationException;

class EnsureHasRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::guard('api')->check()) {
            throw new AuthorizationException('Unauthorized', 401);
        }

        $clientId = config('keycloak.client_id');

        if (Auth::guard('api')->hasAnyRole($clientId, $roles)) {
            return $next($request);
        }

        throw new AuthorizationException('Forbidden', 403);
    }
}
