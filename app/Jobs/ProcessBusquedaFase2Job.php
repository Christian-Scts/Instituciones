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

class ProcessBusquedaFase2Job implements ShouldQueue
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

        $coincidencias = $busquedaService->ejecutarHistorica($reporte);

        \Log::info('PUI FASE 2 RESULTADO', [
            'reporte_id' => $reporte->id,
            'empresa_id' => $reporte->empresa_id,
            'curp' => $reporte->curp,
            'id_busqueda' => $reporte->id_busqueda,
            'fecha_desaparicion' => $reporte->fecha_desaparicion,
            'total_coincidencias' => count($coincidencias),
            'coincidencias' => $coincidencias,
        ]);

        if (!empty($coincidencias)) {
            $notificacionService->enviarCoincidencias($reporte, $coincidencias);
        }

        $reporte->update([
            'fase_actual' => '2',
            'estatus' => 'fase_2_completada',
            'busqueda_historica_finalizada_en' => now(),
        ]);

        $notificacionService->notificarFinFase2($reporte);

        ProcessBusquedaFase3Job::dispatch($reporte->id)->delay(now()->addMinutes(1));
        //ProcessBusquedaFase3Job::dispatch($reporte->id)->delay(now()->addHours(4));
    }
}