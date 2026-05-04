<?php

use App\Http\Controllers\Api\PuiAuthController;
use App\Http\Controllers\Api\PuiWebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::prefix('pui')
    ->middleware(['force.json.utf8'])
    ->group(function () {
        Route::post('/login', [PuiAuthController::class, 'login'])
            ->middleware(['pui.ip', 'pui.rate']);

        Route::middleware(['pui.bearer', 'pui.ip', 'pui.rate'])->group(function () {
            Route::post('/activar-reporte', [PuiWebhookController::class, 'activarReporte']);
            Route::post('/activar-reporte-prueba', [PuiWebhookController::class, 'activarReportePrueba']);
            Route::post('/desactivar-reporte', [PuiWebhookController::class, 'desactivarReporte']);
        });

        // Simulaci贸n local
        Route::post('/notificar-coincidencia', function (Request $request) {
            Log::info('SIMULACION PUI /notificar-coincidencia', $request->all());

            return response()->json([
                'mensaje' => 'Coincidencia recibida correctamente por PUI simulada',
            ], 200);
        });

        Route::post('/busqueda-finalizada', function (Request $request) {
            Log::info('SIMULACION PUI /busqueda-finalizada', $request->all());

            return response()->json([
                'mensaje' => 'Busqueda finalizada recibida correctamente por PUI simulada',
            ], 200);
        });
    });