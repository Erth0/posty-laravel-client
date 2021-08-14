<?php

namespace Mukja\Posty\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthenticatePostyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! $request->bearerToken()) {
            return response()->json('Authorization token is missing.', 401);
        }

        if (! in_array($request->bearerToken(), config('posty.api_keys'))) {
            return response()->json("Unauthorized, Token can't be validated.", 401);
        }

        return $next($request);
    }
}
