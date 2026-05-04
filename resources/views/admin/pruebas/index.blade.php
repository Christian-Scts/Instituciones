@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-bold mb-6">Pruebas de conectividad</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        @foreach($empresas as $empresa)
            <div class="bg-white rounded-2xl shadow p-5">
                <h3 class="font-bold text-lg">{{ $empresa->razon_social }}</h3>
                <p class="text-sm text-slate-500 mb-4">{{ $empresa->rfc }}</p>

                <div class="flex gap-2">
                    
                    <form method="POST" action="{{ route('admin.pruebas.loginPui', $empresa) }}">
                        @csrf
                        <button class="px-4 py-2 bg-slate-700 text-white rounded-lg">Probar login</button>
                    </form>
                    
                    <form method="POST" action="{{ route('admin.pruebas.webhook', $empresa) }}">
                        @csrf
                        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">Probar webhook</button>
                    </form>
                    
                    <form action="{{ route('admin.pruebas.desactivar', $empresa) }}" method="POST" class="mt-3 space-y-2">
                        @csrf
                    
                        <select name="id_busqueda" class="w-full rounded-lg border p-2" required>
                            <option value="">Selecciona un reporte activo</option>
                            @foreach($empresa->reportes as $reporte)
                                <option value="{{ preg_replace('/[^A-Za-z0-9\-_]/', '', (string) $reporte->id_busqueda) }}">
                                {{ preg_replace('/[^A-Za-z0-9\-_]/', '', (string) $reporte->id_busqueda) }} — {{ e($reporte->estatus) }}
                            </option>
                            @endforeach
                        </select>
                    
                        <input
                            type="text"
                            name="curp"
                            value="TEST000101HDFABC01"
                            class="w-full rounded-lg border p-2"
                            required
                        >
                    
                        <button type="submit" class="px-4 py-2 rounded-lg bg-red-600 text-white font-semibold">
                            Desactivar reporte
                        </button>
                    </form>

                    
                </div>
            </div>
        @endforeach
    </div>

    <div class="bg-white rounded-2xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="p-3 text-left">Fecha</th>
                    <th class="p-3 text-left">Empresa</th>
                    <th class="p-3 text-left">Tipo</th>
                    <th class="p-3 text-left">URL</th>
                    <th class="p-3 text-left">HTTP</th>
                    <th class="p-3 text-left">Exitosa</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pruebas as $prueba)
                    <tr class="border-t">
                        <td class="p-3">{{ $prueba->ejecutada_en }}</td>
                        <td class="p-3">{{ $prueba->empresa?->razon_social }}</td>
                        <td class="p-3">{{ $prueba->tipo_prueba }}</td>
                        <td class="p-3 break-all">{{ e($prueba->url) }}</td>
                        <td class="p-3">{{ $prueba->http_code }}</td>
                        <td class="p-3">{{ $prueba->exitosa ? 'Sí' : 'No' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $pruebas->links() }}</div>
@endsection