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
        
        $response = $next($request);

        // Ajout des headers pour empêcher la mise en cache (Sécurité Logout)
        return $response->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
                        ->header('Pragma', 'no-cache')
                        ->header('Expires', 'Sat, 01 Jan 1990 00:00:00 GMT');
    }
}
