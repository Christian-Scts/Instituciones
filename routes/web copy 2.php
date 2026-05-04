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
            ->name('dashboard');

        Route::get('/dashboard/data', [DashboardController::class, 'data'])
            ->name('dashboard.data');

        /*
        |--------------------------------------------------------------------------
        | Empresas
        |--------------------------------------------------------------------------
        */

        Route::resource('/empresas', EmpresaController::class)
            ->names('empresas');

        Route::get('/empresas/{empresa}/panel', [EmpresaPanelController::class, 'show'])
            ->name('empresas.panel');

        /*
        |--------------------------------------------------------------------------
        | Clientes por empresa
        |--------------------------------------------------------------------------
        */

        Route::prefix('empresas/{empresa}')
            ->name('empresas.')
            ->group(function () {

                Route::get('/clientes', [EmpresaClienteController::class, 'index'])
                    ->name('clientes.index');

                Route::get('/clientes/create', [EmpresaClienteController::class, 'create'])
                    ->name('clientes.create');

                Route::post('/clientes', [EmpresaClienteController::class, 'store'])
                    ->name('clientes.store');

                Route::get('/clientes/{cliente}/edit', [EmpresaClienteController::class, 'edit'])
                    ->name('clientes.edit');

                Route::put('/clientes/{cliente}', [EmpresaClienteController::class, 'update'])
                    ->name('clientes.update');

                Route::delete('/clientes/{cliente}', [EmpresaClienteController::class, 'destroy'])
                    ->name('clientes.destroy');

                Route::get('/clientes/importar', [EmpresaClienteController::class, 'importForm'])
                    ->name('clientes.import.form');

                Route::post('/clientes/importar', [EmpresaClienteController::class, 'import'])
                    ->name('clientes.import');

                Route::get('/clientes/importaciones', [EmpresaClienteController::class, 'importsIndex'])
                    ->name('clientes.imports.index');
            });

        /*
        |--------------------------------------------------------------------------
        | Logs
        |--------------------------------------------------------------------------
        */

        Route::get('/logs', [LogController::class, 'index'])
            ->name('logs.index');

        Route::get('/logs/{log}', [LogController::class, 'show'])
            ->name('logs.show');

        /*
        |--------------------------------------------------------------------------
        | Pruebas de conectividad
        |--------------------------------------------------------------------------
        */

        Route::get('/pruebas', [PruebaController::class, 'index'])
            ->name('pruebas.index');

        Route::post('/pruebas/{empresa}/webhook', [PruebaController::class, 'webhook'])
            ->name('pruebas.webhook');

        Route::post('/pruebas/{empresa}/login-pui', [PruebaController::class, 'loginPui'])
            ->name('pruebas.loginPui');

        Route::post('/pruebas/{empresa}/desactivar-reporte', [PruebaController::class, 'desactivarReporte'])
            ->name('pruebas.desactivar');

        /*
        |--------------------------------------------------------------------------
        | Reportes
        |--------------------------------------------------------------------------
        */

        Route::get('/reportes', [ReporteController::class, 'index'])
            ->name('reportes.index');

        Route::get('/reportes/{reporte}', [ReporteController::class, 'show'])
            ->name('reportes.show');

        /*
        |--------------------------------------------------------------------------
        | Seguridad
        |--------------------------------------------------------------------------
        */

        Route::get('/seguridad', [SeguridadController::class, 'index'])
            ->name('seguridad.index');

        Route::post('/seguridad', [SeguridadController::class, 'store'])
            ->name('seguridad.store');

            /*
        |--------------------------------------------------------------------------
        | Usuarios
        |--------------------------------------------------------------------------
        */
        Route::resource('/usuarios', UsuarioController::class)
            ->names('usuarios');

        /*
        |--------------------------------------------------------------------------
        | Roles
        |--------------------------------------------------------------------------
        */
        Route::resource('/roles', RoleController::class)
            ->names('roles');

        /*
        |--------------------------------------------------------------------------
        | Permisos
        |--------------------------------------------------------------------------
        */
        Route::resource('/permissions', PermissionController::class)
            ->names('permissions');

        /*
        |--------------------------------------------------------------------------
        | Menú administrativo
        |--------------------------------------------------------------------------
        */
        Route::resource('/menu-grupos', MenuGrupoController::class)
            ->names('menu-grupos');

        Route::resource('/menu-elementos', MenuElementoController::class)
            ->names('menu-elementos');
    });