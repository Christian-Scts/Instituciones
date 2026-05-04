<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $permissions = Permission::query()
            ->when($request->filled('buscar'), function ($query) use ($request) {
                $buscar = $request->buscar;

                $query->where(function ($q) use ($buscar) {
                    $q->where('nombre', 'like', "%{$buscar}%")
                        ->orWhere('slug', 'like', "%{$buscar}%")
                        ->orWhere('grupo', 'like', "%{$buscar}%");
                });
            })
            ->orderBy('grupo')
            ->orderBy('nombre')
            ->paginate(20)
            ->withQueryString();

        return view('admin.permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('admin.permissions.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:permissions,slug'],
            'grupo' => ['nullable', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string', 'max:500'],
            'activo' => ['nullable', 'boolean'],
        ]);

        Permission::create([
            'nombre' => trim($data['nombre']),
            'slug' => $data['slug'] ? Str::slug($data['slug'], '.') : Str::slug($data['nombre'], '.'),
            'grupo' => $data['grupo'] ?? null,
            'descripcion' => $data['descripcion'] ?? null,
            'activo' => $request->boolean('activo'),
        ]);

        return redirect()
            ->route('admin.permissions.index')
            ->with('success', 'Permiso creado correctamente.');
    }

    public function edit(Permission $permission)
    {
        return view('admin.permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('permissions', 'slug')->ignore($permission->id),
            ],
            'grupo' => ['nullable', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string', 'max:500'],
            'activo' => ['nullable', 'boolean'],
        ]);

        $permission->update([
            'nombre' => trim($data['nombre']),
            'slug' => $data['slug'] ? Str::slug($data['slug'], '.') : Str::slug($data['nombre'], '.'),
            'grupo' => $data['grupo'] ?? null,
            'descripcion' => $data['descripcion'] ?? null,
            'activo' => $request->boolean('activo'),
        ]);

        return redirect()
            ->route('admin.permissions.index')
            ->with('success', 'Permiso actualizado correctamente.');
    }

    public function destroy(Permission $permission)
    {
        if ($permission->roles()->exists()) {
            return back()->with('error', 'No puedes eliminar un permiso asignado a roles.');
        }

        $permission->delete();

        return redirect()
            ->route('admin.permissions.index')
            ->with('success', 'Permiso eliminado correctamente.');
    }
}