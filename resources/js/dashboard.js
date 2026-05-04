function parseJsonData(element, attribute, fallback = {}) {
    try {
        const raw = element.dataset[attribute];
        return raw ? JSON.parse(raw) : fallback;
    } catch {
        return fallback;
    }
}

function getBadgeClass(estatus) {
    if (estatus === 'monitoreo_continuo') return 'bg-green-100 text-green-700';
    if (estatus === 'desactivado') return 'bg-red-100 text-red-700';
    if (estatus === 'fase_2_completada') return 'bg-blue-100 text-blue-700';
    return 'bg-yellow-100 text-yellow-700';
}

function getAlertStyles(tipo) {
    if (tipo === 'error') {
        return {
            classes: 'bg-red-50 border-red-200',
            icon: '🔴´',
        };
    }

    if (tipo === 'warning') {
        return {
            classes: 'bg-yellow-50 border-yellow-200',
            icon: '🟡',
        };
    }

    if (tipo === 'info') {
        return {
            classes: 'bg-blue-50 border-blue-200',
            icon: '🔵',
        };
    }

    return {
        classes: 'bg-green-50 border-green-200',
        icon: '🟢',
    };
}

function getHealthClasses(color) {
    if (color === 'green') {
        return {
            card: 'bg-green-50 border-green-200',
            light: 'bg-green-500',
            bar: 'bg-green-600',
        };
    }

    if (color === 'yellow') {
        return {
            card: 'bg-yellow-50 border-yellow-200',
            light: 'bg-yellow-500',
            bar: 'bg-yellow-500',
        };
    }

    return {
        card: 'bg-red-50 border-red-200',
        light: 'bg-red-500',
        bar: 'bg-red-600',
    };
}

