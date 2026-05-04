@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-slate-900">Panel de empresa</h2>
        <p class="text-slate-500 mt-1">{{ $empresa->razon_social }} ({{ $empresa->rfc }})</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-6 gap-4 mb-8">
        <div class="bg-white rounded-2xl shadow p-5"><div class="text-sm text-slate-500">Clientes</div><div class="text-3xl font-bold">{{ $stats['clientes'] }}</div></div>
        <div class="bg-white rounded-2xl shadow p-5"><div class="text-sm text-slate-500">Reportes</div><div class="text-3xl font-bold">{{ $stats['reportes'] }}</div></div>
        <div class="bg-white rounded-2xl shadow p-5"><div class="text-sm text-slate-500">Activos</div><div class="text-3xl font-bold">{{ $stats['reportes_activos'] }}</div></div>
        <div class="bg-white rounded-2xl shadow p-5"><div class="text-sm text-slate-500">Coincidencias</div><div class="text-3xl font-bold">{{ $stats['coincidencias'] }}</div></div>
        <div class="bg-white rounded-2xl shadow p-5"><div class="text-sm text-slate-500">Logs</div><div class="text-3xl font-bold">{{ $stats['logs'] }}</div></div>
        <div class="bg-white rounded-2xl shadow p-5"><div class="text-sm text-slate-500">Tokens activos</div><div class="text-3xl font-bold">{{ $stats['tokens_activos'] }}</div></div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl shadow p-5">
            <h3 class="font-bold mb-4">Últimos clientes</h3>
            <div class="space-y-2">
                @foreach($ultimosClientes as $cliente)
                    <div class="border-b pb-2">{{ $cliente->curp }} - {{ $cliente->nombre }} {{ $cliente->primer_apellido }}</div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow p-5">
            <h3 class="font-bold mb-4">Últimos reportes</h3>
            <div class="space-y-2">
                @foreach($ultimosReportes as $reporte)
                    <div class="border-b pb-2">{{ $reporte->id_busqueda }} - {{ $reporte->estatus }}</div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow p-5">
            <h3 class="font-bold mb-4">Últimas coincidencias</h3>
            <div class="space-y-2">
                @foreach($ultimasCoincidencias as $coincidencia)
                    <div class="border-b pb-2">{{ $coincidencia->id_busqueda }} - Fase {{ $coincidencia->fase_busqueda }} - HTTP {{ $coincidencia->http_code }}</div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow p-5">
            <h3 class="font-bold mb-4">Últimos logs</h3>
            <div class="space-y-2">
                @foreach($ultimosLogs as $log)
                    <div class="border-b pb-2">{{ $log->endpoint }} - HTTP {{ $log->http_code }}</div>
                @endforeach
            </div>
        </div>
    </div>
@endsection