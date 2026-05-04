@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-slate-900">Editar empresa</h2>
        <p class="text-slate-500 mt-1">Actualiza la configuración de la empresa para la integración PUI</p>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6 max-w-4xl">
        <form action="{{ route('admin.empresas.update', $empresa) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">RFC</label>
                    <input type="text" name="rfc" value="{{ old('rfc', $empresa->rfc) }}"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    @error('rfc')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Razón social</label>
                    <input type="text" name="razon_social" value="{{ old('razon_social', $empresa->razon_social) }}"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    @error('razon_social')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Nombre comercial</label>
                    <input type="text" name="nombre_comercial" value="{{ old('nombre_comercial', $empresa->nombre_comercial) }}"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    @error('nombre_comercial')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Ambiente</label>
                    <select name="ambiente"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="sandbox" @selected(old('ambiente', $empresa->ambiente) === 'sandbox')>Sandbox</option>
                        <option value="productivo" @selected(old('ambiente', $empresa->ambiente) === 'productivo')>Productivo</option>
                    </select>
                    @error('ambiente')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">URL base API</label>
                    <input type="text" name="url_base_api" value="{{ old('url_base_api', $empresa->url_base_api) }}"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    @error('url_base_api')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">IP whitelist</label>
                    <textarea name="ip_whitelist_text" rows="4"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                        placeholder="Una IP por línea">{{ old('ip_whitelist_text', is_array($empresa->ip_whitelist) ? implode("\n", $empresa->ip_whitelist) : '') }}</textarea>
                    <p class="text-xs text-slate-500 mt-1">Ejemplo: 127.0.0.1 o ::1</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Rate limit por minuto</label>
                    <input type="number" name="rate_limit_per_minute"
                        value="{{ old('rate_limit_per_minute', $empresa->rate_limit_per_minute ?? 60) }}"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <label class="flex items-center gap-3 p-4 rounded-2xl border border-slate-200 bg-slate-50">
                    <input type="checkbox" name="activo" value="1"
                        @checked(old('activo', $empresa->activo))
                        class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-sm font-medium text-slate-700">Empresa activa</span>
                </label>

                <label class="flex items-center gap-3 p-4 rounded-2xl border border-slate-200 bg-slate-50">
                    <input type="checkbox" name="aprobado_sandbox" value="1"
                        @checked(old('aprobado_sandbox', $empresa->aprobado_sandbox))
                        class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-sm font-medium text-slate-700">Aprobado sandbox</span>
                </label>

                <label class="flex items-center gap-3 p-4 rounded-2xl border border-slate-200 bg-slate-50">
                    <input type="checkbox" name="aprobado_productivo" value="1"
                        @checked(old('aprobado_productivo', $empresa->aprobado_productivo))
                        class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-sm font-medium text-slate-700">Aprobado productivo</span>
                </label>
            </div>

            <div class="flex items-center gap-3 pt-4">
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                    Guardar cambios
                </button>

                <a href="{{ route('admin.empresas.index') }}"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
@endsection