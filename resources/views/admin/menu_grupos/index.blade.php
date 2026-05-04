@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-3xl font-bold">Grupos del menú</h2>
        <p class="text-slate-500">Administra las secciones principales del sidebar</p>
    </div>

    <a href="{{ route('admin.menu-grupos.create') }}"
       class="px-4 py-2 bg-blue-700 text-white rounded-xl font-semibold">
        Nuevo grupo
    </a>
</div>

<div class="bg-white rounded-2xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50">
            <tr>
                <th class="p-3 text-left">Orden</th>
                <th class="p-3 text-left">Grupo</th>
                <th class="p-3 text-left">Slug</th>
                <th class="p-3 text-left">Elementos</th>
                <th class="p-3 text-left">Estatus</th>
                <th class="p-3 text-right">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($grupos as $grupo)
                <tr class="border-t">
                    <td class="p-3">{{ $grupo->orden }}</td>
                    <td class="p-3 font-semibold">{{ $grupo->nombre }}</td>
                    <td class="p-3">{{ $grupo->slug }}</td>
                    <td class="p-3">{{ $grupo->elementos_count }}</td>
                    <td class="p-3">
                        {{ $grupo->activo ? 'Activo' : 'Inactivo' }}
                    </td>
                    <td class="p-3 text-right">
                        <a href="{{ route('admin.menu-grupos.edit', $grupo) }}"
                           class="px-3 py-2 bg-amber-500 text-white rounded-lg text-xs font-semibold">
                            Editar
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="p-6 text-center text-slate-500">Sin grupos registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-5">{{ $grupos->links() }}</div>
@endsection