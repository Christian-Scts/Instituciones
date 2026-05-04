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
               name="name"
               value="{{ old('name', $usuario->name ?? '') }}"
               class="w-full rounded-xl border border-slate-300 px-4 py-3"
               required>
    </div>

    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-2">Correo electrónico</label>
        <input type="email"
               name="email"
               value="{{ old('email', $usuario->email ?? '') }}"
               class="w-full rounded-xl border border-slate-300 px-4 py-3"
               required>
    </div>

    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-2">
            Contraseña
        </label>
        <input type="password"
               name="password"
               class="w-full rounded-xl border border-slate-300 px-4 py-3"
               {{ $usuario ? '' : 'required' }}>

        @if($usuario)
            <p class="text-xs text-slate-500 mt-1">
                Dejar vacío si no deseas cambiarla.
            </p>
        @endif
    </div>

    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-2">
            Confirmar contraseña
        </label>
        <input type="password"
               name="password_confirmation"
               class="w-full rounded-xl border border-slate-300 px-4 py-3"
               {{ $usuario ? '' : 'required' }}>
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-semibold text-slate-700 mb-2">
            Empresa vinculada
        </label>

        <select name="empresa_id"
                class="w-full rounded-xl border border-slate-300 px-4 py-3">
            <option value="">Usuario global / sin empresa</option>
            @foreach($empresas as $empresa)
                <option value="{{ $empresa->id }}"
                    @selected(old('empresa_id', $usuario->empresa_id ?? '') == $empresa->id)>
                    {{ $empresa->razon_social }} — {{ $empresa->rfc }}
                </option>
            @endforeach
        </select>

        <p class="text-xs text-slate-500 mt-1">
            Si seleccionas una empresa, el usuario solo deberá visualizar información vinculada a esa empresa.
        </p>
    </div>
</div>

<div>
    <label class="block text-sm font-semibold text-slate-700 mb-3">
        Roles
    </label>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
        @foreach($roles as $role)
            <label class="flex items-center gap-3 p-4 rounded-xl border border-slate-200 bg-slate-50">
                <input type="checkbox"
                       name="roles[]"
                       value="{{ $role->id }}"
                       class="rounded border-slate-300 text-blue-600"
                       @checked(in_array($role->id, old('roles', $rolesAsignados ?? [])))>

                <div>
                    <div class="font-semibold text-slate-800">
                        {{ $role->nombre }}
                    </div>
                    <div class="text-xs text-slate-500">
                        {{ $role->slug }}
                    </div>
                </div>
            </label>
        @endforeach
    </div>
</div>

<div>
    <label class="flex items-center gap-3 p-4 rounded-xl border border-slate-200 bg-slate-50 w-fit">
        <input type="checkbox"
               name="activo"
               value="1"
               class="rounded border-slate-300 text-blue-600"
               @checked(old('activo', $usuario->activo ?? true))>

        <span class="font-semibold text-slate-700">Usuario activo</span>
    </label>
</div>