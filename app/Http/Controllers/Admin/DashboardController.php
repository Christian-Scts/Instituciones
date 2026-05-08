<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\PuiCoincidencia;
use App\Models\PuiLog;
use App\Models\PuiReporte;
use App\Models\PuiToken;
use Illuminate\Support\Facades\DB;
use App\Support\AdminEmpresaScope;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'stats' => $this->buildStats(),
            'ultimosLogs' => $this->ultimosLogs(),
            'ultimosReportes' => $this->ultimosReportes(),
            'coincidenciasPorFase' => $this->coincidenciasPorFase(),
            'reportesPorEstatus' => $this->reportesPorEstatus(),
            'empresasEstado' => $this->empresasEstado(),
            'queueEstado' => $this->queueEstado(),
            'salud' => $this->saludSistema(),
            'alertas' => $this->alertasSistema(),
        ]);
    }

    public function data()
    {
        return response()->json([
            'stats' => $this->buildStats(),
            'ultimosLogs' => $this->ultimosLogs(),
            'ultimosReportes' => $this->ultimosReportes(),
            'coincidenciasPorFase' => $this->coincidenciasPorFase(),
            'reportesPorEstatus' => $this->reportesPorEstatus(),
            'empresasEstado' => $this->empresasEstado(),
            'queueEstado' => $this->queueEstado(),
            'salud' => $this->saludSistema(),
            'alertas' => $this->alertasSistema(),
            'server_time' => now()->toDateTimeString(),
        ]);
    }

    private function buildStats(): array
{
    $logsHoy = AdminEmpresaScope::filtrarPorEmpresaId(
        PuiLog::whereDate('created_at', today())
    )->count();

    $erroresHoy = AdminEmpresaScope::filtrarPorEmpresaId(
        PuiLog::whereDate('created_at', today())
            ->whereNotNull('error')
    )->count();

    $totalCoincidencias = AdminEmpresaScope::filtrarPorEmpresaId(
        PuiCoincidencia::query()
    )->count();

    $coincidenciasExitosas = AdminEmpresaScope::filtrarPorEmpresaId(
        PuiCoincidencia::where('http_code', 200)
    )->count();

    return [
        'empresas' => AdminEmpresaScope::filtrarEmpresas(
            Empresa::query()
        )->count(),

        'empresas_activas' => AdminEmpresaScope::filtrarEmpresas(
            Empresa::where('activo', true)
        )->count(),

        'tokens_activos' => AdminEmpresaScope::filtrarPorEmpresaId(
            PuiToken::where('estatus', 'activo')
                ->where('expira_en', '>', now())
        )->count(),

        'reportes_totales' => AdminEmpresaScope::filtrarPorEmpresaId(
            PuiReporte::query()
        )->count(),

        'reportes_activos' => AdminEmpresaScope::filtrarPorEmpresaId(
            PuiReporte::whereNull('baja_en')
        )->count(),

        'reportes_prueba' => AdminEmpresaScope::filtrarPorEmpresaId(
            PuiReporte::where('es_prueba', true)
        )->count(),

        'reportes_desactivados' => AdminEmpresaScope::filtrarPorEmpresaId(
            PuiReporte::where('estatus', 'desactivado')
        )->count(),

        'coincidencias_totales' => $totalCoincidencias,

        'errores_hoy' => $erroresHoy,

        'logs_hoy' => $logsHoy,

        'tasa_error' => $logsHoy > 0
            ? round(($erroresHoy / $logsHoy) * 100, 2)
            : 0,

        'efectividad' => $totalCoincidencias > 0
            ? round(($coincidenciasExitosas / $totalCoincidencias) * 100, 2)
            : 100,
    ];
}

    private function ultimosLogs()
        {
            return AdminEmpresaScope::filtrarPorEmpresaId(
                    PuiLog::with('empresa')
                )
                ->latest()
                ->limit(10)
                ->get([
                    'id',
                    'empresa_id',
                    'endpoint',
                    'metodo',
                    'id_busqueda',
                    'http_code',
                    'error',
                    'created_at',
                ]);
        }

    private function ultimosReportes()
{
    return AdminEmpresaScope::filtrarPorEmpresaId(
            PuiReporte::with('empresa')
        )
        ->latest()
        ->limit(10)
        ->get([
            'id',
            'empresa_id',
            'id_busqueda',
            'curp',
            'fase_actual',
            'estatus',
            'es_prueba',
            'alta_en',
            'baja_en',
            'created_at',
        ]);
}

    private function coincidenciasPorFase(): array
{
    $rows = AdminEmpresaScope::filtrarPorEmpresaId(
            PuiCoincidencia::select(
                'fase_busqueda',
                DB::raw('COUNT(*) as total')
            )
        )
        ->groupBy('fase_busqueda')
        ->orderBy('fase_busqueda')
        ->get();

    return [
        '1' => (int) optional($rows->firstWhere('fase_busqueda', '1'))->total,
        '2' => (int) optional($rows->firstWhere('fase_busqueda', '2'))->total,
        '3' => (int) optional($rows->firstWhere('fase_busqueda', '3'))->total,
    ];
}

    private function reportesPorEstatus(): array
{
    return AdminEmpresaScope::filtrarPorEmpresaId(
            PuiReporte::query()
        )
        ->selectRaw('estatus, COUNT(*) as total')
        ->groupBy('estatus')
        ->pluck('total', 'estatus')
        ->map(fn ($value) => (int) $value)
        ->toArray();
}

    private function empresasEstado()
{
    return AdminEmpresaScope::filtrarEmpresas(
            Empresa::query()
        )
        ->orderBy('razon_social')
        ->get([
            'id',
            'rfc',
            'razon_social',
            'ambiente',
            'activo',
            'aprobado_sandbox',
            'aprobado_productivo',
            'ultimo_login_ok_en',
            'ultima_prueba_webhook_en',
        ]);
}

    private function queueEstado(): array
    {
        $jobsPendientes = DB::table('jobs')->count();
        $failedJobs = DB::table('failed_jobs')->count();

        $jobsFase3 = DB::table('jobs')
            ->where('payload', 'like', '%ProcessBusquedaFase3Job%')
            ->count();

        return [
            'jobs_pendientes' => $jobsPendientes,
            'failed_jobs' => $failedJobs,
            'jobs_fase3_programados' => $jobsFase3,
        ];
    }

    private function saludSistema(): array
    {
        $erroresHoy = AdminEmpresaScope::filtrarPorEmpresaId(
            PuiLog::whereDate('created_at', today())
                ->whereNotNull('error')
        )->count();

        $failedJobs = DB::table('failed_jobs')->count();
        $jobsPendientes = DB::table('jobs')->count();

        $latenciaPromedio = AdminEmpresaScope::filtrarPorEmpresaId(
            PuiLog::whereDate('created_at', today())
        )->avg('duracion_ms') ?? 0;

        $color = 'green';
        $label = 'SALUDABLE';
        $score = 100;

        if ($failedJobs > 0) {
            $color = 'red';
            $label = 'CRÍTICO';
            $score -= 50;
        }

        if ($erroresHoy >= 5) {
            $color = 'red';
            $label = 'CRÍTICO';
            $score -= 30;
        }

        if ($jobsPendientes > 20 && $color !== 'red') {
            $color = 'yellow';
            $label = 'SATURADO';
            $score -= 20;
        }

        if ($latenciaPromedio > 2000 && $color === 'green') {
            $color = 'yellow';
            $label = 'LENTO';
            $score -= 10;
        }

        return [
            'color' => $color,
            'label' => $label,
            'score' => max($score, 0),
            'errores_hoy' => $erroresHoy,
            'failed_jobs' => $failedJobs,
            'jobs_pendientes' => $jobsPendientes,
            'latencia_ms' => round($latenciaPromedio, 2),
        ];
    }

    private function alertasSistema(): array
    {
        $alertas = [];

        $failedJobs = DB::table('failed_jobs')->count();
        if ($failedJobs > 0) {
            $alertas[] = [
                'tipo' => 'error',
                'titulo' => 'Jobs fallidos detectados',
                'mensaje' => "Existen {$failedJobs} jobs fallidos en cola.",
            ];
        }

        $erroresHoy = AdminEmpresaScope::filtrarPorEmpresaId(
            PuiLog::whereDate('created_at', today())
                ->whereNotNull('error')
        )->count();
        if ($erroresHoy > 0) {
            $alertas[] = [
                'tipo' => 'warning',
                'titulo' => 'Errores registrados hoy',
                'mensaje' => "Se detectaron {$erroresHoy} errores en logs del día.",
            ];
        }

        $reportesActivosSinMonitoreo = AdminEmpresaScope::filtrarPorEmpresaId(
        PuiReporte::whereNull('baja_en')
                ->whereIn('estatus', [
                    'activo',
                    'fase_1_completada',
                    'fase_2_completada'
                ])
        )->count();

        if ($reportesActivosSinMonitoreo > 0) {
            $alertas[] = [
                'tipo' => 'info',
                'titulo' => 'Reportes pendientes de estabilizar',
                'mensaje' => "Hay {$reportesActivosSinMonitoreo} reportes que aún no llegan a monitoreo continuo.",
            ];
        }

        $latenciaPromedio = AdminEmpresaScope::filtrarPorEmpresaId(
            PuiLog::whereDate('created_at', today())
        )->avg('duracion_ms') ?? 0;

        if ($latenciaPromedio > 2000) {
            $alertas[] = [
                'tipo' => 'warning',
                'titulo' => 'Latencia alta',
                'mensaje' => "Tiempo promedio de respuesta: " . round($latenciaPromedio, 2) . " ms.",
            ];
        }

        $jobsPendientes = DB::table('jobs')->count();
        if ($jobsPendientes > 50) {
            $alertas[] = [
                'tipo' => 'error',
                'titulo' => 'Cola saturada',
                'mensaje' => "Existen {$jobsPendientes} jobs pendientes.",
            ];
        }

        if (empty($alertas)) {
            $alertas[] = [
                'tipo' => 'success',
                'titulo' => 'Sistema estable',
                'mensaje' => 'No se detectaron alertas críticas en este momento.',
            ];
        }

        return $alertas;
    }
}