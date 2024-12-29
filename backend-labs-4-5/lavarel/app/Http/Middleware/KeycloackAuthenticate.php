<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;

class KeycloakAuthenticate
{
    public function handle($request, Closure $next)
    {
        if (!$request->bearerToken()) {
            throw new AuthenticationException('No token provided');
        }

        if (!auth()->guard('keycloak')->user()) {
            throw new AuthenticationException('Invalid token');
        }

        return $next($request);
    }
}