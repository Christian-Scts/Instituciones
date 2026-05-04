<?php

namespace App\Jobs;

use App\Models\PuiReporte;
use App\Services\PuiBusquedaService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ProcessBusquedaFase2Job implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $reporteId)
    {
    }

    public function handle(PuiBusquedaService $busquedaService): void
{
    $reporte = PuiReporte::findOrFail($this->reporteId);

    $coincidencias = $busquedaService->ejecutarHistorica($reporte);

    \Log::info('PUI FASE2 coincidencias', [
        'reporte_id' => $reporte->id,
        'coincidencias' => $coincidencias,
        'tipo' => gettype($coincidencias),
    ]);

    foreach ($coincidencias as $payload) {
        \Log::info('PUI FASE2 payload', [
            'payload' => $payload,
            'tipo' => gettype($payload),
        ]);

        SendCoincidenciaJob::dispatch($reporte->id, '2', $payload);
    }

    $reporte->update([
        'fase_actual' => '3',
        'estatus' => 'fase_2_completada',
        'busqueda_historica_finalizada_en' => now(),
    ]);

    SendBusquedaFinalizadaJob::dispatch($reporte->id);
    ProcessBusquedaFase3Job::dispatch($reporte->id)->delay(now()->addHours(4));
    //ProcessBusquedaFase3Job::dispatch($reporte->id);
}
}