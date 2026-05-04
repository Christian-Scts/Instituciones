@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-slate-900">Editar usuario</h2>
        <p class="text-slate-500 mt-1">{{ $usuario->email }}</p>
    </div>

    <form method="POST" action="{{ route('admin.usuarios.update', $usuario) }}"
          class="bg-white rounded-2xl shadow p-6 space-y-6">
        @csrf
        @method('PUT')

        @include('admin.usuarios.partials.form', [
            'usuario' => $usuario,
            'rolesAsignados' => $rolesAsignados
        ])

        <div class="flex gap-3">
            <button class="px-4 py-2 bg-blue-700 text-white rounded-xl font-semibold">
                Guardar cambios
            </button>

            <a href="{{ route('admin.usuarios.index') }}"
               class="px-4 py-2 bg-slate-200 text-slate-700 rounded-xl font-semibold">
                Cancelar
            </a>
        </div>
    </form>
@endsection