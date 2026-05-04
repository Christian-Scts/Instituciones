<?php

namespace App\Http\Middleware;

use App\Models\Empresa;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class RateLimitPuiRequests
{
    public function handle(Request $request, Closure $next): Response
    {
        $clientIp = $request->ip();

        $empresa = null;
        $institucionId = $request->input('institucion_id');

        if ($institucionId) {
            $empresa = Empresa::where('rfc', $institucionId)->first();
        }

        if (!$empresa && $request->attributes->has('empresa')) {
            $empresa = $request->attributes->get('empresa');
        }

        $limit = $empresa ? $empresa->rate_limit_per_minute : 30;
        $keyBase = $empresa ? 'pui_rate:' . $empresa->id : 'pui_rate_ip:' . $clientIp;
        $key = $keyBase . ':' . now()->format('YmdHi');

        $current = Cache::get($key, 0);

        if ($current >= $limit) {
            return response()->json([
                'error' => 'Rate limit excedido',
                'limit' => $limit,
                'ip_detectada' => $clientIp,
            ], 429);
        }

        Cache::put($key, $current + 1, now()->addMinute());

        $response = $next($request);
        $response->headers->set('X-RateLimit-Limit', $limit);
        $response->headers->set('X-RateLimit-Remaining', max($limit - ($current + 1), 0));

        return $response;
    }
}