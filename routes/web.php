<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BienController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BienV2Controller;
use App\Http\Controllers\UnidadBienV2Controller;
use App\Http\Controllers\LoteV2Controller;
use App\Http\Controllers\DetalleInventarioV2Controller;
use App\Http\Controllers\BusquedaCodigoV2Controller;
use App\Http\Controllers\EtiquetasQrV2Controller;
use App\Http\Controllers\PrestamoV2Controller;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransferenciaV2Controller;
use App\Http\Controllers\AreaV2Controller;
use App\Http\Controllers\UbicacionV2Controller;

// Ruta Raíz
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('auth.login');
});

Route::middleware(['auth'])->prefix('v2')->name('v2.')->group(function () {
    Route::get('/bienes', [BienV2Controller::class, 'index'])
        ->name('bienes.index');

    Route::get('/bienes/crear', [BienV2Controller::class, 'create'])
        ->name('bienes.create');

    Route::post('/bienes', [BienV2Controller::class, 'store'])
        ->name('bienes.store');
    
    Route::get('/prestamos/unidades/{unidad}/crear', [PrestamoV2Controller::class, 'createUnidad'])
        ->name('prestamos.unidades.create');

    Route::get('/prestamos/lotes/{lote}/crear', [PrestamoV2Controller::class, 'createLote'])
        ->name('prestamos.lotes.create');

    Route::post('/prestamos', [PrestamoV2Controller::class, 'store'])
        ->name('prestamos.store');

    Route::get('/prestamos', [PrestamoV2Controller::class, 'index'])
        ->name('prestamos.index');

    Route::get('/prestamos/{prestamo}', [PrestamoV2Controller::class, 'show'])
        ->name('prestamos.show');

    Route::put('/prestamos/{prestamo}/devolver', [PrestamoV2Controller::class, 'devolver'])
        ->name('prestamos.devolver');

    Route::put('/prestamos/{prestamo}/cancelar', [PrestamoV2Controller::class, 'cancelar'])
        ->name('prestamos.cancelar');

    Route::get('/unidades/crear', [UnidadBienV2Controller::class, 'create'])
        ->name('unidades.create');

    Route::post('/unidades', [UnidadBienV2Controller::class, 'store'])
        ->name('unidades.store');

    Route::get('/lotes/crear', [LoteV2Controller::class, 'create'])
        ->name('lotes.create');

    Route::post('/lotes', [LoteV2Controller::class, 'store'])
        ->name('lotes.store');

    Route::get('/bienes/exportar', [BienV2Controller::class, 'exportarExcel'])
        ->name('bienes.exportar');
    
    Route::get('/bienes/{bien}/editar', [BienV2Controller::class, 'edit'])
        ->name('bienes.edit');

    Route::put('/bienes/{bien}', [BienV2Controller::class, 'update'])
        ->name('bienes.update');

    Route::get('/buscar-codigo', [BusquedaCodigoV2Controller::class, 'buscar'])
        ->name('buscar-codigo');

    Route::post('/etiquetas/imprimir', [EtiquetasQrV2Controller::class, 'imprimir'])
        ->name('etiquetas.imprimir');

    Route::get('/unidades/{unidad}/editar', [UnidadBienV2Controller::class, 'edit'])
        ->name('unidades.edit');

    Route::put('/unidades/{unidad}', [UnidadBienV2Controller::class, 'update'])
        ->name('unidades.update');

    Route::get('/unidades/{unidad}', [DetalleInventarioV2Controller::class, 'unidad'])
        ->name('unidades.show');

    Route::get('/lotes/{lote}/editar', [LoteV2Controller::class, 'edit'])
        ->name('lotes.edit');

    Route::put('/lotes/{lote}', [LoteV2Controller::class, 'update'])
        ->name('lotes.update');

    Route::get('/lotes/{lote}', [DetalleInventarioV2Controller::class, 'lote'])
        ->name('lotes.show');

    Route::get('/transferencias/unidades/{unidad}/crear', [TransferenciaV2Controller::class, 'createUnidad'])
        ->name('transferencias.unidades.create');

    Route::get('/transferencias/lotes/{lote}/crear', [TransferenciaV2Controller::class, 'createLote'])
        ->name('transferencias.lotes.create');

    Route::post('/transferencias', [TransferenciaV2Controller::class, 'store'])
        ->name('transferencias.store');

    Route::get('/parametros', function () {
        return view('v2.parametros.index');
    })->name('parametros.index');

    Route::get(
        '/parametros/areas',
        [AreaV2Controller::class, 'index']
    )->name('parametros.areas.index');

    Route::post(
        '/parametros/areas',
        [AreaV2Controller::class, 'store']
    )->name('parametros.areas.store');

    Route::put(
        '/parametros/areas/{area}',
        [AreaV2Controller::class, 'update']
    )->name('parametros.areas.update');

    Route::patch(
        '/parametros/areas/{area}/estado',
        [AreaV2Controller::class, 'cambiarEstado']
    )->name('parametros.areas.estado');

    Route::get(
        '/parametros/ubicaciones',
        [UbicacionV2Controller::class, 'index']
    )->name('parametros.ubicaciones.index');

    Route::post(
        '/parametros/ubicaciones',
        [UbicacionV2Controller::class, 'store']
    )->name('parametros.ubicaciones.store');

    Route::put(
        '/parametros/ubicaciones/{ubicacion}',
        [UbicacionV2Controller::class, 'update']
    )->name('parametros.ubicaciones.update');

    Route::patch(
        '/parametros/ubicaciones/{ubicacion}/estado',
        [UbicacionV2Controller::class, 'cambiarEstado']
    )->name('parametros.ubicaciones.estado');

    Route::get(
        '/lotes/{lote}/fusionar',
        [TransferenciaV2Controller::class, 'createFusion']
    )->name('lotes.fusion.create');

    Route::post(
        '/lotes/{lote}/fusionar',
        [TransferenciaV2Controller::class, 'storeFusion']
    )->name('lotes.fusion.store');
});

// Bloque de rutas protegidas
Route::middleware(['auth', 'verified'])->group(function () {
    
    // RUTA DASHBOARD UNIFICADA Y CORRECTA
    Route::get(
        '/dashboard',
        [DashboardController::class, 'index']
    )->name('dashboard');

    // Módulo de Bienes
    Route::get('/bienes', [BienController::class, 'index'])->name('bienes.index');
    Route::get('/bienes/importar', [BienController::class, 'importForm'])->name('bienes.import.form');
    Route::post('/bienes/importar', [BienController::class, 'import'])->name('bienes.import');
    Route::post('/bienes', [BienController::class, 'store'])->name('bienes.store');
    Route::get('/bienes/{bien}/edit', [BienController::class, 'edit'])->name('bienes.edit');
    Route::put('/bienes/{bien}', [BienController::class, 'update'])->name('bienes.update');
    Route::delete('/bienes/{bien}', [BienController::class, 'destroy'])->name('bienes.destroy');
    Route::post('/bienes/imprimir', [App\Http\Controllers\BienController::class, 'imprimir'])->name('bienes.imprimir');
    Route::get('/bienes/escanear', [BienController::class, 'escanear'])->name('bienes.escanear');
    Route::get('/bienes/reportes', [BienController::class, 'reportes'])->name('bienes.reportes');
    Route::get('/bienes/exportar', [BienController::class, 'exportarExcel'])->name('bienes.exportar');
});

// Rutas de Perfil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';