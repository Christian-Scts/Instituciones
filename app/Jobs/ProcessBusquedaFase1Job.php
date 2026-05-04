<?php

namespace App\Jobs;

use App\Models\PuiReporte;
use App\Services\PuiBusquedaService;
use App\Services\PuiNotificacionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessBusquedaFase1Job implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $reporteId)
    {
    }

    public function handle(
        PuiBusquedaService $busquedaService,
        PuiNotificacionService $notificacionService
    ): void {
        $reporte = PuiReporte::with('empresa')->find($this->reporteId);

        if (!$reporte || $reporte->estatus === 'desactivado' || $reporte->baja_en) {
            return;
        }

        $coincidencias = $busquedaService->prepararFase1($reporte);

        \Log::info('PUI FASE 1 RESULTADO', [
            'reporte_id' => $reporte->id,
            'empresa_id' => $reporte->empresa_id,
            'curp' => $reporte->curp,
            'id_busqueda' => $reporte->id_busqueda,
            'total_coincidencias' => count($coincidencias),
            'coincidencias' => $coincidencias,
        ]);

        if (!empty($coincidencias)) {
            $notificacionService->enviarCoincidencias($reporte, $coincidencias);
        }

        $reporte->update([
            'fase_actual' => '1',
            'estatus' => $reporte->es_prueba ? 'activo_prueba' : 'activo',
        ]);
    }
}