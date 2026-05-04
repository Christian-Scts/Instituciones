@extends('layouts.app')

@section('content')
    <h2 class="text-3xl font-bold text-slate-900 mb-6">Importar clientes</h2>

    <div class="bg-white rounded-2xl shadow p-6 max-w-2xl">
        <form action="{{ route('admin.empresas.clientes.import', $empresa) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Archivo CSV o Excel</label>
                <input type="file" name="archivo" class="w-full rounded-xl border border-slate-300 px-4 py-3">
            </div>

            <label class="flex items-center gap-3">
                <input type="checkbox" name="sobrescribir" value="1" class="rounded border-slate-300">
                <span class="text-sm text-slate-700">Sobrescribir registros existentes por CURP + fecha_referencia</span>
            </label>

            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                Importar
            </button>
        </form>
    </div>
@endsection