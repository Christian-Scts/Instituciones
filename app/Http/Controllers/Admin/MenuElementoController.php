<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuElemento;
use App\Models\MenuGrupo;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class MenuElementoController extends Controller
{
    public function index()
    {
        $elementos = MenuElemento::with('grupo')
            ->orderBy('menu_grupo_id')
            ->orderBy('orden')
            ->paginate(20);

        return view('admin.menu_elementos.index', compact('elementos'));
    }

    public function create()
    {
        return view('admin.menu_elementos.create', $this->formData());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'menu_grupo_id' => ['required', 'exists:menu_grupos,id'],
            'titulo' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:menu_elementos,slug'],
            'route_name' => ['nullable', 'string', 'max:255'],
            'url' => ['nullable', 'string', 'max:500'],
            'icono' => ['nullable', 'string', 'max:255'],
            'permission_slug' => ['nullable', 'string', 'max:255'],
            'orden' => ['nullable', 'integer', 'min:0'],
            'activo' => ['nullable', 'boolean'],
        ]);

        if (!empty($data['route_name']) && !Route::has($data['route_name'])) {
            return back()->withInput()->withErrors([
                'route_name' => 'La ruta indicada no existe.',
            ]);
        }

        MenuElemento::create([
            'menu_grupo_id' => $data['menu_grupo_id'],
            'titulo' => trim($data['titulo']),
            'slug' => $data['slug'] ? Str::slug($data['slug']) : Str::slug($data['titulo']),
            'route_name' => $data['route_name'] ?? null,
            'url' => $data['url'] ?? null,
            'icono' => $data['icono'] ?? null,
            'permission_slug' => $data['permission_slug'] ?? null,
            'orden' => $data['orden'] ?? 0,
            'activo' => $request->boolean('activo'),
        ]);

        return redirect()->route('admin.menu-elementos.index')
            ->with('success', 'Elemento de menú creado correctamente.');
    }

    public function edit(MenuElemento $menuElemento)
    {
        return view('admin.menu_elementos.edit', array_merge(
            ['menuElemento' => $menuElemento],
            $this->formData()
        ));
    }

    public function update(Request $request, MenuElemento $menuElemento)
    {
        $data = $request->validate([
            'menu_grupo_id' => ['required', 'exists:menu_grupos,id'],
            'titulo' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('menu_elementos', 'slug')->ignore($menuElemento->id),
            ],
            'route_name' => ['nullable', 'string', 'max:255'],
            'url' => ['nullable', 'string', 'max:500'],
            'icono' => ['nullable', 'string', 'max:255'],
            'permission_slug' => ['nullable', 'string', 'max:255'],
            'orden' => ['nullable', 'integer', 'min:0'],
            'activo' => ['nullable', 'boolean'],
        ]);

        if (!empty($data['route_name']) && !Route::has($data['route_name'])) {
            return back()->withInput()->withErrors([
                'route_name' => 'La ruta indicada no existe.',
            ]);
        }

        $menuElemento->update([
            'menu_grupo_id' => $data['menu_grupo_id'],
            'titulo' => trim($data['titulo']),
            'slug' => $data['slug'] ? Str::slug($data['slug']) : Str::slug($data['titulo']),
            'route_name' => $data['route_name'] ?? null,
            'url' => $data['url'] ?? null,
            'icono' => $data['icono'] ?? null,
            'permission_slug' => $data['permission_slug'] ?? null,
            'orden' => $data['orden'] ?? 0,
            'activo' => $request->boolean('activo'),
        ]);

        return redirect()->route('admin.menu-elementos.index')
            ->with('success', 'Elemento de menú actualizado correctamente.');
    }

    public function destroy(MenuElemento $menuElemento)
    {
        $menuElemento->delete();

        return redirect()->route('admin.menu-elementos.index')
            ->with('success', 'Elemento eliminado correctamente.');
    }

    private function formData(): array
    {
        return [
            'grupos' => MenuGrupo::where('activo', true)->orderBy('orden')->get(),
            'permissions' => Permission::where('activo', true)->orderBy('grupo')->orderBy('nombre')->get(),
            'routes' => collect(Route::getRoutes())
                ->map(fn ($route) => $route->getName())
                ->filter()
                ->filter(fn ($name) => str_starts_with($name, 'admin.'))
                ->sort()
                ->values(),
        ];
    }
}