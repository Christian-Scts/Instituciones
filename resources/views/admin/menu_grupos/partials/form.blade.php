@if ($errors->any())
    <div class="rounded-xl bg-red-50 border border-red-200 p-4 text-sm text-red-700">
        {{ $errors->first() }}
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <div>
        <label class="block text-sm font-semibold mb-2">Nombre</label>
        <input name="nombre" value="{{ old('nombre', $menuGrupo->nombre ?? '') }}"
               class="w-full rounded-xl border px-4 py-3" required>
    </div>

    <div>
        <label class="block text-sm font-semibold mb-2">Slug</label>
        <input name="slug" value="{{ old('slug', $menuGrupo->slug ?? '') }}"
               class="w-full rounded-xl border px-4 py-3">
    </div>

    <div>
        <label class="block text-sm font-semibold mb-2">Icono</label>
        <input name="icono" value="{{ old('icono', $menuGrupo->icono ?? '') }}"
               class="w-full rounded-xl border px-4 py-3"
               placeholder="fa-solid fa-user-gear">
    </div>

    <div>
        <label class="block text-sm font-semibold mb-2">Orden</label>
        <input type="number" name="orden" value="{{ old('orden', $menuGrupo->orden ?? 0) }}"
               class="w-full rounded-xl border px-4 py-3">
    </div>
</div>

<label class="flex items-center gap-3 p-4 rounded-xl border bg-slate-50 w-fit">
    <input type="checkbox" name="activo" value="1" @checked(old('activo', $menuGrupo->activo ?? true))>
    <span class="font-semibold">Activo</span>
</label>