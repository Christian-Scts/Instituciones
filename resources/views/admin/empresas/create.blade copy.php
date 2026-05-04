@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-bold mb-6">Nueva empresa</h2>

    <form method="POST" action="{{ route('admin.empresas.store') }}" class="bg-white rounded-2xl shadow p-6 space-y-4">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input name="rfc" placeholder="RFC" class="border rounded-lg p-3">
            <input name="razon_social" placeholder="Razón social" class="border rounded-lg p-3">
            <input name="nombre_comercial" placeholder="Nombre comercial" class="border rounded-lg p-3">
            <input name="giro" placeholder="Giro" class="border rounded-lg p-3">
            <input name="ambiente" placeholder="sandbox" class="border rounded-lg p-3">
            <input name="ip_registrada" placeholder="IP registrada" class="border rounded-lg p-3">
            <input name="url_base_api" placeholder="https://tu-dominio.com/api/pui" class="border rounded-lg p-3 md:col-span-2">
            <input name="endpoint_user" placeholder="Usuario endpoint institucional" class="border rounded-lg p-3">
            <input name="endpoint_password" placeholder="Password endpoint institucional" class="border rounded-lg p-3">
            <input name="pui_user" placeholder="Usuario PUI" class="border rounded-lg p-3">
            <input name="pui_password" placeholder="Password PUI" class="border rounded-lg p-3">
            <input name="jwt_secret" placeholder="JWT secret" class="border rounded-lg p-3 md:col-span-2">
        </div>

        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">Guardar</button>
    </form>
@endsection