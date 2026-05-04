@extends('layouts.app')

@section('content')
<h2 class="text-3xl font-bold mb-6">Editar elemento de menú</h2>

<form method="POST" action="{{ route('admin.menu-elementos.update', $menuElemento) }}"
      class="bg-white rounded-2xl shadow p-6 space-y-6">
    @csrf
    @method('PUT')

    @include('admin.menu_elementos.partials.form', ['menuElemento' => $menuElemento])

    <button class="px-4 py-2 bg-blue-700 text-white rounded-xl font-semibold">
        Guardar cambios
    </button>
</form>
@endsection