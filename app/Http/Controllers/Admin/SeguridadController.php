<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\PuiEvidenciaSeguridad;
use Illuminate\Http\Request;

class SeguridadController extends Controller
{
    public function index()
    {
        $empresas = Empresa::orderBy('razon_social')->get();
        $evidencias = PuiEvidenciaSeguridad::with('empresa')
            ->latest('fecha_ejecucion')
            ->paginate(20);

        return view('admin.seguridad.index', compact('empresas', 'evidencias'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'empresa_id' => 'required|exists:empresas,id',
            'tipo_reporte' => 'required|in:SAST,DAST,SCA',
            'herramienta' => 'nullable|string|max:255',
            'version_herramienta' => 'nullable|string|max:100',
            'ambiente_ejecucion' => 'required|in:sandbox,productivo',
            'fecha_ejecucion' => 'nullable|date',
            'urls_validadas' => 'nullable|string',
            'resultado_global' => 'nullable|string|max:50',
            'archivo' => 'nullable|file|mimes:pdf,zip,json,txt,csv',
            'observaciones' => 'nullable|string',
        ]);

        if ($request->hasFile('archivo')) {
            $data['archivo'] = $request->file('archivo')->store('pui/seguridad', 'public');
        }

        $data['urls_validadas'] = $request->filled('urls_validadas')
            ? array_map('trim', preg_split('/\r\n|\r|\n/', $request->urls_validadas))
            : null;

        PuiEvidenciaSeguridad::create($data);

        return back()->with('success', 'Evidencia cargada correctamente.');
    }
}