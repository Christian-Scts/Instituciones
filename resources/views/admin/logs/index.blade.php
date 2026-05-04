@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-bold mb-6">Logs</h2>

    <form method="GET" class="bg-white rounded-2xl shadow p-4 mb-6 grid grid-cols-1 md:grid-cols-5 gap-4">
        <select name="empresa_id" class="border rounded-lg p-2">
            <option value="">Todas las empresas</option>
            @foreach($empresas as $empresa)
                <option value="{{ $empresa->id }}" @selected(request('empresa_id') == $empresa->id)>
                    {{ $empresa->razon_social }}
                </option>
            @endforeach
        </select>

        <input type="text" name="endpoint" value="{{ request('endpoint') }}" placeholder="Endpoint" class="border rounded-lg p-2">
        <input type="text" name="id_busqueda" value="{{ request('id_busqueda') }}" placeholder="ID búsqueda" class="border rounded-lg p-2">
        <input type="text" name="http_code" value="{{ request('http_code') }}" placeholder="HTTP code" class="border rounded-lg p-2">
        <button class="bg-blue-600 text-white rounded-lg px-4 py-2">Filtrar</button>
    </form>

    <div class="bg-white rounded-2xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="p-3 text-left">Fecha</th>
                    <th class="p-3 text-left">Empresa</th>
                    <th class="p-3 text-left">Endpoint</th>
                    <th class="p-3 text-left">ID búsqueda</th>
                    <th class="p-3 text-left">HTTP</th>
                    <th class="p-3 text-left">Error</th>
                    <th class="p-3 text-left">Acción</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                    <tr class="border-t">
                        <td class="p-3">{{ $log->created_at }}</td>
                        <td class="p-3">{{ $log->empresa?->razon_social }}</td>
                        <td class="p-3">
                            {{ preg_replace('/[^A-Za-z0-9\/\-_\.]/', '', (string) $log->endpoint) }}
                        </td>
                        <td class="p-3">{{ $log->id_busqueda }}</td>
                        <td class="p-3">{{ $log->http_code }}</td>
                        <td class="p-3">
                            {{ \Illuminate\Support\Str::limit(strip_tags((string) $log->error), 40) }}
                        </td>
                        <td class="p-3">
                            <a href="{{ route('admin.logs.show', $log) }}" class="text-blue-600">Ver</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $logs->links() }}</div>
@endsection