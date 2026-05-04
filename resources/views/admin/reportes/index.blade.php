@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-bold mb-6">Reportes</h2>

    <form method="GET" class="bg-white rounded-2xl shadow p-4 mb-6 grid grid-cols-1 md:grid-cols-5 gap-4">
        <select name="empresa_id" class="border rounded-lg p-2">
            <option value="">Todas las empresas</option>
            @foreach($empresas as $empresa)
                <option value="{{ $empresa->id }}" @selected(request('empresa_id') == $empresa->id)>
                    {{ $empresa->razon_social }}
                </option>
            @endforeach
        </select>

        <input type="text" name="curp" value="{{ request('curp') }}" placeholder="CURP" class="border rounded-lg p-2">
        <input type="text" name="estatus" value="{{ request('estatus') }}" placeholder="Estatus" class="border rounded-lg p-2">
        <input type="text" name="fase_actual" value="{{ request('fase_actual') }}" placeholder="Fase" class="border rounded-lg p-2">
        <button class="bg-blue-600 text-white rounded-lg px-4 py-2">Filtrar</button>
    </form>

    <div class="bg-white rounded-2xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="p-3 text-left">Empresa</th>
                    <th class="p-3 text-left">ID búsqueda</th>
                    <th class="p-3 text-left">CURP</th>
                    <th class="p-3 text-left">Fase</th>
                    <th class="p-3 text-left">Estatus</th>
                    <th class="p-3 text-left">Coincidencias</th>
                    <th class="p-3 text-left">Acción</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reportes as $reporte)
                    <tr class="border-t">
                        <td class="p-3">{{ $reporte->empresa?->razon_social }}</td>
                        <td class="p-3">{{ $reporte->id_busqueda }}</td>
                        <td class="p-3">{{ $reporte->curp }}</td>
                        <td class="p-3">{{ $reporte->fase_actual }}</td>
                        <td class="p-3">{{ $reporte->estatus }}</td>
                        <td class="p-3">{{ $reporte->coincidencias->count() }}</td>
                        <td class="p-3">
                            <a href="{{ route('admin.reportes.show', $reporte) }}" class="text-blue-600">Ver</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $reportes->links() }}</div>
@endsection