@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl font-bold text-slate-900">Permisos</h2>
            <p class="text-slate-500 mt-1">Catálogo de permisos usados por roles, rutas y menú</p>
        </div>

        <a href="{{ route('admin.permissions.create') }}"
           class="px-4 py-2 bg-blue-700 text-white rounded-xl font-semibold hover:bg-blue-800">
            Nuevo permiso
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow p-5 mb-6">
        <form method="GET" class="flex gap-3">
            <input type="text"
                   name="buscar"
                   value="{{ request('buscar') }}"
                   placeholder="Buscar por nombre, slug o grupo"
                   class="w-full rounded-xl border border-slate-300 px-4 py-3">

            <button class="px-4 py-2 bg-slate-800 text-white rounded-xl">
                Buscar
            </button>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="p-3 text-left">Permiso</th>
                    <th class="p-3 text-left">Slug</th>
                    <th class="p-3 text-left">Grupo</th>
                    <th class="p-3 text-left">Estatus</th>
                    <th class="p-3 text-right">Acciones</th>
                </tr>
            </thead>

            <tbody>
                @forelse($permissions as $permission)
                    <tr class="border-t">
                        <td class="p-3">
                            <div class="font-semibold">{{ $permission->nombre }}</div>
                            <div class="text-xs text-slate-500">{{ $permission->descripcion }}</div>
                        </td>

                        <td class="p-3">
                            <span class="px-2 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs font-semibold">
                                {{ $permission->slug }}
                            </span>
                        </td>

                        <td class="p-3">{{ $permission->grupo ?? 'General' }}</td>

                        <td class="p-3">
                            @if($permission->activo)
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                    Activo
                                </span>
                            @else
                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">
                                    Inactivo
                                </span>
                            @endif
                        </td>

                        <td class="p-3 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.permissions.edit', $permission) }}"
                                   class="px-3 py-2 bg-amber-500 text-white rounded-lg text-xs font-semibold">
                                    Editar
                                </a>

                                <form method="POST"
                                      action="{{ route('admin.permissions.destroy', $permission) }}"
                                      onsubmit="return confirm('¿Eliminar este permiso?')">
                                    @csrf
                                    @method('DELETE')

                                    <button class="px-3 py-2 bg-red-600 text-white rounded-lg text-xs font-semibold">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-6 text-center text-slate-500">
                            No hay permisos registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-5">
        {{ $permissions->links() }}
    </div>
@endsection