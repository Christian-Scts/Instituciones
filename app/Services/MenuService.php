<?php

namespace App\Services;

use App\Models\MenuGrupo;
use App\Models\Usuario;
use Illuminate\Support\Facades\Route;

class MenuService
{
    public function buildForUsuario(Usuario $usuario): array
    {
        $permissions = $usuario->permissionsArray();

        $grupos = MenuGrupo::query()
            ->where('activo', true)
            ->with(['elementos' => function ($query) {
                $query->where('activo', true)
                    ->orderBy('orden');
            }])
            ->orderBy('orden')
            ->get();

        $menu = [];

        foreach ($grupos as $grupo) {
            $items = [];

            foreach ($grupo->elementos as $elemento) {
                if ($elemento->permission_slug && !in_array($elemento->permission_slug, $permissions, true)) {
                    continue;
                }

                if ($elemento->route_name && !Route::has($elemento->route_name)) {
                    continue;
                }

                $items[] = [
                    'titulo' => $elemento->titulo,
                    'slug' => $elemento->slug,
                    'route_name' => $elemento->route_name,
                    'url' => $elemento->route_name
                        ? route($elemento->route_name)
                        : ($elemento->url ?? '#'),
                    'icono' => $elemento->icono,
                    'permission_slug' => $elemento->permission_slug,
                    'active_patterns' => $this->patternsForRoute($elemento->route_name),
                ];
            }

            if (!empty($items)) {
                $menu[] = [
                    'nombre' => $grupo->nombre,
                    'slug' => $grupo->slug,
                    'icono' => $grupo->icono,
                    'items' => $items,
                ];
            }
        }

        return $menu;
    }

    private function patternsForRoute(?string $routeName): array
{
    return match ($routeName) {
        'admin.dashboard' => ['admin/dashboard*'],

        'admin.empresas.index' => ['admin/empresas*'],
        'admin.empresas.mi-panel' => ['admin/mi-panel*'],
        'admin.reportes.index' => ['admin/reportes*'],
        'admin.logs.index' => ['admin/logs*'],
        'admin.pruebas.index' => ['admin/pruebas*'],
        'admin.seguridad.index' => ['admin/seguridad*'],

        'admin.usuarios.index' => ['admin/usuarios*'],
        'admin.roles.index' => ['admin/roles*'],
        'admin.permissions.index' => ['admin/permissions*'],

        'admin.menu-grupos.index' => ['admin/menu-grupos*'],
        'admin.menu-elementos.index' => ['admin/menu-elementos*'],

        default => [],
    };
}
}