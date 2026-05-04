<?php

namespace App\Services;

use App\Models\Empresa;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

class PuiClienteService
{
    public function loginPui(Empresa $empresa)
    {
        $baseUrl = rtrim((string) $empresa->url_base_api, '/');

        if ($baseUrl === '') {
            throw new \RuntimeException('La empresa no tiene url_base_api configurada');
        }

        if (empty($empresa->pui_password_encrypted)) {
            throw new \RuntimeException('La empresa no tiene pui_password_encrypted configurado');
        }

        $clave = Crypt::decryptString($empresa->pui_password_encrypted);

        return Http::acceptJson()
            ->timeout(20)
            ->post($baseUrl . '/login', [
                'institucion_id' => $empresa->rfc,
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