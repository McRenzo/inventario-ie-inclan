<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BienController;
use Illuminate\Support\Facades\Route;

// Ruta Raíz
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('auth.login');
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
});

// Rutas de Perfil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';