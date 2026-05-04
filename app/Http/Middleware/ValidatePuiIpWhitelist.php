<?php

namespace App\Http\Middleware;

use App\Models\Empresa;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidatePuiIpWhitelist
{
    public function handle(Request $request, Closure $next): Response
    {
        $clientIp = $request->ip();

        \Log::info('PUI IP DETECTADA', [
            'ip' => $request->ip(),
            'path' => $request->path(),
        ]);

        $institucionId = $request->input('institucion_id');
        $empresa = null;

        if ($institucionId) {
            $empresa = Empresa::where('rfc', $institucionId)->first();
        }

        if (!$empresa && $request->attributes->has('empresa')) {
            $empresa = $request->attributes->get('empresa');
        }

        if (!$empresa) {
            return $next($request);
        }

        $whitelist = $empresa->ip_whitelist ?? [];

        if (!is_array($whitelist) || empty($whitelist)) {
            return response()->json([
                'error' => 'IP no autorizada para esta empresa',
                'ip_detectada' => $clientIp,
            ], 403);
        }

        if (!in_array($clientIp, $whitelist, true)) {
            return response()->json([
                'error' => 'IP no autorizada para esta empresa',
                'ip_detectada' => $clientIp,
            ], 403);
        }

        return $next($request);
    }
}