<?php
namespace App\Services;

use App\Models\Empresa;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

class PuiClienteService
{
     public function loginPui(Empresa $empresa)
    {
        $clave = app()->environment('local')
        ? config('services.pui.local_test_password')
        : Crypt::decryptString($empresa->pui_password_encrypted);

        return Http::acceptJson()
            ->timeout(20)
            ->post(rtrim($empresa->url_base_api, '/') . '/login', [
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

    public function loginPui_otro(Empresa $empresa)
    {
        $clave = Crypt::decryptString($empresa->pui_password_encrypted);

        return Http::acceptJson()
            ->timeout(20)
            ->post(rtrim($empresa->url_base_api, '/') . '/login', [
                'institucion_id' => $empresa->rfc,
                'clave' => $clave,
            ]);
    }

    public function notificarCoincidencia_otro(string $url, string $token, array $payload)
    {
        return Http::acceptJson()
            ->withToken($token)
            ->timeout(20)
            ->post($url, $payload);
    }

    public function busquedaFinalizada_otro(string $url, string $token, array $payload)
    {
        return Http::acceptJson()
            ->withToken($token)
            ->timeout(20)
            ->post($url, $payload);
    }
}