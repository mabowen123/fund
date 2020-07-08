<?php

namespace App\Http\Middleware;

use Closure;

class RequestAcceptJson
{
    public function handle($request, Closure $next)
    {
        if ($request->header('Accept') != 'application/json') {
            $request->headers->set('Accept', 'application/json');
        }

        return $next($request);
    }
}
