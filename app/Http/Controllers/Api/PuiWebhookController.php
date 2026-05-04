<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ActivarReporteRequest;
use App\Http\Requests\DesactivarReporteRequest;
use App\Jobs\ProcessBusquedaFase1Job;
use App\Jobs\ProcessBusquedaFase2Job;
use App\Models\PuiReporte;
use App\Services\PuiLogService;

class PuiWebhookController extends Controller
{
    public function __construct(
        protected PuiLogService $logService
    ) {
    }

    public function activarReporte(ActivarReporteRequest $request)
    {
        $empresa = $request->attributes->get('empresa');

        if (!$empresa) {
            return response()->json([
                'error' => 'Empresa no resuelta desde el token',
            ], 401);
        }

        $reporte = PuiReporte::updateOrCreate(
            [
                'empresa_id' => $empresa->id,
                'id_busqueda' => $request->id,
            ],
            [
                'curp' => $request->curp,
                'fecha_desaparicion' => $request->fecha_desaparicion,
                'carpeta_investigacion' => $request->carpeta_investigacion,
                'fase_actual' => '1',
                'estatus' => 'activo',
                'es_prueba' => false,
                'payload_original' => $request->validated(),
                'alta_en' => now(),
                'baja_en' => null,
            ]
        );

        ProcessBusquedaFase1Job::dispatch($reporte->id);
        ProcessBusquedaFase2Job::dispatch($reporte->id)->delay(now()->addMinutes(1));

        return response()->json([
            'mensaje' => 'Webhook recibido correctamente',
            'id_busqueda' => $reporte->id_busqueda,
            'empresa_id' => $empresa->id,
        ]);
    }

    public function activarReportePrueba(ActivarReporteRequest $request)
    {
        $empresa = $request->attributes->get('empresa');

        if (!$empresa) {
            return response()->json([
                'error' => 'Empresa no resuelta desde el token',
            ], 401);
        }

        $reporte = PuiReporte::updateOrCreate(
            [
                'empresa_id' => $empresa->id,
                'id_busqueda' => $request->id,
            ],
            [
                'curp' => $request->curp,
                'fecha_desaparicion' => $request->fecha_desaparicion,
                'carpeta_investigacion' => $request->carpeta_investigacion,
                'fase_actual' => '1',
                'estatus' => 'activo_prueba',
                'es_prueba' => true,
                'payload_original' => $request->validated(),
                'alta_en' => now(),
                'baja_en' => null,
            ]
        );

        ProcessBusquedaFase1Job::dispatch($reporte->id);
        ProcessBusquedaFase2Job::dispatch($reporte->id)->delay(now()->addMinutes(1));

        return response()->json([
            'mensaje' => 'Prueba de webhook recibida correctamente',
            'id_busqueda' => $reporte->id_busqueda,
            'empresa_id' => $empresa->id,
        ]);
    }

    public function desactivarReporte(DesactivarReporteRequest $request)
    {
        $empresa = $request->attributes->get('empresa');

        if (!$empresa) {
            return response()->json([
                'error' => 'Empresa no resuelta desde el token',
            ], 401);
        }

        $reporte = PuiReporte::where('empresa_id', $empresa->id)
            ->where('id_busqueda', $request->id)
            ->first();

        if (!$reporte) {
            return response()->json([
                'error' => 'Reporte no encontrado para esta empresa',
                'id_busqueda' => $request->id,
            ], 404);
        }

        $reporte->update([
            'estatus' => 'desactivado',
            'baja_en' => now(),
        ]);

        return response()->json([
            'mensaje' => 'Reporte desactivado correctamente',
            'id_busqueda' => $reporte->id_busqueda,
            'empresa_id' => $empresa->id,
        ]);
    }
}