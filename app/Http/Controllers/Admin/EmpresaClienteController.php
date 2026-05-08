<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmpresaClienteStoreRequest;
use App\Http\Requests\EmpresaClienteUpdateRequest;
use App\Jobs\ImportEmpresaClientesJob;
use App\Models\Empresa;
use App\Models\EmpresaCliente;
use App\Models\EmpresaClienteImportacion;
use Illuminate\Support\Facades\Storage;
use App\Support\AdminEmpresaScope;
use Illuminate\Http\Request;

class EmpresaClienteController extends Controller
{
    public function index(Empresa $empresa, Request $request)
    {
        AdminEmpresaScope::validarEmpresa($empresa->id);
        $query = EmpresaCliente::where('empresa_id', $empresa->id);

        if ($search = trim((string) $request->input('q'))) {
            $query->where(function ($q) use ($search) {
                $q->where('curp', 'like', "%{$search}%")
                  ->orWhere('nombre', 'like', "%{$search}%")
                  ->orWhere('primer_apellido', 'like', "%{$search}%")
                  ->orWhere('segundo_apellido', 'like', "%{$search}%");
            });
        }

        $clientes = $query->orderBy('primer_apellido')
            ->orderBy('segundo_apellido')
            ->orderBy('nombre')
            ->paginate(20)
            ->withQueryString();

        return view('admin.empresas.clientes.index', compact('empresa', 'clientes'));
    }

    public function create(Empresa $empresa)
    {
        AdminEmpresaScope::validarEmpresa($empresa->id);
        return view('admin.empresas.clientes.create', compact('empresa'));
    }

    public function store(Empresa $empresa, EmpresaClienteStoreRequest $request)
    {
        AdminEmpresaScope::validarEmpresa($empresa->id);
        $data = $request->validated();
        $data['empresa_id'] = $empresa->id;

        EmpresaCliente::create($data);

        return redirect()
            ->route('admin.empresas.clientes.index', $empresa)
            ->with('success', 'Cliente registrado correctamente.');
    }

    public function edit(Empresa $empresa, EmpresaCliente $cliente)
    {
        AdminEmpresaScope::validarEmpresa($empresa->id);
        abort_unless($cliente->empresa_id === $empresa->id, 404);

        return view('admin.empresas.clientes.edit', compact('empresa', 'cliente'));
    }

    public function update(Empresa $empresa, EmpresaCliente $cliente, EmpresaClienteUpdateRequest $request)
    {
        AdminEmpresaScope::validarEmpresa($empresa->id);
        abort_unless($cliente->empresa_id === $empresa->id, 404);

        $cliente->update($request->validated());

        return redirect()
            ->route('admin.empresas.clientes.index', $empresa)
            ->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy(Empresa $empresa, EmpresaCliente $cliente)
    {
        AdminEmpresaScope::validarEmpresa($empresa->id);
        abort_unless($cliente->empresa_id === $empresa->id, 404);

        $cliente->delete();

        return back()->with('success', 'Cliente eliminado correctamente.');
    }

    public function importForm(Empresa $empresa)
    {
        AdminEmpresaScope::validarEmpresa($empresa->id);
        return view('admin.empresas.clientes.import', compact('empresa'));
    }

    public function import(Empresa $empresa, Request $request)
{
    AdminEmpresaScope::validarEmpresa($empresa->id);
    $request->validate([
        'archivo' => ['required', 'file', 'mimes:csv,txt,xlsx,xls'],
        'sobrescribir' => ['nullable', 'boolean'],
    ]);

    $file = $request->file('archivo');
    $path = $file->store('imports/clientes');

    $importacion = EmpresaClienteImportacion::create([
        'empresa_id' => $empresa->id,
        'archivo_original' => $file->getClientOriginalName(),
        'archivo_path' => $path,
        'extension' => strtolower($file->getClientOriginalExtension()),
        'sobrescribir' => $request->boolean('sobrescribir'),
        'estatus' => 'pendiente',
    ]);

    ImportEmpresaClientesJob::dispatch($importacion->id);

    return redirect()
        ->route('admin.empresas.clientes.index', $empresa)
        ->with('success', 'Archivo enviado a cola para importación.');
}

public function importsIndex(Empresa $empresa)
{
    AdminEmpresaScope::validarEmpresa($empresa->id);
    $importaciones = EmpresaClienteImportacion::where('empresa_id', $empresa->id)
        ->latest()
        ->paginate(20);

    return view('admin.empresas.clientes.imports', compact('empresa', 'importaciones'));
}
}