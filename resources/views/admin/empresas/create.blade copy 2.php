@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-bold mb-6">Nueva empresa</h2>

    @if ($errors->any())
        <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4">
            <div class="font-semibold text-red-700 mb-2">Hay errores en el formulario:</div>
            <ul class="list-disc ml-5 text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.empresas.store') }}" class="bg-white rounded-2xl shadow p-6 space-y-4">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input name="rfc" value="{{ old('rfc') }}" placeholder="RFC" class="border rounded-lg p-3">
            <input name="razon_social" value="{{ old('razon_social') }}" placeholder="Razón social" class="border rounded-lg p-3">

            <input name="nombre_comercial" value="{{ old('nombre_comercial') }}" placeholder="Nombre comercial" class="border rounded-lg p-3">
            <input name="giro" value="{{ old('giro') }}" placeholder="Giro" class="border rounded-lg p-3">

            <select name="ambiente" class="border rounded-lg p-3">
                <option value="">Selecciona ambiente</option>
                <option value="sandbox" @selected(old('ambiente') === 'sandbox')>Sandbox</option>
                <option value="productivo" @selected(old('ambiente') === 'productivo')>Productivo</option>
            </select>

            <input name="ip_registrada" value="{{ old('ip_registrada') }}" placeholder="IP registrada" class="border rounded-lg p-3">

            <input name="url_base_api" value="{{ old('url_base_api') }}" placeholder="https://tu-dominio.com/api/pui" class="border rounded-lg p-3 md:col-span-2">

            <input name="endpoint_user" value="{{ old('endpoint_user', 'PUI') }}" placeholder="Usuario endpoint institucional" class="border rounded-lg p-3">
            <input type="password" name="endpoint_password" placeholder="Password endpoint institucional" class="border rounded-lg p-3">

            <input name="pui_user" value="{{ old('pui_user', 'PUI') }}" placeholder="Usuario PUI" class="border rounded-lg p-3">
            <input type="password" name="pui_password" placeholder="Password PUI" class="border rounded-lg p-3">

            <input name="jwt_secret" value="{{ old('jwt_secret') }}" placeholder="JWT secret" class="border rounded-lg p-3 md:col-span-2">

            <textarea name="ip_whitelist_text" rows="4" placeholder="Una IP por línea" class="border rounded-lg p-3 md:col-span-2">{{ old('ip_whitelist_text') }}</textarea>

            <input type="number" name="rate_limit_per_minute" value="{{ old('rate_limit_per_minute', 60) }}" placeholder="Rate limit por minuto" class="border rounded-lg p-3">
        </div>

        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">Guardar</button>
    </form>
@endsection