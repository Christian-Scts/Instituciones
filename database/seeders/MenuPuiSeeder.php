<?php

namespace Database\Seeders;

use App\Models\MenuElemento;
use App\Models\MenuGrupo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MenuPuiSeeder extends Seeder
{
    public function run(): void
    {
        $grupos = [
            [
                'nombre' => 'Principal',
                'slug' => 'principal',
                'icono' => 'fa-solid fa-layer-group',
                'orden' => 1,
                'elementos' => [
                    [
                        'titulo' => 'Dashboard',
                        'slug' => 'dashboard',
                        'route_name' => 'admin.dashboard',
                        'url' => null,
                        'icono' => 'fa-solid fa-gauge',
                        'permission_slug' => 'dashboard.ver',
                        'orden' => 1,
                    ],
                ],
            ],
            [
                'nombre' => 'Operación PUI',
                'slug' => 'operacion-pui',
                'icono' => 'fa-solid fa-network-wired',
                'orden' => 2,
                'elementos' => [
                    [
                        'titulo' => 'Empresas',
                        'slug' => 'empresas',
                        'route_name' => 'admin.empresas.index',
                        'url' => null,
                        'icono' => 'fa-solid fa-building',
                        'permission_slug' => 'empresas.ver',
                        'orden' => 1,
                    ],
                    [
                        'titulo' => 'Reportes',
                        'slug' => 'reportes',
                        'route_name' => 'admin.reportes.index',
                        'url' => null,
                        'icono' => 'fa-solid fa-file-lines',
                        'permission_slug' => 'reportes.ver',
                        'orden' => 2,
                    ],
                    [
                        'titulo' => 'Pruebas',
                        'slug' => 'pruebas',
                        'route_name' => 'admin.pruebas.index',
                        'url' => null,
                        'icono' => 'fa-solid fa-vial',
                        'permission_slug' => 'pruebas.ver',
                        'orden' => 3,
                    ],
                ],
            ],
            [
                'nombre' => 'Monitoreo',
                'slug' => 'monitoreo',
                'icono' => 'fa-solid fa-chart-line',
                'orden' => 3,
                'elementos' => [
                    [
                        'titulo' => 'Logs',
                        'slug' => 'logs',
                        'route_name' => 'admin.logs.index',
                        'url' => null,
                        'icono' => 'fa-solid fa-list',
                        'permission_slug' => 'logs.ver',
                        'orden' => 1,
                    ],
                    [
                        'titulo' => 'Seguridad',
                        'slug' => 'seguridad',
                        'route_name' => 'admin.seguridad.index',
                        'url' => null,
                        'icono' => 'fa-solid fa-shield-halved',
                        'permission_slug' => 'seguridad.ver',
                        'orden' => 2,
                    ],
                ],
            ],
            [
                'nombre' => 'Administración',
                'slug' => 'administracion',
                'icono' => 'fa-solid fa-user-gear',
                'orden' => 4,
                'elementos' => [
                    [
                        'titulo' => 'Usuarios',
                        'slug' => 'usuarios',
                        'route_name' => 'admin.usuarios.index',
                        'url' => null,
                        'icono' => 'fa-solid fa-users',
                        'permission_slug' => 'usuarios.ver',
                        'orden' => 1,
                    ],
                    [
                        'titulo' => 'Roles',
                        'slug' => 'roles',
                        'route_name' => 'admin.roles.index',
                        'url' => null,
                        'icono' => 'fa-solid fa-user-lock',
                        'permission_slug' => 'roles.ver',
                        'orden' => 2,
                    ],
                ],
            ],
        ];

        foreach ($grupos as $grupoData) {
            $elementos = $grupoData['elementos'];
            unset($grupoData['elementos']);

            $grupo = MenuGrupo::updateOrCreate(
                ['slug' => $grupoData['slug']],
                $grupoData + ['activo' => true]
            );

            foreach ($elementos as $elementoData) {
                MenuElemento::updateOrCreate(
                    ['slug' => $elementoData['slug']],
                    $elementoData + [
                        'menu_grupo_id' => $grupo->id,
                        'activo' => true,
                    ]
                );
            }
        }
    }
}