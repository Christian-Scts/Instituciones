<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\PuiPruebaConectividad;
use App\Services\PuiClienteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PruebaController extends Controller
{
    public function index_chido(Request $request)
        {
            
           $page = $request->query('page', 1);

    if (!filter_var($page, FILTER_VALIDATE_INT) || (int) $page < 1) {
        abort(404);
    }

    $empresas = Empresa::with(['reportes' => function ($q) {
        $q->whereNull('baja_en')
          ->whereIn('estatus', ['activo', 'activo_prueba', 'fase_1_completada', 'fase_2_completada', 'monitoreo_continuo'])
          ->orderByDesc('created_at');
    }])->orderBy('razon_social')->get();

    $pruebas = PuiPruebaConectividad::with('empresa')
        ->latest('ejecutada_en')
        ->paginate(20);

    return view('admin.pruebas.index', compact('empresas', 'pruebas'));
        }

    public function index(Request $request)
    {
        $empresas = Empresa::with(['reportes' => function ($q) {
            $q->whereNull('baja_en')
            ->whereIn('estatus', ['activo', 'activo_prueba', 'fase_2_completada', 'monitoreo_continuo'])
            ->orderByDesc('created_at');
        }])->orderBy('razon_social')->get();

        $pruebas = PuiPruebaConectividad::with('empresa')
            ->when($request->filled('empresa_id'), fn ($q) => $q->where('empresa_id', $request->integer('empresa_id')))
            ->latest('ejecutada_en')
            ->paginate(20)
            ->withQueryString();

        return view('admin.pruebas.index', compact('empresas', 'pruebas'));
    }

    public function webhook(Empresa $empresa)
    {
        $url = rtrim($empresa->url_base_api, '/') . '/activar-reporte-prueba';

        $payload = [
            'id' => 'FUB-DEMO-' . now()->format('YmdHis') . '-' . \Str::uuid(),
            'curp' => 'TEST000101HDFABC01',
            'fecha_desaparicion' => now()->toDateString(),
            'carpeta_investigacion' => 'CARPETA-DEMO-' . now()->format('Ymd'),
        ];

        $token = null;
        $status = 500;
        $body = ['error' => 'No se ejecut贸 la prueba'];

        try {
            $loginResponse = app(PuiClienteService::class)->loginPui($empresa);

            if (!$loginResponse->successful()) {
                $status = $loginResponse->status();
                $body = [
                    'error' => 'Fall贸 login previo',
                    'login_response' => $loginResponse->json() ?: $loginResponse->body(),
                ];
            } else {
                $token = $loginResponse->json('token');

                $response = Http::acceptJson()
                    ->withToken($token)
                    ->timeout(20)
                    ->post($url, $payload);

                $status = $response->status();
                $body = $response->json() ?: ['raw' => $response->body()];
            }
        } catch (\Throwable $e) {
            $body = ['error' => $e->getMessage()];
        }

        PuiPruebaConectividad::create([
            'empresa_id' => $empresa->id,
            'tipo_prueba' => 'webhook',
            'url' => $url,
            'request_json' => $payload,
            'response_json' => $body,
            'http_code' => $status,
            'exitosa' => $status >= 200 && $status < 300,
            'ejecutada_en' => now(),
        ]);

        if ($status >= 200 && $status < 300) {
            $empresa->update(['ultima_prueba_webhook_en' => now()]);
        }

        return back()->with(
            $status >= 200 && $status < 300 ? 'success' : 'error',
            $status >= 200 && $status < 300
                ? 'Prueba de webhook ejecutada correctamente.'
                : 'Prueba de webhook ejecutada con error HTTP ' . $status . '.'
        );
    }

    public function loginPui(Empresa $empresa, PuiClienteService $clienteService)
    {
        $status = 500;
        $body = ['error' => 'No se ejecut贸 la prueba'];

        try {
            $response = $clienteService->loginPui($empresa);
            $status = $response->status();
            $body = $response->json() ?: ['raw' => $response->body()];
        } catch (\Throwable $e) {
            $body = ['error' => $e->getMessage()];
        }

        PuiPruebaConectividad::create([
            'empresa_id' => $empresa->id,
            'tipo_prueba' => 'login_pui',
            'url' => rtrim($empresa->url_base_api, '/') . '/login',
            'request_json' => ['institucion_id' => $empresa->rfc],
            'response_json' => $body,
            'http_code' => $status,
            'exitosa' => $status >= 200 && $status < 300,
            'ejecutada_en' => now(),
        ]);

        return back()->with(
            $status >= 200 && $status < 300 ? 'success' : 'error',
            $status >= 200 && $status < 300
                ? 'Prueba de login ejecutada correctamente.'
                : 'Prueba de login ejecutada con error HTTP ' . $status . '.'
        );
    }
    
    
    public function desactivarReporte(Request $request, Empresa $empresa)
{
    $request->validate([
        'id_busqueda' => ['required', 'string'],
        'curp' => ['required', 'string'],
    ]);

    $url = rtrim($empresa->url_base_api, '/') . '/desactivar-reporte';

    $payload = [
        'id' => $request->id_busqueda,
        'curp' => $request->curp,
    ];

    $status = 500;
    $body = ['error' => 'No se ejecutó la prueba'];

    try {
        $loginResponse = app(PuiClienteService::class)->loginPui($empresa);

        if (!$loginResponse->successful()) {
            $status = $loginResponse->status();
            $body = [
                'error' => 'Falló login previo',
                'login_response' => $loginResponse->json() ?: $loginResponse->body(),
            ];
        } else {
            $token = $loginResponse->json('token');

            $response = Http::acceptJson()
                ->withToken($token)
                ->timeout(20)
                ->post($url, $payload);

            $status = $response->status();
            $body = $response->json() ?: ['raw' => $response->body()];
        }
    } catch (\Throwable $e) {
        $body = ['error' => $e->getMessage()];
    }

    PuiPruebaConectividad::create([
        'empresa_id' => $empresa->id,
        'tipo_prueba' => 'desactivar_reporte',
        'url' => $url,
        'request_json' => $payload,
        'response_json' => $body,
        'http_code' => $status,
        'exitosa' => $status >= 200 && $status < 300,
        'ejecutada_en' => now(),
    ]);

    return back()->with(
        $status >= 200 && $status < 300 ? 'success' : 'error',
        $status >= 200 && $status < 300
            ? 'Prueba de desactivación ejecutada.'
            : 'Prueba de desactivación con error HTTP ' . $status . '.'
    );
}
}