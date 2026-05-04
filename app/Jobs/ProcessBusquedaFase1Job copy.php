<?php

namespace App\Jobs;

use App\Models\PuiReporte;
use App\Services\PuiBusquedaService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessBusquedaFase1Job implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $reporteId)
    {
    }

    public function handle(PuiBusquedaService $busquedaService): void
    {
        $reporte = PuiReporte::findOrFail($this->reporteId);

        $resultado = $busquedaService->prepararFase1($reporte);

        if (!empty($resultado['coincidencia'])) {
            SendCoincidenciaJob::dispatch($reporte->id, '1', $resultado['payload']);
        }

        $reporte->update([
            'fase_actual' => '2',
            'estatus' => 'fase_1_completada',
        ]);

        ProcessBusquedaFase2Job::dispatch($reporte->id);
    }
}