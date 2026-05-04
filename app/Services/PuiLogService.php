<?php
namespace App\Services;

use App\Models\Empresa;
use App\Models\PuiLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PuiLogService
{
    public function guardar(
        ?Empresa $empresa,
        Request $request,
        string $endpoint,
        array $response = [],
        ?string $idBusqueda = null,
        ?string $fase = null,
        ?int $httpCode = null,
        ?string $error = null,
        ?float $inicio = null
    ): void {
        $headers = $request->headers->all();

        if (isset($headers['authorization'])) {
            $headers['authorization'] = ['***'];
        }

        PuiLog::create([
            'empresa_id' => $empresa?->id,
            'endpoint' => $endpoint,
            'metodo' => $request->method(),
            'request_id' => $request->header('X-Request-Id', (string) Str::uuid()),
            'id_busqueda' => $idBusqueda,
            'fase_busqueda' => $fase,
            'headers_json' => json_encode($headers, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'body_json' => json_encode($request->all(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'response_json' => json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'http_code' => $httpCode,
            'duracion_ms' => $inicio ? (int) round((microtime(true) - $inicio) * 1000) : null,
            'ip_origen' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'error' => $error,
        ]);
    }
}