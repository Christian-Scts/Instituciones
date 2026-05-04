@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl font-bold text-slate-900">Roles</h2>
            <p class="text-slate-500 mt-1">Administra roles y permisos del sistema</p>
        </div>

        <a href="{{ route('admin.roles.create') }}"
           class="px-4 py-2 bg-blue-700 text-white rounded-xl font-semibold hover:bg-blue-800">
            Nuevo rol
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="p-3 text-left">Rol</th>
                    <th class="p-3 text-left">Slug</th>
                    <th class="p-3 text-left">Usuarios</th>
                    <th class="p-3 text-left">Permisos</th>
                    <th class="p-3 text-left">Estatus</th>
                    <th class="p-3 text-right">Acciones</th>
                </tr>
            </thead>

            <tbody>
                @forelse($roles as $role)
                    <tr class="border-t">
                        <td class="p-3">
                            <div class="font-semibold">{{ $role->nombre }}</div>
                            <div class="text-xs text-slate-500">{{ $role->descripcion }}</div>
                        </td>

                        <td class="p-3">{{ $role->slug }}</td>
                        <td class="p-3">{{ $role->usuarios_count }}</td>
                        <td class="p-3">{{ $role->permissions_count }}</td>

                        <td class="p-3">
                            @if($role->activo)
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Activo</span>
                            @else
                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">Inactivo</span>
                            @endif
                        </td>

                        <td class="p-3 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.roles.edit', $role) }}"
                                   class="px-3 py-2 bg-amber-500 text-white rounded-lg text-xs font-semibold">
                                    Editar
                                </a>

                                <form method="POST"
                                      action="{{ route('admin.roles.destroy', $role) }}"
                                      onsubmit="return confirm('¿Eliminar este rol?')">
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
                        <td colspan="6" class="p-6 text-center text-slate-500">
                            No hay roles registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-5">
        {{ $roles->links() }}
    </div>
@endsection