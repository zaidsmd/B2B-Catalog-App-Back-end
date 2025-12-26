<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MobileSourceMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->header('X-Client') === 'mobile') {
            return $next($request);
        }

        return response('Unauthorized.', 401);
    }
}