function escapeHtml(value) {
    return String(value ?? '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}

function cleanIdBusqueda(value) {
    return String(value ?? '').replace(/[^A-Za-z0-9\-_]/g, '');
}

function cleanEndpoint(value) {
    return String(value ?? '').replace(/[^A-Za-z0-9/_\-.]/g, '');
}

document.addEventListener('DOMContentLoaded', () => {
    const root = document.getElementById('dashboard-root');

    if (!root || typeof window.Chart === 'undefined') {
        return;
    }

    const dashboardUrl = root.dataset.dashboardUrl;
    const coincidenciasPorFase = parseJsonData(root, 'coincidencias', { 1: 0, 2: 0, 3: 0 });
    const reportesPorEstatus = parseJsonData(root, 'reportesEstatus', {});
    const saludInicial = parseJsonData(root, 'salud', { score: 0, color: 'red' });

    const healthBar = document.getElementById('health-bar');
    const semaforo = document.getElementById('semaforo');
    const saludCard = document.getElementById('salud-card');

    function applyHealthBar(score, color) {
        const health = getHealthClasses(color);

        if (healthBar) {
            healthBar.style.width = `${score}%`;
            healthBar.className = `h-2 rounded-full transition-all duration-500 ${health.bar}`;
        }

        if (semaforo) {
            semaforo.className = `w-6 h-6 rounded-full ${health.light}`;
        }

        if (saludCard) {
            saludCard.className = `rounded-2xl shadow-soft p-5 border ${health.card}`;
        }
    }

    applyHealthBar(Number(saludInicial.score ?? 0), saludInicial.color ?? 'red');

    const ctxCoincidencias = document.getElementById('chartCoincidencias')?.getContext('2d');
    const ctxReportes = document.getElementById('chartReportes')?.getContext('2d');

    if (!ctxCoincidencias || !ctxReportes) {
        return;
    }

    const chartCoincidencias = new window.Chart(ctxCoincidencias, {
        type: 'bar',
        data: {
            labels: ['Fase 1', 'Fase 2', 'Fase 3'],
            datasets: [{
                label: 'Coincidencias',
                data: [
                    Number(coincidenciasPorFase['1'] ?? 0),
                    Number(coincidenciasPorFase['2'] ?? 0),
                    Number(coincidenciasPorFase['3'] ?? 0),
                ],
                backgroundColor: ['#1d4ed8', '#f59e0b', '#16a34a'],
                borderRadius: 8,
            }],
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
            },
        },
    });

    const chartReportes = new window.Chart(ctxReportes, {
        type: 'doughnut',
        data: {
            labels: Object.keys(reportesPorEstatus),
            datasets: [{
                data: Object.values(reportesPorEstatus),
                backgroundColor: ['#16a34a', '#dc2626', '#f59e0b', '#1d4ed8', '#7c3aed', '#0f766e'],
            }],
        },
        options: {
            responsive: true,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                },
            },
        },
    });

    function renderAlertas(alertas) {
        const container = document.getElementById('alertas-container');
        if (!container) return;

        container.innerHTML = '';

        alertas.forEach((alerta) => {
            const { classes, icon } = getAlertStyles(alerta.tipo);

            container.insertAdjacentHTML('beforeend', `
                <div class="rounded-2xl p-4 shadow-soft border ${classes}">
                    <div class="flex items-start gap-3">
                        <div class="text-xl">${icon}</div>
                        <div>
                            <div class="font-bold">${alerta.titulo ?? 'Aviso'}</div>
                            <div class="text-sm text-slate-700">${alerta.mensaje ?? ''}</div>
                        </div>
                    </div>
                </div>
            `);
        });
    }

    function renderSalud(salud) {
        document.getElementById('salud-label').textContent = salud.label ?? 'SIN DATO';
        document.getElementById('salud-errores').textContent = salud.errores_hoy ?? 0;
        document.getElementById('salud-failed').textContent = salud.failed_jobs ?? 0;
        document.getElementById('salud-jobs').textContent = salud.jobs_pendientes ?? 0;
        document.getElementById('salud-latencia').textContent = salud.latencia_ms ?? 0;

        applyHealthBar(Number(salud.score ?? 0), salud.color ?? 'red');
    }

    function updateCharts(data) {
        chartCoincidencias.data.datasets[0].data = [
            Number(data.coincidenciasPorFase?.['1'] ?? 0),
            Number(data.coincidenciasPorFase?.['2'] ?? 0),
            Number(data.coincidenciasPorFase?.['3'] ?? 0),
        ];
        chartCoincidencias.update();

        chartReportes.data.labels = Object.keys(data.reportesPorEstatus ?? {});
        chartReportes.data.datasets[0].data = Object.values(data.reportesPorEstatus ?? {});
        chartReportes.update();
    }

    async function refreshDashboard() {
        try {
            const response = await fetch(dashboardUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    Accept: 'application/json',
                },
            });

            if (!response.ok) return;

            const data = await response.json();

            document.getElementById('server-time').textContent = data.server_time ?? '';
            document.getElementById('stat-empresas').textContent = data.stats?.empresas ?? 0;
            document.getElementById('stat-empresas-activas').textContent = data.stats?.empresas_activas ?? 0;
            document.getElementById('stat-tokens').textContent = data.stats?.tokens_activos ?? 0;
            document.getElementById('stat-reportes-activos').textContent = data.stats?.reportes_activos ?? 0;
            document.getElementById('stat-errores-hoy').textContent = data.stats?.errores_hoy ?? 0;
            document.getElementById('stat-efectividad').textContent = `${data.stats?.efectividad ?? 0}%`;

            document.getElementById('jobs-pendientes').textContent = data.queueEstado?.jobs_pendientes ?? 0;
            document.getElementById('failed-jobs').textContent = data.queueEstado?.failed_jobs ?? 0;
            document.getElementById('jobs-fase3').textContent = data.queueEstado?.jobs_fase3_programados ?? 0;

            renderSalud(data.salud ?? {});
            renderAlertas(data.alertas ?? []);
            updateCharts(data);

            const estatusContainer = document.getElementById('estatus-reportes');
            if (estatusContainer) {
                estatusContainer.innerHTML = '';

                const estatus = data.reportesPorEstatus ?? {};
                const keys = Object.keys(estatus);

                if (keys.length === 0) {
                    estatusContainer.innerHTML = '<div class="text-slate-500">Sin datos</div>';
                } else {
                    keys.forEach((key) => {
                        estatusContainer.insertAdjacentHTML('beforeend', `
                            <div class="rounded-xl bg-slate-50 p-3 border">
                                <div class="text-slate-500">${key}</div>
                                <div class="text-2xl font-bold">${estatus[key]}</div>
                            </div>
                        `);
                    });
                }
            }

            const logsBody = document.getElementById('tabla-logs');
            if (logsBody) {
                logsBody.innerHTML = '';

                (data.ultimosLogs ?? []).forEach((log) => {
                    logsBody.insertAdjacentHTML('beforeend', `
                        <tr class="border-t hover:bg-slate-50 transition">
                            <td class="p-3">${log.created_at ?? ''}</td>
                            <td class="p-3">${escapeHtml(cleanEndpoint(log.endpoint))}</td>
                            <td class="p-3">${log.http_code ?? ''}</td>
                           <td class="p-3">${escapeHtml(log.error ?? '')}</td>
                        </tr>
                    `);
                });
            }

            const reportesBody = document.getElementById('tabla-reportes');
            if (reportesBody) {
                reportesBody.innerHTML = '';

                (data.ultimosReportes ?? []).forEach((reporte) => {
                    const badgeClass = getBadgeClass(reporte.estatus);

                    reportesBody.insertAdjacentHTML('beforeend', `
                        <tr class="border-t hover:bg-slate-50 transition">
                            <td class="p-3">${reporte.empresa?.razon_social ?? ''}</td>
                            <td class="p-3">${escapeHtml(cleanIdBusqueda(reporte.id_busqueda))}</td>
                            <td class="p-3">${reporte.fase_actual ?? ''}</td>
                            <td class="p-3">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold ${badgeClass}">
                                    ${reporte.estatus ?? ''}
                                </span>
                            </td>
                        </tr>
                    `);
                });
            }
            
        } catch (error) {
            console.error('Error actualizando dashboard:', error);
        }
    }

    setInterval(refreshDashboard, 10000);
});