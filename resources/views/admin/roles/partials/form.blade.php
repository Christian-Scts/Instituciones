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
               value="{{ old('nombre', $role->nombre ?? '') }}"
               class="w-full rounded-xl border border-slate-300 px-4 py-3"
               required>
    </div>

    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-2">Slug</label>
        <input type="text"
               name="slug"
               value="{{ old('slug', $role->slug ?? '') }}"
               class="w-full rounded-xl border border-slate-300 px-4 py-3"
               placeholder="Ej. operador">
        <p class="text-xs text-slate-500 mt-1">Si lo dejas vacío se genera desde el nombre.</p>
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-semibold text-slate-700 mb-2">Descripción</label>
        <textarea name="descripcion"
                  rows="3"
                  class="w-full rounded-xl border border-slate-300 px-4 py-3">{{ old('descripcion', $role->descripcion ?? '') }}</textarea>
    </div>
</div>

<div>
    <label class="flex items-center gap-3 p-4 rounded-xl border border-slate-200 bg-slate-50 w-fit">
        <input type="checkbox"
               name="activo"
               value="1"
               class="rounded border-slate-300 text-blue-600"
               @checked(old('activo', $role->activo ?? true))>

        <span class="font-semibold text-slate-700">Rol activo</span>
    </label>
</div>

<div>
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="text-xl font-bold text-slate-900">Permisos</h3>
            <p class="text-sm text-slate-500">Selecciona los accesos permitidos para este rol</p>
        </div>
    </div>

    <div class="space-y-5">
        @foreach($permissions as $grupo => $items)
            <div class="rounded-2xl border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 px-5 py-3 font-bold text-slate-800">
                    {{ $grupo ?: 'General' }}
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 p-5">
                    @foreach($items as $permission)
                        <label class="flex items-start gap-3 p-3 rounded-xl border border-slate-200 bg-white hover:bg-slate-50">
                            <input type="checkbox"
                                   name="permissions[]"
                                   value="{{ $permission->id }}"
                                   class="mt-1 rounded border-slate-300 text-blue-600"
                                   @checked(in_array($permission->id, old('permissions', $permissionsAsignados ?? [])))>

                            <div>
                                <div class="font-semibold text-slate-800">
                                    {{ $permission->nombre }}
                                </div>
                                <div class="text-xs text-slate-500">
                                    {{ $permission->slug }}
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>