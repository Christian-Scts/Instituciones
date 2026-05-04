@extends('layouts.app')

@section('content')
<h2 class="text-3xl font-bold mb-6">Nuevo grupo de menú</h2>

<form method="POST" action="{{ route('admin.menu-grupos.store') }}"
      class="bg-white rounded-2xl shadow p-6 space-y-6">
    @csrf

    @include('admin.menu_grupos.partials.form', ['menuGrupo' => null])

    <button class="px-4 py-2 bg-blue-700 text-white rounded-xl font-semibold">
        Guardar
    </button>
</form>
@endsection