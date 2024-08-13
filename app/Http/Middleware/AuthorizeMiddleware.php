<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorizeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $queryParameters = $request->all();

        info('query', $request->all());

        if (key_exists('access', $queryParameters)
            && $queryParameters['access'] === 'yes') {
            return $next($request);
        }
        
        return abort(403);
    }
}
