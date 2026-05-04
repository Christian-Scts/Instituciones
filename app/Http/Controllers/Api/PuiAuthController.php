<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginPuiRequest;
use App\Services\PuiAuthService;
use App\Services\PuiLogService;

class PuiAuthController extends Controller
{
    public function __construct(
        protected PuiAuthService $authService,
        protected PuiLogService $logService
    ) {
    }

    public function login(LoginPuiRequest $request)
    {
        $inicio = microtime(true);
        $empresa = null;
        $response = [];
        $code = 200;
        $error = null;

        try {
            $empresa = $this->authService->autenticarInstitucion(
                $request->institucion_id,
                $request->clave
            );

            if (!$empresa) {
                $response = ['error' => 'Credenciales inv谩lidas'];
                $code = 403;
                return response()->json($response, $code);
            }

            $token = $this->authService->generarToken($empresa);
            $response = ['token' => $token];

            return response()->json($response);
        } catch (\Throwable $e) {
            $response = ['error' => 'Error procesando petici贸n'];
            $code = 500;
            $error = $e->getMessage();

            return response()->json($response, $code);
        } finally {
            $this->logService->guardar(
                empresa: $empresa,
                request: $request,
                endpoint: '/api/pui/login',
                response: $response,
                httpCode: $code,
                error: $error,
                inicio: $inicio
            );
        }
    }
}