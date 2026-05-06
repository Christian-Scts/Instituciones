<?php

namespace App\Services;

use App\Models\Empresa;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

class PuiClienteService
{
    /*
     * LOGIN LOCAL (API LOCAL)
     */
    public function loginInstitucional(Empresa $empresa)
    {
        $baseUrl = rtrim((string) $empresa->url_base_api, '/');

        if (!$baseUrl) {
            throw new \RuntimeException('url_base_api no configurada');
        }

        if (empty($empresa->endpoint_password_encrypted)) {
            throw new \RuntimeException('endpoint_password_encrypted no configurado');
        }

        $clave = Crypt::decryptString($empresa->endpoint_password_encrypted);

        return Http::acceptJson()
            ->timeout(20)
            ->post($baseUrl . '/login', [
                'institucion_id' => $empresa->rfc,
                'clave' => $clave,
            ]);
    }

    /*
     *  LOGIN PUI EXTERNA (para producción)
     */
    public function loginPuiExterno(Empresa $empresa)
    {
        $baseUrl = rtrim(config('services.pui.remote_base_url'), '/');

        $clave = Crypt::decryptString($empresa->pui_password_encrypted);

        return Http::acceptJson()
            ->timeout(20)
            ->post($baseUrl . '/login', [
                'usuario' => $empresa->pui_user,
                'clave' => $clave,
            ]);
    }

    public function notificarCoincidencia(string $url, string $token, array $payload)
    {
        return Http::acceptJson()
            ->withToken($token)
            ->timeout(20)
            ->post($url, $payload);
    }

    public function busquedaFinalizada(string $url, string $token, array $payload)
    {
        return Http::acceptJson()
            ->withToken($token)
            ->timeout(20)
            ->post($url, $payload);
    }

    public function reportesActivos(string $url, string $token)
    {
        return Http::acceptJson()
            ->withToken($token)
            ->timeout(20)
            ->get($url);
    }
}