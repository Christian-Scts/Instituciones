<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuGrupo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class MenuGrupoController extends Controller
{
    public function index()
    {
        $grupos = MenuGrupo::withCount('elementos')
            ->orderBy('orden')
            ->paginate(15);

        return view('admin.menu_grupos.index', compact('grupos'));
    }

    public function create()
    {
        return view('admin.menu_grupos.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:menu_grupos,slug'],
            'icono' => ['nullable', 'string', 'max:255'],
            'orden' => ['nullable', 'integer', 'min:0'],
            'activo' => ['nullable', 'boolean'],
        ]);

        MenuGrupo::create([
            'nombre' => trim($data['nombre']),
            'slug' => $data['slug'] ? Str::slug($data['slug']) : Str::slug($data['nombre']),
            'icono' => $data['icono'] ?? null,
            'orden' => $data['orden'] ?? 0,
            'activo' => $request->boolean('activo'),
        ]);

        return redirect()->route('admin.menu-grupos.index')
            ->with('success', 'Grupo de menú creado correctamente.');
    }

    public function edit(MenuGrupo $menuGrupo)
    {
        return view('admin.menu_grupos.edit', compact('menuGrupo'));
    }

    public function update(Request $request, MenuGrupo $menuGrupo)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('menu_grupos', 'slug')->ignore($menuGrupo->id),
            ],
            'icono' => ['nullable', 'string', 'max:255'],
            'orden' => ['nullable', 'integer', 'min:0'],
            'activo' => ['nullable', 'boolean'],
        ]);

        $menuGrupo->update([
            'nombre' => trim($data['nombre']),
            'slug' => $data['slug'] ? Str::slug($data['slug']) : Str::slug($data['nombre']),
            'icono' => $data['icono'] ?? null,
            'orden' => $data['orden'] ?? 0,
            'activo' => $request->boolean('activo'),
        ]);

        return redirect()->route('admin.menu-grupos.index')
            ->with('success', 'Grupo de menú actualizado correctamente.');
    }

    public function destroy(MenuGrupo $menuGrupo)
    {
        if ($menuGrupo->elementos()->exists()) {
            return back()->with('error', 'No puedes eliminar un grupo que tiene elementos.');
        }

        $menuGrupo->delete();

        return redirect()->route('admin.menu-grupos.index')
            ->with('success', 'Grupo eliminado correctamente.');
    }
}