<?php

namespace App\Services;

use App\Models\Empresa;
use App\Models\PuiCoincidencia;
use App\Models\PuiReporte;

class PuiNotificacionService
{
    public function enviarCoincidencias(PuiReporte $reporte, array $coincidencias): void
    {
        if (empty($coincidencias)) {
            return;
        }

        $empresa = $reporte->empresa;
        $cliente = app(PuiClienteService::class);

        $login = $cliente->loginPui($empresa);
        if (!$login->successful()) {
            return;
        }

        $token = $login->json('token');
        $url = config('services.pui.remote_base_url') . '/notificar-coincidencia';

        foreach ($coincidencias as $payload) {
            $response = $cliente->notificarCoincidencia($url, $token, $payload);

            PuiCoincidencia::create([
                'empresa_id' => $empresa->id,
                'pui_reporte_id' => $reporte->id,
                'id_busqueda' => $reporte->id_busqueda,
                'curp' => $payload['curp'] ?? $reporte->curp,
                'fase_busqueda' => $payload['fase_busqueda'] ?? null,
                'nombre_completo' => $payload['nombre_completo'] ?? null,
                'domicilio' => $payload['domicilio'] ?? null,
                'evento' => $this->buildEventoPayload($payload),
                'payload_enviado' => $payload,
                'respuesta_pui' => $response->json() ?: ['raw' => $response->body()],
                'http_code' => $response->status(),
                'exitosa' => $response->successful(),
                'notificado_en' => now(),
            ]);
        }
    }

    public function notificarFinFase2(PuiReporte $reporte): void
    {
        $empresa = $reporte->empresa;
        $cliente = app(PuiClienteService::class);

        $login = $cliente->loginPui($empresa);
        if (!$login->successful()) {
            return;
        }

        $token = $login->json('token');
        $url = config('services.pui.remote_base_url') . '/busqueda-finalizada';

        $cliente->busquedaFinalizada($url, $token, [
            'id' => $reporte->id_busqueda,
            'institucion_id' => $empresa->rfc,
        ]);
    }

    public function sincronizarReportesActivos(Empresa $empresa): array
    {
        $cliente = app(PuiClienteService::class);

        $login = $cliente->loginPui($empresa);
        if (!$login->successful()) {
            return [];
        }

        $token = $login->json('token');
        $url = config('services.pui.remote_base_url') . '/reportes';

        $response = $cliente->reportesActivos($url, $token);

        return $response->json() ?: [];
    }

    private function buildEventoPayload(array $payload): ?array
    {
        $evento = [
            'tipo_evento' => $payload['tipo_evento'] ?? null,
            'fecha_evento' => $payload['fecha_evento'] ?? null,
            'descripcion_lugar_evento' => $payload['descripcion_lugar_evento'] ?? null,
            'direccion_evento' => $payload['direccion_evento'] ?? null,
        ];

        $evento = array_filter($evento, fn ($value) => !is_null($value) && $value !== '');

        return empty($evento) ? null : $evento;
    }
}