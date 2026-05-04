<?php

use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmpresaClienteController;
use App\Http\Controllers\Admin\EmpresaController;
use App\Http\Controllers\Admin\EmpresaPanelController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\PruebaController;
use App\Http\Controllers\Admin\ReporteController;
use App\Http\Controllers\Admin\SeguridadController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\MenuGrupoController;
use App\Http\Controllers\Admin\MenuElementoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Login administrativo
|--------------------------------------------------------------------------
*/

Route::get('/login', [AdminLoginController::class, 'showLogin'])
    ->name('admin.login');

Route::post('/login', [AdminLoginController::class, 'login'])
    ->name('admin.login.post');

Route::post('/logout', [AdminLoginController::class, 'logout'])
    ->name('admin.logout');


/*
|--------------------------------------------------------------------------
| Panel administrativo protegido
|--------------------------------------------------------------------------
*/

Route::middleware(['admin.auth', 'method.override.safe'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->middleware('permission:dashboard.ver')
            ->name('dashboard');

        Route::get('/dashboard/data', [DashboardController::class, 'data'])
            ->middleware('permission:dashboard.ver')
            ->name('dashboard.data');

        Route::resource('/empresas', EmpresaController::class)
            ->names('empresas')
            ->middleware([
                'index' => 'permission:empresas.ver',
                'create' => 'permission:empresas.crear',
                'store' => 'permission:empresas.crear',
                'edit' => 'permission:empresas.editar',
                'update' => 'permission:empresas.editar',
            ]);

        Route::get('/empresas/{empresa}/panel', [EmpresaPanelController::class, 'show'])
            ->middleware('permission:empresas.ver')
            ->name('empresas.panel');

        Route::prefix('empresas/{empresa}')
            ->name('empresas.')
            ->group(function () {
                Route::get('/clientes', [EmpresaClienteController::class, 'index'])
                    ->middleware('permission:clientes.ver')
                    ->name('clientes.index');

                Route::get('/clientes/create', [EmpresaClienteController::class, 'create'])
                    ->middleware('permission:clientes.crear')
                    ->name('clientes.create');

                Route::post('/clientes', [EmpresaClienteController::class, 'store'])
                    ->middleware('permission:clientes.crear')
                    ->name('clientes.store');

                Route::get('/clientes/{cliente}/edit', [EmpresaClienteController::class, 'edit'])
                    ->middleware('permission:clientes.editar')
                    ->name('clientes.edit');

                Route::put('/clientes/{cliente}', [EmpresaClienteController::class, 'update'])
                    ->middleware('permission:clientes.editar')
                    ->name('clientes.update');

                Route::delete('/clientes/{cliente}', [EmpresaClienteController::class, 'destroy'])
                    ->middleware('permission:clientes.editar')
                    ->name('clientes.destroy');

                Route::get('/clientes/importar', [EmpresaClienteController::class, 'importForm'])
                    ->middleware('permission:clientes.importar')
                    ->name('clientes.import.form');

                Route::post('/clientes/importar', [EmpresaClienteController::class, 'import'])
                    ->middleware('permission:clientes.importar')
                    ->name('clientes.import');

                Route::get('/clientes/importaciones', [EmpresaClienteController::class, 'importsIndex'])
                    ->middleware('permission:clientes.importar')
                    ->name('clientes.imports.index');
            });

        Route::get('/logs', [LogController::class, 'index'])
            ->middleware('permission:logs.ver')
            ->name('logs.index');

        Route::get('/logs/{log}', [LogController::class, 'show'])
            ->middleware('permission:logs.ver')
            ->name('logs.show');

        Route::get('/pruebas', [PruebaController::class, 'index'])
            ->middleware('permission:pruebas.ver')
            ->name('pruebas.index');

        Route::post('/pruebas/{empresa}/webhook', [PruebaController::class, 'webhook'])
            ->middleware('permission:pruebas.webhook')
            ->name('pruebas.webhook');

        Route::post('/pruebas/{empresa}/login-pui', [PruebaController::class, 'loginPui'])
            ->middleware('permission:pruebas.login')
            ->name('pruebas.loginPui');

        Route::post('/pruebas/{empresa}/desactivar-reporte', [PruebaController::class, 'desactivarReporte'])
            ->middleware('permission:pruebas.desactivar')
            ->name('pruebas.desactivar');

        Route::get('/reportes', [ReporteController::class, 'index'])
            ->middleware('permission:reportes.ver')
            ->name('reportes.index');

        Route::get('/reportes/{reporte}', [ReporteController::class, 'show'])
            ->middleware('permission:reportes.ver')
            ->name('reportes.show');

        Route::get('/seguridad', [SeguridadController::class, 'index'])
            ->middleware('permission:seguridad.ver')
            ->name('seguridad.index');

        Route::post('/seguridad', [SeguridadController::class, 'store'])
            ->middleware('permission:seguridad.ver')
            ->name('seguridad.store');

        Route::resource('/usuarios', UsuarioController::class)
            ->names('usuarios')
            ->middleware([
                'index' => 'permission:usuarios.ver',
                'create' => 'permission:usuarios.crear',
                'store' => 'permission:usuarios.crear',
                'edit' => 'permission:usuarios.editar',
                'update' => 'permission:usuarios.editar',
                'destroy' => 'permission:usuarios.editar',
            ]);

        Route::resource('/roles', RoleController::class)
            ->names('roles')
            ->middleware([
                'index' => 'permission:roles.ver',
                'create' => 'permission:roles.crear',
                'store' => 'permission:roles.crear',
                'edit' => 'permission:roles.editar',
                'update' => 'permission:roles.editar',
                'destroy' => 'permission:roles.editar',
            ]);

        Route::resource('/permissions', PermissionController::class)
            ->names('permissions')
            ->middleware([
                'index' => 'permission:permissions.ver',
                'create' => 'permission:permissions.crear',
                'store' => 'permission:permissions.crear',
                'edit' => 'permission:permissions.editar',
                'update' => 'permission:permissions.editar',
                'destroy' => 'permission:permissions.editar',
            ]);

        Route::resource('/menu-grupos', MenuGrupoController::class)
            ->names('menu-grupos')
            ->middleware([
                'index' => 'permission:roles.editar',
                'create' => 'permission:roles.editar',
                'store' => 'permission:roles.editar',
                'edit' => 'permission:roles.editar',
                'update' => 'permission:roles.editar',
                'destroy' => 'permission:roles.editar',
            ]);

        Route::resource('/menu-elementos', MenuElementoController::class)
            ->names('menu-elementos')
            ->middleware([
                'index' => 'permission:roles.editar',
                'create' => 'permission:roles.editar',
                'store' => 'permission:roles.editar',
                'edit' => 'permission:roles.editar',
                'update' => 'permission:roles.editar',
                'destroy' => 'permission:roles.editar',
            ]);
    });