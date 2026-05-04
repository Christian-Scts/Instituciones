@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-slate-900">Editar permiso</h2>
        <p class="text-slate-500 mt-1">{{ $permission->slug }}</p>
    </div>

    <form method="POST" action="{{ route('admin.permissions.update', $permission) }}"
          class="bg-white rounded-2xl shadow p-6 space-y-6">
        @csrf
        @method('PUT')

        @include('admin.permissions.partials.form', [
            'permission' => $permission
        ])

        <div class="flex gap-3">
            <button class="px-4 py-2 bg-blue-700 text-white rounded-xl font-semibold">
                Guardar cambios
            </button>

            <a href="{{ route('admin.permissions.index') }}"
               class="px-4 py-2 bg-slate-200 text-slate-700 rounded-xl font-semibold">
                Cancelar
            </a>
        </div>
    </form>
@endsection