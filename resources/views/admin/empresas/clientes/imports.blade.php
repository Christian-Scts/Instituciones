@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl font-bold text-slate-900">Bitácora de importaciones</h2>
            <p class="text-slate-500 mt-1">{{ $empresa->razon_social }} ({{ $empresa->rfc }})</p>
        </div>

        <a href="{{ route('admin.empresas.clientes.import.form', $empresa) }}"
           class="px-4 py-2 bg-blue-600 text-white rounded-lg">
            Nueva importación
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="p-3 text-left">Fecha</th>
                    <th class="p-3 text-left">Archivo</th>
                    <th class="p-3 text-left">Estado</th>
                    <th class="p-3 text-left">Total</th>
                    <th class="p-3 text-left">OK</th>
                    <th class="p-3 text-left">Error</th>
                </tr>
            </thead>
            <tbody>
                @forelse($importaciones as $importacion)
                    <tr class="border-t align-top">
                        <td class="p-3">{{ $importacion->created_at }}</td>
                        <td class="p-3">{{ $importacion->archivo_original }}</td>
                        <td class="p-3">{{ $importacion->estatus }}</td>
                        <td class="p-3">{{ $importacion->total_filas }}</td>
                        <td class="p-3">{{ $importacion->filas_ok }}</td>
                        <td class="p-3">
                            {{ $importacion->filas_error }}

                            @if(!empty($importacion->errores_json))
                                <details class="mt-2">
                                    <summary class="cursor-pointer text-red-600">Ver errores</summary>
                                    <div class="mt-2 space-y-2">
                                        @foreach($importacion->errores_json as $error)
                                            <div class="rounded-xl bg-red-50 border border-red-200 p-3 text-xs">
                                                <div><strong>Fila:</strong> {{ $error['fila'] ?? '' }}</div>
                                                <div><strong>CURP:</strong> {{ $error['curp'] ?? '' }}</div>
                                                <div>
                                                    <strong>Errores:</strong>
                                                    <ul class="list-disc ml-5">
                                                        @foreach(($error['errores'] ?? []) as $mensaje)
                                                            <li>{{ $mensaje }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </details>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr class="border-t">
                        <td colspan="6" class="p-3 text-slate-500">Sin importaciones registradas</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $importaciones->links() }}
    </div>
@endsection