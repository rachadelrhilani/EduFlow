<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EncryptCookies
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    protected $except = [
        'jwt_token', // On ajoute le nom de ton cookie ici
    ];
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }
}
