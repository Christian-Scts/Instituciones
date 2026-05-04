@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl font-bold text-slate-900">Usuarios</h2>
            <p class="text-slate-500 mt-1">Administración de accesos al panel PUI</p>
        </div>

        <a href="{{ route('admin.usuarios.create') }}"
           class="px-4 py-2 bg-blue-700 text-white rounded-xl font-semibold hover:bg-blue-800">
            Nuevo usuario
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow p-5 mb-6">
        <form method="GET" class="flex gap-3">
            <input type="text"
                   name="buscar"
                   value="{{ request('buscar') }}"
                   placeholder="Buscar por nombre o correo"
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
                    <th class="p-3 text-left">Usuario</th>
                    <th class="p-3 text-left">Empresa</th>
                    <th class="p-3 text-left">Roles</th>
                    <th class="p-3 text-left">Estatus</th>
                    <th class="p-3 text-right">Acciones</th>
                </tr>
            </thead>

            <tbody>
                @forelse($usuarios as $usuario)
                    <tr class="border-t">
                        <td class="p-3">
                            <div class="font-semibold">{{ $usuario->name }}</div>
                            <div class="text-slate-500 text-xs">{{ $usuario->email }}</div>
                        </td>

                        <td class="p-3">
                            @if($usuario->empresa)
                                {{ $usuario->empresa->razon_social }}
                            @else
                                <span class="text-slate-400">Global</span>
                            @endif
                        </td>

                        <td class="p-3">
                            <div class="flex flex-wrap gap-1">
                                @foreach($usuario->roles as $role)
                                    <span class="px-2 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-semibold">
                                        {{ $role->nombre }}
                                    </span>
                                @endforeach
                            </div>
                        </td>

                        <td class="p-3">
                            @if($usuario->activo)
                                <span class="px-2 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                                    Activo
                                </span>
                            @else
                                <span class="px-2 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">
                                    Inactivo
                                </span>
                            @endif
                        </td>

                        <td class="p-3 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.usuarios.edit', $usuario) }}"
                                   class="px-3 py-2 bg-amber-500 text-white rounded-lg text-xs font-semibold">
                                    Editar
                                </a>

                                <form method="POST"
                                      action="{{ route('admin.usuarios.destroy', $usuario) }}"
                                      onsubmit="return confirm('¿Eliminar este usuario?')">
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
                    <tr class="border-t">
                        <td colspan="5" class="p-6 text-center text-slate-500">
                            No hay usuarios registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-5">
        {{ $usuarios->links() }}
    </div>
@endsection