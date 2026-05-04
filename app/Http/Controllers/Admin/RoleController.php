<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount(['usuarios', 'permissions'])
            ->orderBy('nombre')
            ->paginate(15);

        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::where('activo', true)
            ->orderBy('grupo')
            ->orderBy('nombre')
            ->get()
            ->groupBy('grupo');

        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:roles,slug'],
            'descripcion' => ['nullable', 'string', 'max:500'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
            'activo' => ['nullable', 'boolean'],
        ]);

        $role = Role::create([
            'nombre' => trim($data['nombre']),
            'slug' => $data['slug'] ? Str::slug($data['slug']) : Str::slug($data['nombre']),
            'descripcion' => $data['descripcion'] ?? null,
            'activo' => $request->boolean('activo'),
        ]);

        $role->permissions()->sync($data['permissions'] ?? []);

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Rol creado correctamente.');
    }

    public function edit(Role $role)
    {
        $role->load('permissions');

        $permissions = Permission::where('activo', true)
            ->orderBy('grupo')
            ->orderBy('nombre')
            ->get()
            ->groupBy('grupo');

        $permissionsAsignados = $role->permissions->pluck('id')->toArray();

        return view('admin.roles.edit', compact('role', 'permissions', 'permissionsAsignados'));
    }

    public function update(Request $request, Role $role)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('roles', 'slug')->ignore($role->id),
            ],
            'descripcion' => ['nullable', 'string', 'max:500'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
            'activo' => ['nullable', 'boolean'],
        ]);

        $role->update([
            'nombre' => trim($data['nombre']),
            'slug' => $data['slug'] ? Str::slug($data['slug']) : Str::slug($data['nombre']),
            'descripcion' => $data['descripcion'] ?? null,
            'activo' => $request->boolean('activo'),
        ]);

        $role->permissions()->sync($data['permissions'] ?? []);

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Rol actualizado correctamente.');
    }

    public function destroy(Role $role)
    {
        if ($role->slug === 'superadmin') {
            return back()->with('error', 'No puedes eliminar el rol superadmin.');
        }

        if ($role->usuarios()->exists()) {
            return back()->with('error', 'No puedes eliminar un rol asignado a usuarios.');
        }

        $role->permissions()->detach();
        $role->delete();

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Rol eliminado correctamente.');
    }
}