<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateMethodOverride
{
    public function handle(Request $request, Closure $next): Response
    {
        $spoofed = $request->input('_method');

        if ($spoofed !== null) {
            $allowed = ['PUT', 'PATCH', 'DELETE'];

            if (!is_string($spoofed) || !in_array(strtoupper(trim($spoofed)), $allowed, true)) {
                abort(405, 'Método no permitido');
            }
        }

        return $next($request);
    }
}