@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl font-bold">Dashboard PUI</h2>
            <p class="text-slate-500">Monitoreo operativo en tiempo real</p>
        </div>

        <div class="text-right">
            <div class="text-sm text-slate-500">Última actualización</div>
            <div id="server-time" class="font-semibold">{{ now()->toDateTimeString() }}</div>
        </div>
    </div>

    {{-- Semáforo de salud --}}
    <div class="mb-6">
        <div class="rounded-2xl shadow-soft p-5 border
            @if($salud['color'] === 'green') bg-green-50 border-green-200
            @elseif($salud['color'] === 'yellow') bg-yellow-50 border-yellow-200
            @else bg-red-50 border-red-200
            @endif">
            <div class="flex items-center gap-4">
                <div id="semaforo"
                     class="w-6 h-6 rounded-full
                        @if($salud['color'] === 'green') bg-green-500
                        @elseif($salud['color'] === 'yellow') bg-yellow-500
                        @else bg-red-500
                        @endif">
                </div>

                <div class="flex-1">
                    <h3 class="text-xl font-bold">
                        Estado general:
                        <span id="salud-label">{{ $salud['label'] }}</span>
                    </h3>

                    <p class="text-sm text-slate-600">
                        Errores hoy:
                        <strong id="salud-errores">{{ $salud['errores_hoy'] }}</strong>
                        |
                        Failed jobs:
                        <strong id="salud-failed">{{ $salud['failed_jobs'] }}</strong>
                        |
                        Jobs pendientes:
                        <strong id="salud-jobs">{{ $salud['jobs_pendientes'] }}</strong>
                        |
                        Latencia promedio:
                        <strong id="salud-latencia">{{ $salud['latencia_ms'] }}</strong> ms
                    </p>

                    <div class="mt-3">
                        <div class="text-xs text-slate-500 mb-1">Score de salud</div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div id="health-bar"
                                class="h-2 rounded-full transition-all duration-500"
                                style="width: {{ $salud['score'] }}%;
                                background: {{ $salud['score'] > 80 ? '#16a34a' : ($salud['score'] > 50 ? '#f59e0b' : '#dc2626') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Alertas --}}
    <div class="mb-8 space-y-3" id="alertas-container">
        @foreach($alertas as $alerta)
            <div class="rounded-2xl p-4 shadow-soft border
                @if($alerta['tipo'] === 'error') bg-red-50 border-red-200
                @elseif($alerta['tipo'] === 'warning') bg-yellow-50 border-yellow-200
                @elseif($alerta['tipo'] === 'info') bg-blue-50 border-blue-200
                @else bg-green-50 border-green-200
                @endif">
                <div class="flex items-start gap-3">
                    <div class="text-xl">
                        @if($alerta['tipo'] === 'error') 🔴
                        @elseif($alerta['tipo'] === 'warning') 🟡
                        @elseif($alerta['tipo'] === 'info') 🔵
                        @else 🟢
                        @endif
                    </div>
                    <div>
                        <div class="font-bold">{{ $alerta['titulo'] }}</div>
                        <div class="text-sm text-slate-700">{{ $alerta['mensaje'] }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- KPIs --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-6 gap-4 mb-8">
        <div class="bg-white rounded-2xl shadow-soft p-5 border-l-4 border-blue-700">
            <div class="text-sm text-slate-500">Empresas</div>
            <div id="stat-empresas" class="text-3xl font-bold">{{ $stats['empresas'] }}</div>
        </div>

        <div class="bg-white rounded-2xl shadow-soft p-5 border-l-4 border-green-600">
            <div class="text-sm text-slate-500">Empresas activas</div>
            <div id="stat-empresas-activas" class="text-3xl font-bold">{{ $stats['empresas_activas'] }}</div>
        </div>

        <div class="bg-white rounded-2xl shadow-soft p-5 border-l-4 border-amber-500">
            <div class="text-sm text-slate-500">Tokens activos</div>
            <div id="stat-tokens" class="text-3xl font-bold">{{ $stats['tokens_activos'] }}</div>
        </div>

        <div class="bg-white rounded-2xl shadow-soft p-5 border-l-4 border-blue-700">
            <div class="text-sm text-slate-500">Reportes activos</div>
            <div id="stat-reportes-activos" class="text-3xl font-bold">{{ $stats['reportes_activos'] }}</div>
        </div>

        <div class="bg-white rounded-2xl shadow-soft p-5 border-l-4 border-red-600">
            <div class="text-sm text-slate-500">Errores hoy</div>
            <div id="stat-errores-hoy" class="text-3xl font-bold">{{ $stats['errores_hoy'] }}</div>
        </div>

        <div class="bg-white rounded-2xl shadow-soft p-5 border-l-4 border-purple-600">
            <div class="text-sm text-slate-500">% Efectividad</div>
            <div id="stat-efectividad" class="text-3xl font-bold">{{ $stats['efectividad'] }}%</div>
        </div>
    </div>

    {{-- Gráficas --}}
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

    {{-- Estado cola / resumen --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-soft p-5">
            <h3 class="font-bold mb-4">Estado de cola</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span>Jobs pendientes</span>
                    <strong id="jobs-pendientes">{{ $queueEstado['jobs_pendientes'] }}</strong>
                </div>
                <div class="flex justify-between">
                    <span>Jobs fallidos</span>
                    <strong id="failed-jobs">{{ $queueEstado['failed_jobs'] }}</strong>
                </div>
                <div class="flex justify-between">
                    <span>Fase 3 programados</span>
                    <strong id="jobs-fase3">{{ $queueEstado['jobs_fase3_programados'] }}</strong>
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

    {{-- Tablas --}}
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
                                <td class="p-3">{{ $reporte->id_busqueda }}</td>
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

    {{-- Empresas --}}
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
                <tbody id="tabla-empresas">
                    @foreach($empresasEstado as $empresa)
                        <tr class="border-t hover:bg-slate-50 transition">
                            <td class="p-3">{{ $empresa->rfc }}</td>
                            <td class="p-3">{{ $empresa->razon_social }}</td>
                            <td class="p-3">{{ strtoupper($empresa->ambiente) }}</td>
                            <td class="p-3">{{ $empresa->activo ? 'Sí' : 'No' }}</td>
                            <td class="p-3">{{ $empresa->aprobado_sandbox ? 'Sí' : 'No' }}</td>
                            <td class="p-3">{{ $empresa->aprobado_productivo ? 'Sí' : 'No' }}</td>
                            <td class="p-3">{{ $empresa->ultimo_login_ok_en }}</td>
                            <td class="p-3">{{ $empresa->ultima_prueba_webhook_en }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        let chartCoincidencias;
        let chartReportes;

        function initCharts() {
            const ctxCoincidencias = document.getElementById('chartCoincidencias').getContext('2d');
            const ctxReportes = document.getElementById('chartReportes').getContext('2d');

            chartCoincidencias = new Chart(ctxCoincidencias, {
                type: 'bar',
                data: {
                    labels: ['Fase 1', 'Fase 2', 'Fase 3'],
                    datasets: [{
                        label: 'Coincidencias',
                        data: [
                            {{ $coincidenciasPorFase['1'] }},
                            {{ $coincidenciasPorFase['2'] }},
                            {{ $coincidenciasPorFase['3'] }}
                        ],
                        backgroundColor: ['#1d4ed8', '#f59e0b', '#16a34a'],
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false }
                    }
                }
            });

            chartReportes = new Chart(ctxReportes, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode(array_keys($reportesPorEstatus)) !!},
                    datasets: [{
                        data: {!! json_encode(array_values($reportesPorEstatus)) !!},
                        backgroundColor: ['#16a34a', '#dc2626', '#f59e0b', '#1d4ed8', '#7c3aed', '#0f766e']
                    }]
                },
                options: {
                    responsive: true,
                    cutout: '65%',
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        function updateCharts(data) {
            chartCoincidencias.data.datasets[0].data = [
                data.coincidenciasPorFase['1'],
                data.coincidenciasPorFase['2'],
                data.coincidenciasPorFase['3']
            ];
            chartCoincidencias.update();

            chartReportes.data.labels = Object.keys(data.reportesPorEstatus);
            chartReportes.data.datasets[0].data = Object.values(data.reportesPorEstatus);
            chartReportes.update();
        }

        function renderAlertas(alertas) {
            const container = document.getElementById('alertas-container');
            container.innerHTML = '';

            alertas.forEach(alerta => {
                let classes = 'bg-green-50 border-green-200';
                let icon = '🟢';

                if (alerta.tipo === 'error') {
                    classes = 'bg-red-50 border-red-200';
                    icon = '🔴';
                } else if (alerta.tipo === 'warning') {
                    classes = 'bg-yellow-50 border-yellow-200';
                    icon = '🟡';
                } else if (alerta.tipo === 'info') {
                    classes = 'bg-blue-50 border-blue-200';
                    icon = '🔵';
                }

                container.innerHTML += `
                    <div class="rounded-2xl p-4 shadow-soft border ${classes}">
                        <div class="flex items-start gap-3">
                            <div class="text-xl">${icon}</div>
                            <div>
                                <div class="font-bold">${alerta.titulo}</div>
                                <div class="text-sm text-slate-700">${alerta.mensaje}</div>
                            </div>
                        </div>
                    </div>
                `;
            });
        }

        function renderSalud(salud) {
            const semaforo = document.getElementById('semaforo');
            semaforo.className = 'w-6 h-6 rounded-full';

            if (salud.color === 'green') semaforo.classList.add('bg-green-500');
            else if (salud.color === 'yellow') semaforo.classList.add('bg-yellow-500');
            else semaforo.classList.add('bg-red-500');

            document.getElementById('salud-label').textContent = salud.label;
            document.getElementById('salud-errores').textContent = salud.errores_hoy;
            document.getElementById('salud-failed').textContent = salud.failed_jobs;
            document.getElementById('salud-jobs').textContent = salud.jobs_pendientes;
            document.getElementById('salud-latencia').textContent = salud.latencia_ms;

            const healthBar = document.getElementById('health-bar');
            healthBar.style.width = salud.score + '%';
            healthBar.style.background = salud.score > 80 ? '#16a34a' : (salud.score > 50 ? '#f59e0b' : '#dc2626');
        }

        async function refreshDashboard() {
            try {
                const response = await fetch('{{ route('admin.dashboard.data') }}', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) return;

                const data = await response.json();

                document.getElementById('server-time').textContent = data.server_time;
                document.getElementById('stat-empresas').textContent = data.stats.empresas;
                document.getElementById('stat-empresas-activas').textContent = data.stats.empresas_activas;
                document.getElementById('stat-tokens').textContent = data.stats.tokens_activos;
                document.getElementById('stat-reportes-activos').textContent = data.stats.reportes_activos;
                document.getElementById('stat-errores-hoy').textContent = data.stats.errores_hoy;
                document.getElementById('stat-efectividad').textContent = data.stats.efectividad + '%';

                document.getElementById('jobs-pendientes').textContent = data.queueEstado.jobs_pendientes;
                document.getElementById('failed-jobs').textContent = data.queueEstado.failed_jobs;
                document.getElementById('jobs-fase3').textContent = data.queueEstado.jobs_fase3_programados;

                renderSalud(data.salud);
                renderAlertas(data.alertas);
                updateCharts(data);

                const estatusContainer = document.getElementById('estatus-reportes');
                estatusContainer.innerHTML = '';

                if (Object.keys(data.reportesPorEstatus).length === 0) {
                    estatusContainer.innerHTML = '<div class="text-slate-500">Sin datos</div>';
                } else {
                    Object.entries(data.reportesPorEstatus).forEach(([key, value]) => {
                        estatusContainer.innerHTML += `
                            <div class="rounded-xl bg-slate-50 p-3 border">
                                <div class="text-slate-500">${key}</div>
                                <div class="text-2xl font-bold">${value}</div>
                            </div>
                        `;
                    });
                }

                const logsBody = document.getElementById('tabla-logs');
                logsBody.innerHTML = '';
                data.ultimosLogs.forEach(log => {
                    logsBody.innerHTML += `
                        <tr class="border-t hover:bg-slate-50 transition">
                            <td class="p-3">${log.created_at ?? ''}</td>
                            <td class="p-3">${log.endpoint ?? ''}</td>
                            <td class="p-3">${log.http_code ?? ''}</td>
                            <td class="p-3">${log.error ?? ''}</td>
                        </tr>
                    `;
                });

                const reportesBody = document.getElementById('tabla-reportes');
                reportesBody.innerHTML = '';
                data.ultimosReportes.forEach(reporte => {
                    let badgeClass = 'bg-yellow-100 text-yellow-700';
                    if (reporte.estatus === 'monitoreo_continuo') badgeClass = 'bg-green-100 text-green-700';
                    else if (reporte.estatus === 'desactivado') badgeClass = 'bg-red-100 text-red-700';
                    else if (reporte.estatus === 'fase_2_completada') badgeClass = 'bg-blue-100 text-blue-700';

                    reportesBody.innerHTML += `
                        <tr class="border-t hover:bg-slate-50 transition">
                            <td class="p-3">${reporte.empresa?.razon_social ?? ''}</td>
                            <td class="p-3">${reporte.id_busqueda ?? ''}</td>
                            <td class="p-3">${reporte.fase_actual ?? ''}</td>
                            <td class="p-3">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold ${badgeClass}">
                                    ${reporte.estatus ?? ''}
                                </span>
                            </td>
                        </tr>
                    `;
                });

                const empresasBody = document.getElementById('tabla-empresas');
                empresasBody.innerHTML = '';
                data.empresasEstado.forEach(empresa => {
                    empresasBody.innerHTML += `
                        <tr class="border-t hover:bg-slate-50 transition">
                            <td class="p-3">${empresa.rfc ?? ''}</td>
                            <td class="p-3">${empresa.razon_social ?? ''}</td>
                            <td class="p-3">${(empresa.ambiente ?? '').toUpperCase()}</td>
                            <td class="p-3">${empresa.activo ? 'Sí' : 'No'}</td>
                            <td class="p-3">${empresa.aprobado_sandbox ? 'Sí' : 'No'}</td>
                            <td class="p-3">${empresa.aprobado_productivo ? 'Sí' : 'No'}</td>
                            <td class="p-3">${empresa.ultimo_login_ok_en ?? ''}</td>
                            <td class="p-3">${empresa.ultima_prueba_webhook_en ?? ''}</td>
                        </tr>
                    `;
                });

            } catch (error) {
                console.error('Error actualizando dashboard:', error);
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            initCharts();
            setInterval(refreshDashboard, 10000);
        });
    </script>
@endsection