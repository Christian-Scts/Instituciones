@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-slate-900">Nuevo permiso</h2>
        <p class="text-slate-500 mt-1">Crea un permiso para rutas, botones o elementos del menú</p>
    </div>

    <form method="POST" action="{{ route('admin.permissions.store') }}"
          class="bg-white rounded-2xl shadow p-6 space-y-6">
        @csrf

        @include('admin.permissions.partials.form', [
            'permission' => null
        ])

        <div class="flex gap-3">
            <button class="px-4 py-2 bg-blue-700 text-white rounded-xl font-semibold">
                Guardar permiso
            </button>

            <a href="{{ route('admin.permissions.index') }}"
               class="px-4 py-2 bg-slate-200 text-slate-700 rounded-xl font-semibold">
                Cancelar
            </a>
        </div>
    </form>
@endsection