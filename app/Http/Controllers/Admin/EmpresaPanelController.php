<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\EmpresaCliente;
use App\Models\PuiCoincidencia;
use App\Models\PuiLog;
use App\Models\PuiReporte;
use App\Models\PuiToken;
use App\Support\AdminEmpresaScope;

class EmpresaPanelController extends Controller
{
    public function show(Empresa $empresa)
    {
        AdminEmpresaScope::validarEmpresa($empresa->id);
        $stats = [
            'clientes' => EmpresaCliente::where('empresa_id', $empresa->id)->count(),
            'reportes' => PuiReporte::where('empresa_id', $empresa->id)->count(),
            'reportes_activos' => PuiReporte::where('empresa_id', $empresa->id)->whereNull('baja_en')->count(),
            'coincidencias' => PuiCoincidencia::where('empresa_id', $empresa->id)->count(),
            'logs' => PuiLog::where('empresa_id', $empresa->id)->count(),
            'tokens_activos' => PuiToken::where('empresa_id', $empresa->id)
                ->where('estatus', 'activo')
                ->where('expira_en', '>', now())
                ->count(),
        ];

        $ultimosClientes = EmpresaCliente::where('empresa_id', $empresa->id)
            ->latest()
            ->limit(10)
            ->get();

        $ultimosReportes = PuiReporte::where('empresa_id', $empresa->id)
            ->latest()
            ->limit(10)
            ->get();

        $ultimasCoincidencias = PuiCoincidencia::where('empresa_id', $empresa->id)
            ->latest()
            ->limit(10)
            ->get();

        $ultimosLogs = PuiLog::where('empresa_id', $empresa->id)
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.empresas.panel', compact(
            'empresa',
            'stats',
            'ultimosClientes',
            'ultimosReportes',
            'ultimasCoincidencias',
            'ultimosLogs'
        ));
    }

      public function miPanel()
{
    $empresaId = session('admin_user.empresa_id');

    if (!$empresaId) {
        return redirect()
            ->route('admin.empresas.index')
            ->with('error', 'Tu usuario no está vinculado a una empresa específica.');
    }

    $empresa = Empresa::findOrFail($empresaId);

    return $this->show($empresa);
}
}