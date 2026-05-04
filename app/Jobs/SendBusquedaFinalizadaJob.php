<?php

namespace App\Jobs;

use App\Models\Empresa;
use App\Models\PuiReporte;
use App\Services\PuiClienteService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendBusquedaFinalizadaJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $reporteId)
    {
    }

    public function handle(PuiClienteService $clienteService): void
    {
        $reporte = PuiReporte::with('empresa')->findOrFail($this->reporteId);

        /** @var Empresa|null $empresa */
        $empresa = $reporte->empresa;

        if (!$empresa) {
            Log::error('PUI SendBusquedaFinalizadaJob sin empresa asociada', [
                'reporte_id' => $reporte->id,
            ]);
            return;
        }

        Log::info('PUI SendBusquedaFinalizadaJob START', [
            'reporte_id' => $reporte->id,
            'empresa_id' => $empresa->id,
            'id_busqueda' => $reporte->id_busqueda,
            'curp' => $reporte->curp,
            'url_base_api' => $empresa->url_base_api,
        ]);

        $login = $clienteService->loginPui($empresa);

        Log::info('PUI SendBusquedaFinalizadaJob LOGIN', [
            'reporte_id' => $reporte->id,
            'status' => $login->status(),
            'body' => $login->json() ?: $login->body(),
        ]);

        if (!$login->successful()) {
            Log::warning('PUI SendBusquedaFinalizadaJob LOGIN FAILED', [
                'reporte_id' => $reporte->id,
                'empresa_id' => $empresa->id,
                'status' => $login->status(),
                'body' => $login->json() ?: $login->body(),
            ]);

            return;
        }

        $token = $login->json('token');
        $url = rtrim($empresa->url_base_api, '/') . '/busqueda-finalizada';

        $payload = [
            'id' => $reporte->id_busqueda,
            'curp' => $reporte->curp,
        ];

        Log::info('PUI SendBusquedaFinalizadaJob REQUEST', [
            'reporte_id' => $reporte->id,
            'url' => $url,
            'payload' => $payload,
        ]);

        $response = $clienteService->busquedaFinalizada($url, $token, $payload);

        Log::info('PUI SendBusquedaFinalizadaJob RESPONSE', [
            'reporte_id' => $reporte->id,
            'status' => $response->status(),
            'body' => $response->json() ?: $response->body(),
        ]);
    }
}