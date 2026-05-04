<?php

namespace App\Jobs;

use App\Models\Empresa;
use App\Models\PuiCoincidencia;
use App\Models\PuiReporte;
use App\Services\PuiClienteService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendCoincidenciaJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $reporteId,
        public string $faseBusqueda,
        public array $payload
    ) {
    }

    public function handle(PuiClienteService $clienteService): void
    {
        $reporte = PuiReporte::with('empresa')->findOrFail($this->reporteId);

        /** @var Empresa|null $empresa */
        $empresa = $reporte->empresa;

        if (!$empresa) {
            Log::error('PUI SendCoincidenciaJob sin empresa asociada', [
                'reporte_id' => $reporte->id,
            ]);
            return;
        }

        $login = $clienteService->loginPui($empresa);

        if (!$login->successful()) {
            Log::warning('PUI SendCoincidenciaJob LOGIN FAILED', [
                'reporte_id' => $reporte->id,
                'empresa_id' => $empresa->id,
                'status' => $login->status(),
                'body' => $login->json() ?: $login->body(),
            ]);
            return;
        }

        $token = $login->json('token');
        $url = rtrim(config('services.pui.remote_base_url'), '/') . '/notificar-coincidencia';

        $response = $clienteService->notificarCoincidencia($url, $token, $this->payload);

        PuiCoincidencia::create([
            'empresa_id' => $empresa->id,
            'pui_reporte_id' => $reporte->id,
            'id_busqueda' => $reporte->id_busqueda,
            'curp' => $reporte->curp,
            'fase_busqueda' => $this->payload['fase_busqueda'] ?? null,
            'nombre_completo' => $this->payload['nombre_completo'] ?? null,
            'domicilio' => $this->payload['domicilio'] ?? null,
            'evento' => [
                'tipo_evento' => $this->payload['tipo_evento'] ?? null,
                'fecha_evento' => $this->payload['fecha_evento'] ?? null,
                'descripcion_lugar_evento' => $this->payload['descripcion_lugar_evento'] ?? null,
                'direccion_evento' => $this->payload['direccion_evento'] ?? null,
            ],
            'payload_enviado' => $this->payload,
            'respuesta_pui' => $response->json() ?: ['raw' => $response->body()],
            'http_code' => $response->status(),
            'exitosa' => $response->successful(),
            'notificado_en' => now(),
        ]);
    }
}