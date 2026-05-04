<?php

namespace App\Http\Middleware;

use App\Services\PuiAuthService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidatePuiBearer
{
    public function __construct(protected PuiAuthService $authService)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $auth = $request->header('Authorization');

        if (!$auth || !preg_match('/Bearer\s(\S+)/', $auth, $matches)) {
            return response()->json(['error' => 'Token requerido'], 401);
        }

        $jwt = $matches[1];

        try {
            $empresa = app(PuiAuthService::class)->validarToken($jwt);

            $request->attributes->set('empresa', $empresa);

            return $next($request);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Token inválido'
            ], 401);
        }
    }
}