<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\PuiLog;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $logs = PuiLog::with('empresa')
            ->when($request->filled('empresa_id'), fn ($q) => $q->where('empresa_id', $request->empresa_id))
            ->when($request->filled('endpoint'), fn ($q) => $q->where('endpoint', 'like', '%' . $request->endpoint . '%'))
            ->when($request->filled('id_busqueda'), fn ($q) => $q->where('id_busqueda', $request->id_busqueda))
            ->when($request->filled('http_code'), fn ($q) => $q->where('http_code', $request->http_code))
            ->latest()
            ->paginate(50)
            ->withQueryString();

        $empresas = Empresa::orderBy('razon_social')->get();

        return view('admin.logs.index', compact('logs', 'empresas'));
    }

    public function show(PuiLog $log)
    {
        $log->load('empresa');

        return view('admin.logs.show', compact('log'));
    }
}