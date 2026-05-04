@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-bold mb-6">Detalle de reporte</h2>

    <div class="bg-white rounded-2xl shadow p-6 mb-6 space-y-3">
        <div><strong>Empresa:</strong> {{ $reporte->empresa?->razon_social }}</div>
        <div><strong>ID búsqueda:</strong> {{ $reporte->id_busqueda }}</div>
        <div><strong>CURP:</strong> {{ $reporte->curp }}</div>
        <div><strong>Fase actual:</strong> {{ $reporte->fase_actual }}</div>
        <div><strong>Estatus:</strong> {{ $reporte->estatus }}</div>
        <div><strong>Alta:</strong> {{ $reporte->alta_en }}</div>
        <div><strong>Búsqueda histórica finalizada:</strong> {{ $reporte->busqueda_historica_finalizada_en }}</div>
        <div><strong>Última búsqueda continua:</strong> {{ $reporte->ultima_busqueda_continua_en }}</div>
    </div>

    <div class="bg-white rounded-2xl shadow p-6">
        <h3 class="font-bold mb-4">Coincidencias</h3>

        @forelse($reporte->coincidencias as $coincidencia)
            <div class="border rounded-xl p-4 mb-4">
                <div><strong>Fase:</strong> {{ $coincidencia->fase_busqueda }}</div>
                <div><strong>HTTP:</strong> {{ $coincidencia->http_code }}</div>
                <div><strong>Fecha:</strong> {{ $coincidencia->notificado_en }}</div>
                <pre class="bg-slate-100 p-4 rounded mt-3 text-xs overflow-auto">{{ json_encode($coincidencia->payload_enviado, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
            </div>
        @empty
            <p>No hay coincidencias registradas.</p>
        @endforelse
    </div>
@endsection