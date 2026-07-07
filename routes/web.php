<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BienController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BienV2Controller;
use App\Http\Controllers\UnidadBienV2Controller;
use App\Http\Controllers\LoteV2Controller;
use App\Http\Controllers\DetalleInventarioV2Controller;

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

    Route::get('/unidades/crear', [UnidadBienV2Controller::class, 'create'])
    ->name('unidades.create');

    Route::post('/unidades', [UnidadBienV2Controller::class, 'store'])
        ->name('unidades.store');

    Route::get('/lotes/crear', [LoteV2Controller::class, 'create'])
    ->name('lotes.create');

    Route::post('/lotes', [LoteV2Controller::class, 'store'])
        ->name('lotes.store');

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
});

// Bloque de rutas protegidas
Route::middleware(['auth', 'verified'])->group(function () {
    
    // RUTA DASHBOARD UNIFICADA Y CORRECTA
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->role === 'logistica') {
            $data = [
                'totalActivos'       => \App\Models\Bien::count(),
                'activosDisponibles' => \App\Models\Bien::where('estado_actual', 'disponible')->count(),
                'enMantenimiento'    => \App\Models\Bien::where('estado_actual', 'en_mantenimiento')->count(),
                'categoriasStats'    => \App\Models\Categoria::withCount('bienes')->get(),
            ];

            return view('dashboard', compact('data'));
        }

        return view('soporte.dashboard');
    })->name('dashboard');

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