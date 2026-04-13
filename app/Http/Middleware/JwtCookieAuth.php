<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JwtCookieAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->hasCookie('jwt_token')) {
            return redirect()->route('login');
        }

        try {
            $token = $request->cookie('jwt_token');
            auth('api')->setToken($token)->authenticate();
        } catch (\Exception $e) {
            return redirect()->route('login');
        }
        return $next($request);
    }
}
