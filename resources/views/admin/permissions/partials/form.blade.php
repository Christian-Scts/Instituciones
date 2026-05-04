@if ($errors->any())
    <div class="rounded-xl bg-red-50 border border-red-200 p-4 text-sm text-red-700">
        <ul class="list-disc ml-5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-2">Nombre</label>
        <input type="text"
               name="nombre"
               value="{{ old('nombre', $permission->nombre ?? '') }}"
               class="w-full rounded-xl border border-slate-300 px-4 py-3"
               required>
    </div>

    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-2">Slug</label>
        <input type="text"
               name="slug"
               value="{{ old('slug', $permission->slug ?? '') }}"
               class="w-full rounded-xl border border-slate-300 px-4 py-3"
               placeholder="Ej. empresas.ver">
        <p class="text-xs text-slate-500 mt-1">
            Recomendado: modulo.accion, por ejemplo clientes.importar
        </p>
    </div>

    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-2">Grupo</label>
        <input type="text"
               name="grupo"
               value="{{ old('grupo', $permission->grupo ?? '') }}"
               class="w-full rounded-xl border border-slate-300 px-4 py-3"
               placeholder="Ej. Empresas">
    </div>

    <div>
        <label class="flex items-center gap-3 p-4 rounded-xl border border-slate-200 bg-slate-50 mt-7 w-fit">
            <input type="checkbox"
                   name="activo"
                   value="1"
                   class="rounded border-slate-300 text-blue-600"
                   @checked(old('activo', $permission->activo ?? true))>

            <span class="font-semibold text-slate-700">Permiso activo</span>
        </label>
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-semibold text-slate-700 mb-2">Descripción</label>
        <textarea name="descripcion"
                  rows="3"
                  class="w-full rounded-xl border border-slate-300 px-4 py-3">{{ old('descripcion', $permission->descripcion ?? '') }}</textarea>
    </div>
</div>