@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-bold mb-6">Evidencias de seguridad</h2>

    <form method="POST" action="{{ route('admin.seguridad.store') }}" enctype="multipart/form-data" class="bg-white rounded-2xl shadow p-6 mb-8 space-y-4">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <select name="empresa_id" class="border rounded-lg p-3" required>
                <option value="">Selecciona empresa</option>
                @foreach($empresas as $empresa)
                    <option value="{{ $empresa->id }}">{{ $empresa->razon_social }}</option>
                @endforeach
            </select>

            <select name="tipo_reporte" class="border rounded-lg p-3" required>
                <option value="">Tipo de reporte</option>
                <option value="SAST">SAST</option>
                <option value="DAST">DAST</option>
                <option value="SCA">SCA</option>
            </select>

            <input type="text" name="herramienta" placeholder="Herramienta" class="border rounded-lg p-3">
            <input type="text" name="version_herramienta" placeholder="Versi贸n herramienta" class="border rounded-lg p-3">
            <select name="ambiente_ejecucion" class="border rounded-lg p-3">
                <option value="sandbox">Sandbox</option>
                <option value="productivo">Productivo</option>
            </select>
            <input type="datetime-local" name="fecha_ejecucion" class="border rounded-lg p-3">
            <input type="file" name="archivo" class="border rounded-lg p-3">
        </div>

        <textarea name="urls_validadas" rows="4" class="border rounded-lg p-3 w-full" placeholder="Una URL por l铆nea"></textarea>
        <input type="text" name="resultado_global" placeholder="sin_vulnerabilidades / bajo_riesgo / etc." class="border rounded-lg p-3 w-full">
        <textarea name="observaciones" rows="4" class="border rounded-lg p-3 w-full" placeholder="Observaciones"></textarea>

        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">Guardar evidencia</button>
    </form>

    <div class="bg-white rounded-2xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="p-3 text-left">Empresa</th>
                    <th class="p-3 text-left">Tipo</th>
                    <th class="p-3 text-left">Herramienta</th>
                    <th class="p-3 text-left">Ambiente</th>
                    <th class="p-3 text-left">Fecha</th>
                    <th class="p-3 text-left">Resultado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($evidencias as $evidencia)
                    <tr class="border-t">
                        <td class="p-3">{{ $evidencia->empresa?->razon_social }}</td>
                        <td class="p-3">{{ $evidencia->tipo_reporte }}</td>
                        <td class="p-3">{{ $evidencia->herramienta }}</td>
                        <td class="p-3">{{ $evidencia->ambiente_ejecucion }}</td>
                        <td class="p-3">{{ $evidencia->fecha_ejecucion }}</td>
                        <td class="p-3">{{ $evidencia->resultado_global }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $evidencias->links() }}</div>
@endsection