<?php

namespace Mukja\Posty\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

        if (! Hash::check($request->bearerToken(), config('posty.hashed_api_token'))) {
            return response()->json("Unauthorized, Token can't be validated.", 401);
        }

        return $next($request);
    }
}
