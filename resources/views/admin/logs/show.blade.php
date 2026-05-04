@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-bold mb-6">Detalle de log</h2>

    <div class="bg-white rounded-2xl shadow p-6 space-y-4">
        <div><strong>Empresa:</strong> {{ $log->empresa?->razon_social }}</div>
        <div><strong>Endpoint:</strong> {{ $log->endpoint }}</div>
        <div><strong>Método:</strong> {{ $log->metodo }}</div>
        <div><strong>ID búsqueda:</strong> {{ $log->id_busqueda }}</div>
        <div><strong>Fase:</strong> {{ $log->fase_busqueda }}</div>
        <div><strong>HTTP:</strong> {{ $log->http_code }}</div>
        <div><strong>Error:</strong> {{ $log->error }}</div>

        <div>
            <h3 class="font-semibold mb-2">Headers</h3>
            <pre class="bg-slate-100 p-4 rounded overflow-auto text-xs">{{ $log->headers_json }}</pre>
        </div>

        <div>
            <h3 class="font-semibold mb-2">Body</h3>
            <pre class="bg-slate-100 p-4 rounded overflow-auto text-xs">{{ $log->body_json }}</pre>
        </div>

        <div>
            <h3 class="font-semibold mb-2">Respuesta</h3>
            <pre class="bg-slate-100 p-4 rounded overflow-auto text-xs">{{ $log->response_json }}</pre>
        </div>
    </div>
@endsection