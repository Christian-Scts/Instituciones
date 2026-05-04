<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmpresaController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\PruebaController;
use App\Http\Controllers\Admin\ReporteController;
use App\Http\Controllers\Admin\SeguridadController;
use App\Http\Controllers\Admin\EmpresaClienteController;
use App\Http\Controllers\Admin\EmpresaPanelController;
use App\Http\Controllers\Admin\Auth\AdminLoginController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AdminLoginController::class, 'showLogin'])
    ->name('admin.login');

Route::post('/login', [AdminLoginController::class, 'login'])
    ->name('admin.login.post');

Route::post('/logout', [AdminLoginController::class, 'logout'])
    ->name('admin.logout');


Route::middleware(['web', 'method.override.safe'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
     Route::get('/dashboard/data', [DashboardController::class, 'data'])->name('admin.dashboard.data');

    Route::resource('/empresas', EmpresaController::class)->names('admin.empresas');

    Route::get('/logs', [LogController::class, 'index'])->name('admin.logs.index');
    Route::get('/logs/{log}', [LogController::class, 'show'])->name('admin.logs.show');

    Route::get('/pruebas', [PruebaController::class, 'index'])->name('admin.pruebas.index');
    Route::post('/pruebas/{empresa}/webhook', [PruebaController::class, 'webhook'])->name('admin.pruebas.webhook');
    
    Route::post('/pruebas/{empresa}/login-pui', [PruebaController::class, 'loginPui'])->name('admin.pruebas.loginPui');
    Route::post('/admin/pruebas/{empresa}/desactivar-reporte', [PruebaController::class, 'desactivarReporte'])
    ->name('admin.pruebas.desactivar');

    Route::get('/reportes', [ReporteController::class, 'index'])->name('admin.reportes.index');
    Route::get('/reportes/{reporte}', [ReporteController::class, 'show'])->name('admin.reportes.show');

    Route::get('/seguridad', [SeguridadController::class, 'index'])->name('admin.seguridad.index');
    Route::post('/seguridad', [SeguridadController::class, 'store'])->name('admin.seguridad.store');
});

Route::get('/admin/empresas/{empresa}/panel', [EmpresaPanelController::class, 'show'])
    ->name('admin.empresas.panel');
    
Route::prefix('admin/empresas/{empresa}')->name('admin.empresas.')->group(function () {
    Route::get('/clientes', [EmpresaClienteController::class, 'index'])->name('clientes.index');
    Route::get('/clientes/create', [EmpresaClienteController::class, 'create'])->name('clientes.create');
    Route::post('/clientes', [EmpresaClienteController::class, 'store'])->name('clientes.store');
    Route::get('/clientes/{cliente}/edit', [EmpresaClienteController::class, 'edit'])->name('clientes.edit');
    Route::put('/clientes/{cliente}', [EmpresaClienteController::class, 'update'])->name('clientes.update');
    Route::delete('/clientes/{cliente}', [EmpresaClienteController::class, 'destroy'])->name('clientes.destroy');

    Route::get('/clientes/importar', [EmpresaClienteController::class, 'importForm'])->name('clientes.import.form');
    Route::post('/clientes/importar', [EmpresaClienteController::class, 'import'])->name('clientes.import');

    Route::get('/clientes/importaciones', [EmpresaClienteController::class, 'importsIndex'])
    ->name('clientes.imports.index');

    
});