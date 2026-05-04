@extends('layouts.app')

@section('content')
    @php
        $coincidenciasData = [
            '1' => $coincidenciasPorFase['1'] ?? 0,
            '2' => $coincidenciasPorFase['2'] ?? 0,
            '3' => $coincidenciasPorFase['3'] ?? 0,
        ];
    @endphp

    <div
        id="dashboard-root"
        data-dashboard-url="{{ route('admin.dashboard.data') }}"
        data-coincidencias='@json($coincidenciasData)'
        data-reportes-estatus='@json($reportesPorEstatus)'
        data-salud='@json($salud)'
    >
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-3xl font-bold">Dashboard</h2>
                <p class="text-slate-500">Monitoreo operativo en tiempo real</p>
            </div>

            <div class="text-right">
                <div class="text-sm text-slate-500">Última actualización</div>
                <div id="server-time" class="font-semibold">
                   {{ now()->toDateTimeString() }}
                </div>
            </div>
        </div>

        <div class="mb-6">
            <div
                id="salud-card"
                class="rounded-2xl shadow-soft p-5 border
                    @if(($salud['color'] ?? '') === 'green') bg-green-50 border-green-200
                    @elseif(($salud['color'] ?? '') === 'yellow') bg-yellow-50 border-yellow-200
                    @else bg-red-50 border-red-200
                    @endif"
            >
                <div class="flex items-center gap-4">
                    <div
                        id="semaforo"
                        class="w-6 h-6 rounded-full
                            @if(($salud['color'] ?? '') === 'green') bg-green-500
                            @elseif(($salud['color'] ?? '') === 'yellow') bg-yellow-500
                            @else bg-red-500
                            @endif"
                    ></div>

                    <div class="flex-1">
                        <h3 class="text-xl font-bold">
                            Estado general:
                            <span id="salud-label">{{ $salud['label'] ?? 'SIN DATO' }}</span>
                        </h3>

                        <p class="text-sm text-slate-600">
                            Errores hoy:
                            <strong id="salud-errores">{{ $salud['errores_hoy'] ?? 0 }}</strong>
                            |
                            Failed jobs:
                            <strong id="salud-failed">{{ $salud['failed_jobs'] ?? 0 }}</strong>
                            |
                            Jobs pendientes:
                            <strong id="salud-jobs">{{ $salud['jobs_pendientes'] ?? 0 }}</strong>
                            |
                            Latencia promedio:
                            <strong id="salud-latencia">{{ $salud['latencia_ms'] ?? 0 }}</strong> ms
                        </p>

                        <div class="mt-3">
                            <div class="text-xs text-slate-500 mb-1">Score de salud</div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div
                                    id="health-bar"
                                    class="h-2 rounded-full transition-all duration-500"
                                    data-score="{{ $salud['score'] ?? 0 }}"
                                    data-color="{{ $salud['color'] ?? 'red' }}"
                                ></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-8 space-y-3" id="alertas-container">
            @foreach($alertas as $alerta)
                @php
                    $tipo = $alerta['tipo'] ?? 'success';
                    $classes = match($tipo) {
                        'error' => 'bg-red-50 border-red-200',
                        'warning' => 'bg-yellow-50 border-yellow-200',
                        'info' => 'bg-blue-50 border-blue-200',
                        default => 'bg-green-50 border-green-200',
                    };

                    $icono = match($tipo) {
                        'error' => '🔴',
                        'warning' => '🟡',
                        'info' => '🔵',
                        default => '🟢',
                    };
                @endphp

                <div class="rounded-2xl p-4 shadow-soft border {{ $classes }}">
                    <div class="flex items-start gap-3">
                        <div class="text-xl">{{ $icono }}</div>
                        <div>
                            <div class="font-bold">{{ $alerta['titulo'] ?? 'Aviso' }}</div>
                            <div class="text-sm text-slate-700">{{ $alerta['mensaje'] ?? '' }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-6 gap-4 mb-8">
            <div class="bg-white rounded-2xl shadow-soft p-5 border-l-4 border-blue-700">
                <div class="text-sm text-slate-500">Empresas</div>
                <div id="stat-empresas" class="text-3xl font-bold">{{ $stats['empresas'] ?? 0 }}</div>
            </div>

            <div class="bg-white rounded-2xl shadow-soft p-5 border-l-4 border-green-600">
                <div class="text-sm text-slate-500">Empresas activas</div>
                <div id="stat-empresas-activas" class="text-3xl font-bold">{{ $stats['empresas_activas'] ?? 0 }}</div>
            </div>

            <div class="bg-white rounded-2xl shadow-soft p-5 border-l-4 border-amber-500">
                <div class="text-sm text-slate-500">Tokens activos</div>
                <div id="stat-tokens" class="text-3xl font-bold">{{ $stats['tokens_activos'] ?? 0 }}</div>
            </div>

            <div class="bg-white rounded-2xl shadow-soft p-5 border-l-4 border-blue-700">
                <div class="text-sm text-slate-500">Reportes activos</div>
                <div id="stat-reportes-activos" class="text-3xl font-bold">{{ $stats['reportes_activos'] ?? 0 }}</div>
            </div>

            <div class="bg-white rounded-2xl shadow-soft p-5 border-l-4 border-red-600">
                <div class="text-sm text-slate-500">Errores hoy</div>
                <div id="stat-errores-hoy" class="text-3xl font-bold">{{ $stats['errores_hoy'] ?? 0 }}</div>
            </div>

            <div class="bg-white rounded-2xl shadow-soft p-5 border-l-4 border-purple-600">
                <div class="text-sm text-slate-500">% Efectividad</div>
                <div id="stat-efectividad" class="text-3xl font-bold">{{ $stats['efectividad'] ?? 0 }}%</div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-soft p-5">
                <h3 class="font-bold mb-4">Coincidencias por fase</h3>
                <canvas id="chartCoincidencias" height="120"></canvas>
            </div>

            <div class="bg-white rounded-2xl shadow-soft p-5">
                <h3 class="font-bold mb-4">Reportes por estatus</h3>
                <canvas id="chartReportes" height="120"></canvas>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-soft p-5">
                <h3 class="font-bold mb-4">Estado de cola</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span>Jobs pendientes</span>
                        <strong id="jobs-pendientes">{{ $queueEstado['jobs_pendientes'] ?? 0 }}</strong>
                    </div>
                    <div class="flex justify-between">
                        <span>Jobs fallidos</span>
                        <strong id="failed-jobs">{{ $queueEstado['failed_jobs'] ?? 0 }}</strong>
                    </div>
                    <div class="flex justify-between">
                        <span>Fase 3 programados</span>
                        <strong id="jobs-fase3">{{ $queueEstado['jobs_fase3_programados'] ?? 0 }}</strong>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-soft p-5 xl:col-span-2">
                <h3 class="font-bold mb-4">Resumen de reportes</h3>
                <div id="estatus-reportes" class="grid grid-cols-2 md:grid-cols-3 gap-3 text-sm">
                    @forelse($reportesPorEstatus as $estatus => $total)
                        <div class="rounded-xl bg-slate-50 p-3 border">
                            <div class="text-slate-500">{{ $estatus }}</div>
                            <div class="text-2xl font-bold">{{ $total }}</div>
                        </div>
                    @empty
                        <div class="text-slate-500">Sin datos</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                <div class="p-5 border-b">
                    <h3 class="font-bold">Últimos reportes</h3>
                </div>

                <div class="overflow-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="p-3 text-left">Empresa</th>
                                <th class="p-3 text-left">ID búsqueda</th>
                                <th class="p-3 text-left">Fase</th>
                                <th class="p-3 text-left">Estatus</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-reportes">
                            @foreach($ultimosReportes as $reporte)
                                <tr class="border-t hover:bg-slate-50 transition">
                                    <td class="p-3">{{ $reporte->empresa?->razon_social }}</td>
                                    <td class="p-3">
                                        {{ preg_replace('/[^A-Za-z0-9\-_]/', '', (string) $reporte->id_busqueda) }}
                                    </td>
                                    <td class="p-3">{{ $reporte->fase_actual }}</td>
                                    <td class="p-3">
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                                            @if($reporte->estatus === 'monitoreo_continuo') bg-green-100 text-green-700
                                            @elseif($reporte->estatus === 'desactivado') bg-red-100 text-red-700
                                            @elseif($reporte->estatus === 'fase_2_completada') bg-blue-100 text-blue-700
                                            @else bg-yellow-100 text-yellow-700
                                            @endif">
                                            {{ $reporte->estatus }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                <div class="p-5 border-b">
                    <h3 class="font-bold">Últimos logs</h3>
                </div>

                <div class="overflow-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="p-3 text-left">Fecha</th>
                                <th class="p-3 text-left">Endpoint</th>
                                <th class="p-3 text-left">HTTP</th>
                                <th class="p-3 text-left">Error</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-logs">
                            @foreach($ultimosLogs as $log)
                                <tr class="border-t hover:bg-slate-50 transition">
                                    <td class="p-3">{{ $log->created_at }}</td>
                                    <td class="p-3">{{ $log->endpoint }}</td>
                                    <td class="p-3">{{ $log->http_code }}</td>
                                    <td class="p-3">{{ \Illuminate\Support\Str::limit($log->error, 40) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
            <div class="p-5 border-b">
                <h3 class="font-bold">Estado por empresa</h3>
            </div>

            <div class="overflow-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="p-3 text-left">RFC</th>
                            <th class="p-3 text-left">Empresa</th>
                            <th class="p-3 text-left">Ambiente</th>
                            <th class="p-3 text-left">Activo</th>
                            <th class="p-3 text-left">Sandbox</th>
                            <th class="p-3 text-left">Productivo</th>
                            <th class="p-3 text-left">Último login</th>
                            <th class="p-3 text-left">Última prueba webhook</th>
                        </tr>
                    </thead>
                    <tbody id="tabla-empresas-estatica">
                        @foreach($empresasEstado as $empresa)
                            <tr class="border-t hover:bg-slate-50 transition">
                                <td class="p-3">{{ $empresa->rfc }}</td>
                                <td class="p-3">{{ $empresa->razon_social }}</td>
                                <td class="p-3">{{ strtoupper($empresa->ambiente) }}</td>
                                <td class="p-3">{{ $empresa->activo ? 'Sí­' : 'No' }}</td>
                                <td class="p-3">{{ $empresa->aprobado_sandbox ? 'Sí­' : 'No' }}</td>
                                <td class="p-3">{{ $empresa->aprobado_productivo ? 'Sí­' : 'No' }}</td>
                                <td class="p-3">{{ $empresa->ultimo_login_ok_en }}</td>
                                <td class="p-3">{{ $empresa->ultima_prueba_webhook_en }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection