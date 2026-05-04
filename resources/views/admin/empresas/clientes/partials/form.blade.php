<div class="bg-white rounded-2xl shadow p-6 max-w-5xl">
    <form action="{{ $action }}" method="POST" class="space-y-6">
        @csrf
        @if($method !== 'POST')
            @method($method)
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <input type="text" name="curp" value="{{ old('curp', $cliente->curp ?? '') }}" placeholder="CURP"
                class="w-full rounded-xl border border-slate-300 px-4 py-3">

            <input type="text" name="nombre" value="{{ old('nombre', $cliente->nombre ?? '') }}" placeholder="Nombre"
                class="w-full rounded-xl border border-slate-300 px-4 py-3">

            <input type="text" name="primer_apellido" value="{{ old('primer_apellido', $cliente->primer_apellido ?? '') }}" placeholder="Primer apellido"
                class="w-full rounded-xl border border-slate-300 px-4 py-3">

            <input type="text" name="segundo_apellido" value="{{ old('segundo_apellido', $cliente->segundo_apellido ?? '') }}" placeholder="Segundo apellido"
                class="w-full rounded-xl border border-slate-300 px-4 py-3">

            <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', optional($cliente->fecha_nacimiento ?? null)->format('Y-m-d')) }}"
                class="w-full rounded-xl border border-slate-300 px-4 py-3">

            <input type="text" name="lugar_nacimiento" value="{{ old('lugar_nacimiento', $cliente->lugar_nacimiento ?? '') }}" placeholder="Lugar nacimiento"
                class="w-full rounded-xl border border-slate-300 px-4 py-3">

            <input type="text" name="sexo_asignado" value="{{ old('sexo_asignado', $cliente->sexo_asignado ?? '') }}" placeholder="Sexo asignado (M/H/X)"
                class="w-full rounded-xl border border-slate-300 px-4 py-3">

            <input type="text" name="telefono" value="{{ old('telefono', $cliente->telefono ?? '') }}" placeholder="Teléfono"
                class="w-full rounded-xl border border-slate-300 px-4 py-3">

            <input type="email" name="correo" value="{{ old('correo', $cliente->correo ?? '') }}" placeholder="Correo"
                class="w-full rounded-xl border border-slate-300 px-4 py-3 md:col-span-2">

            <input type="text" name="direccion" value="{{ old('direccion', $cliente->direccion ?? '') }}" placeholder="Dirección"
                class="w-full rounded-xl border border-slate-300 px-4 py-3 md:col-span-2">

            <input type="text" name="calle" value="{{ old('calle', $cliente->calle ?? '') }}" placeholder="Calle"
                class="w-full rounded-xl border border-slate-300 px-4 py-3">

            <input type="text" name="numero" value="{{ old('numero', $cliente->numero ?? '') }}" placeholder="Número"
                class="w-full rounded-xl border border-slate-300 px-4 py-3">

            <input type="text" name="colonia" value="{{ old('colonia', $cliente->colonia ?? '') }}" placeholder="Colonia"
                class="w-full rounded-xl border border-slate-300 px-4 py-3">

            <input type="text" name="codigo_postal" value="{{ old('codigo_postal', $cliente->codigo_postal ?? '') }}" placeholder="Código postal"
                class="w-full rounded-xl border border-slate-300 px-4 py-3">

            <input type="text" name="municipio_o_alcaldia" value="{{ old('municipio_o_alcaldia', $cliente->municipio_o_alcaldia ?? '') }}" placeholder="Municipio o alcaldía"
                class="w-full rounded-xl border border-slate-300 px-4 py-3">

            <input type="text" name="entidad_federativa" value="{{ old('entidad_federativa', $cliente->entidad_federativa ?? '') }}" placeholder="Entidad federativa"
                class="w-full rounded-xl border border-slate-300 px-4 py-3">

            <input type="text" name="tipo_evento" value="{{ old('tipo_evento', $cliente->tipo_evento ?? '') }}" placeholder="Tipo evento"
                class="w-full rounded-xl border border-slate-300 px-4 py-3 md:col-span-2">

            <input type="date" name="fecha_evento" value="{{ old('fecha_evento', optional($cliente->fecha_evento ?? null)->format('Y-m-d')) }}"
                class="w-full rounded-xl border border-slate-300 px-4 py-3">

            <input type="date" name="fecha_referencia" value="{{ old('fecha_referencia', optional($cliente->fecha_referencia ?? null)->format('Y-m-d')) }}"
                class="w-full rounded-xl border border-slate-300 px-4 py-3">

            <textarea name="descripcion_lugar_evento" rows="3" placeholder="Descripción lugar evento"
                class="w-full rounded-xl border border-slate-300 px-4 py-3 md:col-span-2">{{ old('descripcion_lugar_evento', $cliente->descripcion_lugar_evento ?? '') }}</textarea>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                Guardar
            </button>

            <a href="{{ route('admin.empresas.clientes.index', $empresa) }}" class="px-4 py-2 bg-slate-200 text-slate-800 rounded-lg">
                Cancelar
            </a>
        </div>
    </form>
</div>