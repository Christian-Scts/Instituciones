@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-3xl font-bold">Elementos del menú</h2>
        <p class="text-slate-500">Administra accesos, rutas y permisos del sidebar</p>
    </div>

    <a href="{{ route('admin.menu-elementos.create') }}"
       class="px-4 py-2 bg-blue-700 text-white rounded-xl font-semibold">
        Nuevo elemento
    </a>
</div>

<div class="bg-white rounded-2xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50">
            <tr>
                <th class="p-3 text-left">Orden</th>
                <th class="p-3 text-left">Elemento</th>
                <th class="p-3 text-left">Grupo</th>
                <th class="p-3 text-left">Ruta</th>
                <th class="p-3 text-left">Permiso</th>
                <th class="p-3 text-left">Estatus</th>
                <th class="p-3 text-right">Acciones</th>
            </tr>
        </thead>

        <tbody>
            @forelse($elementos as $elemento)
                <tr class="border-t">
                    <td class="p-3">{{ $elemento->orden }}</td>
                    <td class="p-3">
                        <div class="font-semibold">{{ $elemento->titulo }}</div>
                        <div class="text-xs text-slate-500">{{ $elemento->slug }}</div>
                    </td>
                    <td class="p-3">{{ $elemento->grupo?->nombre }}</td>
                    <td class="p-3">{{ $elemento->route_name ?? $elemento->url }}</td>
                    <td class="p-3">{{ $elemento->permission_slug }}</td>
                    <td class="p-3">{{ $elemento->activo ? 'Activo' : 'Inactivo' }}</td>
                    <td class="p-3 text-right">
                        <a href="{{ route('admin.menu-elementos.edit', $elemento) }}"
                           class="px-3 py-2 bg-amber-500 text-white rounded-lg text-xs font-semibold">
                            Editar
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="p-6 text-center text-slate-500">
                        Sin elementos registrados.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-5">{{ $elementos->links() }}</div>
@endsection