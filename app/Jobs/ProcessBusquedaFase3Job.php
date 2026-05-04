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

class ProcessBusquedaFase3Job implements ShouldQueue
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

        try {
            $coincidencias = $busquedaService->ejecutarContinua($reporte);

            if (!empty($coincidencias)) {
                $notificacionService->enviarCoincidencias($reporte, $coincidencias);
            }

            $reporte->update([
                'estatus' => 'monitoreo_continuo',
                'fase_actual' => '3',
                'ultima_busqueda_continua_en' => now(),
            ]);

            self::dispatch($reporte->id)->delay(now()->addHours(4));
        } catch (\Throwable $e) {
            \Log::error('PUI FASE 3 ERROR', [
                'reporte_id' => $reporte->id ?? null,
                'empresa_id' => $reporte->empresa_id ?? null,
                'error' => $e->getMessage(),
            ]);
        }

        /*$coincidencias = $busquedaService->ejecutarContinua($reporte);
        $notificacionService->enviarCoincidencias($reporte, $coincidencias);

        $reporte->update([
            'estatus' => 'monitoreo_continuo',
            'fase_actual' => '3',
            'ultima_busqueda_continua_en' => now(),
        ]);

        self::dispatch($reporte->id)->delay(now()->addMinutes(5));
        self::dispatch($reporte->id)->delay(now()->addHours(4));*/
        //ProcessBusquedaFase3Job::dispatch($reporte->id)->delay(now()->addMinutes(1));
    }
}