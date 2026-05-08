<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\PuiReporte;
use App\Support\AdminEmpresaScope;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function index(Request $request)
{
    $empresaSesionId = AdminEmpresaScope::empresaId();

    $reportes = AdminEmpresaScope::filtrarPorEmpresaId(
            PuiReporte::with(['empresa', 'coincidencias'])
        )
        ->when(
            $request->filled('empresa_id') && !$empresaSesionId,
            fn ($q) => $q->where('empresa_id', $request->empresa_id)
        )
        ->when($request->filled('curp'), fn ($q) => $q->where('curp', 'like', '%' . strtoupper($request->curp) . '%'))
        ->when($request->filled('estatus'), fn ($q) => $q->where('estatus', $request->estatus))
        ->when($request->filled('fase_actual'), fn ($q) => $q->where('fase_actual', $request->fase_actual))
        ->latest()
        ->paginate(30)
        ->withQueryString();

    $empresas = AdminEmpresaScope::filtrarEmpresas(
        Empresa::query()
    )->orderBy('razon_social')->get();

    return view('admin.reportes.index', compact('reportes', 'empresas'));
}

    public function show(PuiReporte $reporte)
{
    AdminEmpresaScope::validarEmpresa($reporte->empresa_id);

    $reporte->load(['empresa', 'coincidencias']);

    return view('admin.reportes.show', compact('reporte'));
}
}