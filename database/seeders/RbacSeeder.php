<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RbacSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['grupo' => 'Dashboard', 'nombre' => 'Ver dashboard', 'slug' => 'dashboard.ver'],

            ['grupo' => 'Empresas', 'nombre' => 'Ver empresas', 'slug' => 'empresas.ver'],
            ['grupo' => 'Empresas', 'nombre' => 'Crear empresas', 'slug' => 'empresas.crear'],
            ['grupo' => 'Empresas', 'nombre' => 'Editar empresas', 'slug' => 'empresas.editar'],

            ['grupo' => 'Clientes', 'nombre' => 'Ver clientes', 'slug' => 'clientes.ver'],
            ['grupo' => 'Clientes', 'nombre' => 'Crear clientes', 'slug' => 'clientes.crear'],
            ['grupo' => 'Clientes', 'nombre' => 'Editar clientes', 'slug' => 'clientes.editar'],
            ['grupo' => 'Clientes', 'nombre' => 'Importar clientes', 'slug' => 'clientes.importar'],

            ['grupo' => 'Reportes', 'nombre' => 'Ver reportes', 'slug' => 'reportes.ver'],
            ['grupo' => 'Reportes', 'nombre' => 'Resincronizar reportes', 'slug' => 'reportes.resincronizar'],

            ['grupo' => 'Coincidencias', 'nombre' => 'Ver coincidencias', 'slug' => 'coincidencias.ver'],

            ['grupo' => 'Pruebas', 'nombre' => 'Ver pruebas de conectividad', 'slug' => 'pruebas.ver'],
            ['grupo' => 'Pruebas', 'nombre' => 'Probar login', 'slug' => 'pruebas.login'],
            ['grupo' => 'Pruebas', 'nombre' => 'Probar webhook', 'slug' => 'pruebas.webhook'],
            ['grupo' => 'Pruebas', 'nombre' => 'Desactivar reporte', 'slug' => 'pruebas.desactivar'],

            ['grupo' => 'Logs', 'nombre' => 'Ver logs', 'slug' => 'logs.ver'],

            ['grupo' => 'Roles', 'nombre' => 'Ver roles', 'slug' => 'roles.ver'],
            ['grupo' => 'Roles', 'nombre' => 'Crear roles', 'slug' => 'roles.crear'],
            ['grupo' => 'Roles', 'nombre' => 'Editar roles', 'slug' => 'roles.editar'],

            ['grupo' => 'Usuarios', 'nombre' => 'Ver usuarios', 'slug' => 'usuarios.ver'],
            ['grupo' => 'Usuarios', 'nombre' => 'Crear usuarios', 'slug' => 'usuarios.crear'],
            ['grupo' => 'Usuarios', 'nombre' => 'Editar usuarios', 'slug' => 'usuarios.editar'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['slug' => $permission['slug']],
                $permission + [
                    'descripcion' => null,
                    'activo' => true,
                ]
            );
        }

        $roles = [
            [
                'nombre' => 'Superadministrador',
                'slug' => 'superadmin',
                'descripcion' => 'Acceso total al sistema.',
            ],
            [
                'nombre' => 'Administrador',
                'slug' => 'admin',
                'descripcion' => 'Administración operativa general.',
            ],
            [
                'nombre' => 'Operador',
                'slug' => 'operador',
                'descripcion' => 'Operación de reportes, clientes y pruebas.',
            ],
            [
                'nombre' => 'Auditor',
                'slug' => 'auditor',
                'descripcion' => 'Consulta de reportes, coincidencias y logs.',
            ],
            [
                'nombre' => 'Empresa',
                'slug' => 'empresa',
                'descripcion' => 'Usuario vinculado a una empresa específica.',
            ],
        ];

        foreach ($roles as $roleData) {
            Role::updateOrCreate(
                ['slug' => $roleData['slug']],
                $roleData + ['activo' => true]
            );
        }

        $allPermissions = Permission::pluck('id')->toArray();

        $superadmin = Role::where('slug', 'superadmin')->first();
        $superadmin->permissions()->sync($allPermissions);

        $admin = Role::where('slug', 'admin')->first();
        $admin->permissions()->sync(
            Permission::whereNotIn('slug', [
                'roles.crear',
                'roles.editar',
                'usuarios.crear',
            ])->pluck('id')->toArray()
        );

        $operador = Role::where('slug', 'operador')->first();
        $operador->permissions()->sync(
            Permission::whereIn('slug', [
                'dashboard.ver',
                'empresas.ver',
                'clientes.ver',
                'clientes.crear',
                'clientes.editar',
                'clientes.importar',
                'reportes.ver',
                'coincidencias.ver',
                'pruebas.ver',
                'pruebas.login',
                'pruebas.webhook',
                'pruebas.desactivar',
                'logs.ver',
            ])->pluck('id')->toArray()
        );

        $auditor = Role::where('slug', 'auditor')->first();
        $auditor->permissions()->sync(
            Permission::whereIn('slug', [
                'dashboard.ver',
                'empresas.ver',
                'clientes.ver',
                'reportes.ver',
                'coincidencias.ver',
                'logs.ver',
            ])->pluck('id')->toArray()
        );

        $empresa = Role::where('slug', 'empresa')->first();
        $empresa->permissions()->sync(
            Permission::whereIn('slug', [
                'dashboard.ver',
                'clientes.ver',
                'reportes.ver',
                'coincidencias.ver',
            ])->pluck('id')->toArray()
        );

        $usuario = Usuario::updateOrCreate(
            ['email' => 'admin@pui.test'],
            [
                'empresa_id' => null,
                'name' => 'Super Admin PUI',
                'password' => Hash::make('AdminPui2026*'),
                'activo' => true,
            ]
        );

        $usuario->roles()->sync([$superadmin->id]);
    }
}