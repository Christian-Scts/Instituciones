<?php

namespace App\Services;

use App\Models\Empresa;
use App\Models\PuiCoincidencia;
use App\Models\PuiReporte;
use Illuminate\Support\Facades\Log;

class PuiNotificacionService
{
    public function enviarCoincidencias(PuiReporte $reporte, array $coincidencias): void
    {
        if (empty($coincidencias)) {
            Log::info('PUI enviarCoincidencias sin resultados', [
                'reporte_id' => $reporte->id,
                'empresa_id' => $reporte->empresa_id,
                'id_busqueda' => $reporte->id_busqueda,
                'curp' => $reporte->curp,
            ]);
            return;
        }

        $empresa = $reporte->empresa;
        $cliente = app(PuiClienteService::class);

        $login = $cliente->loginPui($empresa);

        Log::info('PUI LOGIN PARA NOTIFICAR COINCIDENCIAS', [
            'reporte_id' => $reporte->id,
            'empresa_id' => $empresa->id,
            'id_busqueda' => $reporte->id_busqueda,
            'login_status' => $login->status(),
            'login_body' => $login->json() ?: $login->body(),
        ]);

        if (!$login->successful()) {
            Log::warning('PUI LOGIN FALLIDO, NO SE GUARDAN COINCIDENCIAS', [
                'reporte_id' => $reporte->id,
                'empresa_id' => $empresa->id,
                'id_busqueda' => $reporte->id_busqueda,
            ]);
            return;
        }

        $token = $login->json('token');
        $url = rtrim($empresa->url_base_api, '/') . '/notificar-coincidencia';

        foreach ($coincidencias as $payload) {
            Log::info('PUI NOTIFICANDO COINCIDENCIA', [
                'reporte_id' => $reporte->id,
                'empresa_id' => $empresa->id,
                'id_busqueda' => $reporte->id_busqueda,
                'payload' => $payload,
                'url' => $url,
            ]);

            $response = $cliente->notificarCoincidencia($url, $token, $payload);

            Log::info('PUI RESPUESTA NOTIFICAR COINCIDENCIA', [
                'reporte_id' => $reporte->id,
                'empresa_id' => $empresa->id,
                'id_busqueda' => $reporte->id_busqueda,
                'http_code' => $response->status(),
                'body' => $response->json() ?: $response->body(),
            ]);

            $yaExiste = PuiCoincidencia::where('empresa_id', $empresa->id)
            ->where('id_busqueda', $reporte->id_busqueda)
            ->where('curp', $payload['curp'] ?? $reporte->curp)
            ->where('fase_busqueda', $payload['fase_busqueda'] ?? null)
            ->where('payload_enviado', json_encode($payload))
            ->exists();

        if ($yaExiste) {
            continue;
        }

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

        Log::info('PUI LOGIN PARA BUSQUEDA FINALIZADA', [
            'reporte_id' => $reporte->id,
            'empresa_id' => $empresa->id,
            'id_busqueda' => $reporte->id_busqueda,
            'login_status' => $login->status(),
            'login_body' => $login->json() ?: $login->body(),
        ]);

        if (!$login->successful()) {
            Log::warning('PUI LOGIN FALLIDO, NO SE NOTIFICA BUSQUEDA FINALIZADA', [
                'reporte_id' => $reporte->id,
                'empresa_id' => $empresa->id,
                'id_busqueda' => $reporte->id_busqueda,
            ]);
            return;
        }

        $token = $login->json('token');
        $url = rtrim($empresa->url_base_api, '/') . '/busqueda-finalizada';

        $response = $cliente->busquedaFinalizada($url, $token, [
            'id' => $reporte->id_busqueda,
            'institucion_id' => $empresa->rfc,
        ]);

        Log::info('PUI RESPUESTA BUSQUEDA FINALIZADA', [
            'reporte_id' => $reporte->id,
            'empresa_id' => $empresa->id,
            'id_busqueda' => $reporte->id_busqueda,
            'http_code' => $response->status(),
            'body' => $response->json() ?: $response->body(),
        ]);
    }

    public function sincronizarReportesActivos(Empresa $empresa): array
    {
        $cliente = app(PuiClienteService::class);

        $login = $cliente->loginPui($empresa);

        if (!$login->successful()) {
            Log::warning('PUI LOGIN FALLIDO EN SINCRONIZAR REPORTES', [
                'empresa_id' => $empresa->id,
                'rfc' => $empresa->rfc,
            ]);
            return [];
        }

        $token = $login->json('token');
        $url = rtrim($empresa->url_base_api, '/') . '/reportes';

        $response = $cliente->reportesActivos($url, $token);

        Log::info('PUI RESPUESTA SINCRONIZAR REPORTES', [
            'empresa_id' => $empresa->id,
            'http_code' => $response->status(),
            'body' => $response->json() ?: $response->body(),
        ]);

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