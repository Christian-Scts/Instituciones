<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ForceJsonUtf8
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->method() !== 'GET' && !$request->isJson()) {
            return response()->json([
                'error' => 'Content-Type debe ser application/json'
            ], 415);
        }

        return $next($request);
    }
}
