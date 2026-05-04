@extends('layouts.app')

@section('content')
    <h2 class="text-3xl font-bold text-slate-900 mb-6">Nuevo cliente</h2>

    @include('admin.empresas.clientes.partials.form', [
        'empresa' => $empresa,
        'action' => route('admin.empresas.clientes.store', $empresa),
        'method' => 'POST',
        'cliente' => null,
    ])
@endsection