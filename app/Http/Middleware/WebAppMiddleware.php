<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class WebAppMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->header('X-client') === 'web') {
            return $next($request);
        }

        return response('Unauthorized.', 401);
    }
}
