<?php

namespace App\Jobs;

use App\Models\PuiReporte;
use App\Services\PuiBusquedaService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessBusquedaFase3Job implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $reporteId)
    {
    }

    public function handle(PuiBusquedaService $busquedaService): void
    {
        $reporte = PuiReporte::findOrFail($this->reporteId);

        if ($reporte->estatus === 'desactivado') {
            return;
        }

        $coincidencias = $busquedaService->ejecutarContinua($reporte);

        foreach ($coincidencias as $payload) {
            SendCoincidenciaJob::dispatch($reporte->id, '3', $payload);
        }

        $reporte->update([
            'estatus' => 'monitoreo_continuo',
            'ultima_busqueda_continua_en' => now(),
        ]);

        self::dispatch($reporte->id)->delay(now()->addHours(4));
    }
}