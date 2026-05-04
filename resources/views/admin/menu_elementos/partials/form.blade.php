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
        <label class="block text-sm font-semibold mb-2">Grupo</label>
        <select name="menu_grupo_id" class="w-full rounded-xl border px-4 py-3" required>
            <option value="">Selecciona grupo</option>
            @foreach($grupos as $grupo)
                <option value="{{ $grupo->id }}"
                    @selected(old('menu_grupo_id', $menuElemento->menu_grupo_id ?? '') == $grupo->id)>
                    {{ $grupo->nombre }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-semibold mb-2">Título</label>
        <input name="titulo" value="{{ old('titulo', $menuElemento->titulo ?? '') }}"
               class="w-full rounded-xl border px-4 py-3" required>
    </div>

    <div>
        <label class="block text-sm font-semibold mb-2">Slug</label>
        <input name="slug" value="{{ old('slug', $menuElemento->slug ?? '') }}"
               class="w-full rounded-xl border px-4 py-3">
    </div>

    <div>
        <label class="block text-sm font-semibold mb-2">Icono</label>
        <input name="icono" value="{{ old('icono', $menuElemento->icono ?? '') }}"
               class="w-full rounded-xl border px-4 py-3"
               placeholder="fa-solid fa-gauge">
    </div>

    <div>
        <label class="block text-sm font-semibold mb-2">Route name</label>
        <select name="route_name" class="w-full rounded-xl border px-4 py-3">
            <option value="">Sin ruta Laravel</option>
            @foreach($routes as $route)
                <option value="{{ $route }}"
                    @selected(old('route_name', $menuElemento->route_name ?? '') == $route)>
                    {{ $route }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-semibold mb-2">URL manual</label>
        <input name="url" value="{{ old('url', $menuElemento->url ?? '') }}"
               class="w-full rounded-xl border px-4 py-3"
               placeholder="/admin/ejemplo">
    </div>

    <div>
        <label class="block text-sm font-semibold mb-2">Permiso requerido</label>
        <select name="permission_slug" class="w-full rounded-xl border px-4 py-3">
            <option value="">Sin permiso específico</option>
            @foreach($permissions as $permission)
                <option value="{{ $permission->slug }}"
                    @selected(old('permission_slug', $menuElemento->permission_slug ?? '') == $permission->slug)>
                    {{ $permission->grupo }} — {{ $permission->nombre }} ({{ $permission->slug }})
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-semibold mb-2">Orden</label>
        <input type="number" name="orden" value="{{ old('orden', $menuElemento->orden ?? 0) }}"
               class="w-full rounded-xl border px-4 py-3">
    </div>
</div>

<label class="flex items-center gap-3 p-4 rounded-xl border bg-slate-50 w-fit">
    <input type="checkbox" name="activo" value="1" @checked(old('activo', $menuElemento->activo ?? true))>
    <span class="font-semibold">Activo</span>
</label>