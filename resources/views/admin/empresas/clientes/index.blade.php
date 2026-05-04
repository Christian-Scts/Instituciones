@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl font-bold text-slate-900">Clientes de empresa</h2>
            <p class="text-slate-500 mt-1">{{ $empresa->razon_social }} ({{ $empresa->rfc }})</p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('admin.empresas.clientes.import.form', $empresa) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                Importar
            </a>
            <a href="{{ route('admin.empresas.clientes.imports.index', $empresa) }}" class="px-4 py-2 bg-slate-700 text-white rounded-lg">
            Bitácora importaciones
        </a>
            <a href="{{ route('admin.empresas.clientes.create', $empresa) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                Nuevo cliente
            </a>
        </div>
    </div>

    <form method="GET" class="mb-4">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar por CURP o nombre"
            class="w-full md:w-96 rounded-xl border border-slate-300 px-4 py-3">
    </form>

    <div class="bg-white rounded-2xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="p-3 text-left">CURP</th>
                    <th class="p-3 text-left">Nombre</th>
                    <th class="p-3 text-left">Teléfono</th>
                    <th class="p-3 text-left">Correo</th>
                    <th class="p-3 text-left">Fecha ref.</th>
                    <th class="p-3 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clientes as $cliente)
                    <tr class="border-t">
                        <td class="p-3">{{ $cliente->curp }}</td>
                        <td class="p-3">
                            {{ trim(($cliente->nombre ?? '') . ' ' . ($cliente->primer_apellido ?? '') . ' ' . ($cliente->segundo_apellido ?? '')) }}
                        </td>
                        <td class="p-3">{{ $cliente->telefono }}</td>
                        <td class="p-3">{{ $cliente->correo }}</td>
                        <td class="p-3">{{ optional($cliente->fecha_referencia)->format('Y-m-d') }}</td>
                        <td class="p-3">
                            <div class="flex gap-2">
                                <a href="{{ route('admin.empresas.clientes.edit', [$empresa, $cliente]) }}" class="px-3 py-1 bg-blue-600 text-white rounded-lg">
                                    Editar
                                </a>
                                <form method="POST" action="{{ route('admin.empresas.clientes.destroy', [$empresa, $cliente]) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="px-3 py-1 bg-red-600 text-white rounded-lg"
                                        onclick="return confirm('¿Eliminar cliente?')">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr class="border-t">
                        <td colspan="6" class="p-3 text-slate-500">Sin clientes registrados</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $clientes->links() }}
    </div>
@endsection