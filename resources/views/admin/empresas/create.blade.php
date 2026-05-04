@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-slate-900">Nueva empresa</h2>
        <p class="text-slate-500 mt-1">Alta de empresa para integración PUI</p>
    </div>

    @if ($errors->any())
        <div class="mb-4 rounded-2xl border border-red-200 bg-red-50 p-4">
            <div class="font-semibold text-red-700 mb-2">Corrige los siguientes errores:</div>
            <ul class="list-disc ml-5 text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6 max-w-5xl">
        <form method="POST" action="{{ route('admin.empresas.store') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">RFC</label>
                    <input type="text" name="rfc" value="{{ old('rfc') }}" class="w-full rounded-xl border border-slate-300 px-4 py-3">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Razón social</label>
                    <input type="text" name="razon_social" value="{{ old('razon_social') }}" class="w-full rounded-xl border border-slate-300 px-4 py-3">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Nombre comercial</label>
                    <input type="text" name="nombre_comercial" value="{{ old('nombre_comercial') }}" class="w-full rounded-xl border border-slate-300 px-4 py-3">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Giro</label>
                    <input type="text" name="giro" value="{{ old('giro') }}" class="w-full rounded-xl border border-slate-300 px-4 py-3">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Ambiente</label>
                    <select name="ambiente" class="w-full rounded-xl border border-slate-300 px-4 py-3">
                        <option value="">Selecciona</option>
                        <option value="sandbox" @selected(old('ambiente') === 'sandbox')>Sandbox</option>
                        <option value="productivo" @selected(old('ambiente') === 'productivo')>Productivo</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">IP registrada</label>
                    <input type="text" name="ip_registrada" value="{{ old('ip_registrada') }}" class="w-full rounded-xl border border-slate-300 px-4 py-3">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">URL base API</label>
                    <input type="text" name="url_base_api" value="{{ old('url_base_api') }}" placeholder="https://tu-dominio.com/api/pui" class="w-full rounded-xl border border-slate-300 px-4 py-3">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Usuario endpoint institucional</label>
                    <input type="text" name="endpoint_user" value="{{ old('endpoint_user', 'PUI') }}" class="w-full rounded-xl border border-slate-300 px-4 py-3">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Password endpoint institucional</label>
                    <input type="password" name="endpoint_password" class="w-full rounded-xl border border-slate-300 px-4 py-3">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Usuario PUI</label>
                    <input type="text" name="pui_user" value="{{ old('pui_user', 'PUI') }}" class="w-full rounded-xl border border-slate-300 px-4 py-3">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Password PUI</label>
                    <input type="password" name="pui_password" class="w-full rounded-xl border border-slate-300 px-4 py-3">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">JWT secret</label>
                    <input type="text" name="jwt_secret" value="{{ old('jwt_secret') }}" class="w-full rounded-xl border border-slate-300 px-4 py-3">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Algoritmo JWT</label>
                    <select name="jwt_algo" class="w-full rounded-xl border border-slate-300 px-4 py-3">
                        <option value="HS256" @selected(old('jwt_algo', 'HS256') === 'HS256')>HS256</option>
                        <option value="HS384" @selected(old('jwt_algo') === 'HS384')>HS384</option>
                        <option value="HS512" @selected(old('jwt_algo') === 'HS512')>HS512</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Rate limit por minuto</label>
                    <input type="number" name="rate_limit_per_minute" value="{{ old('rate_limit_per_minute', 60) }}" class="w-full rounded-xl border border-slate-300 px-4 py-3">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">IP whitelist</label>
                    <textarea name="ip_whitelist_text" rows="4" class="w-full rounded-xl border border-slate-300 px-4 py-3" placeholder="Una IP por línea">{{ old('ip_whitelist_text') }}</textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <label class="flex items-center gap-3 p-4 rounded-2xl border border-slate-200 bg-slate-50">
                    <input type="checkbox" name="activo" value="1" @checked(old('activo', true)) class="rounded border-slate-300 text-blue-600">
                    <span class="text-sm font-medium text-slate-700">Empresa activa</span>
                </label>

                <label class="flex items-center gap-3 p-4 rounded-2xl border border-slate-200 bg-slate-50">
                    <input type="checkbox" name="aprobado_sandbox" value="1" @checked(old('aprobado_sandbox')) class="rounded border-slate-300 text-blue-600">
                    <span class="text-sm font-medium text-slate-700">Aprobado sandbox</span>
                </label>

                <label class="flex items-center gap-3 p-4 rounded-2xl border border-slate-200 bg-slate-50">
                    <input type="checkbox" name="aprobado_productivo" value="1" @checked(old('aprobado_productivo')) class="rounded border-slate-300 text-blue-600">
                    <span class="text-sm font-medium text-slate-700">Aprobado productivo</span>
                </label>
            </div>

            <div class="flex items-center gap-3 pt-4">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Guardar empresa</button>
                <a href="{{ route('admin.empresas.index') }}" class="px-4 py-2 bg-slate-200 text-slate-800 rounded-lg">Cancelar</a>
            </div>
        </form>
    </div>
@endsection