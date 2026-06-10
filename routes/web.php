<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BienController; // 1. Subimos el cerebro de la gestión de bienes
use Illuminate\Support\Facades\Route;

// Ruta Raíz: Si ya está logueado va al dashboard, si no, ve el Login moderno que puliste
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('auth.login');
});

// 2. Bloque de rutas protegidas para usuarios verificados
Route::middleware(['auth', 'verified'])->group(function () {
    
    // El "Policía de Tránsito": Identifica el rol en MySQL y muestra la vista correcta
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->role === 'logistica') {
            return view('dashboard'); // Tu panel macro de escritorio
        }

        return view('soporte.dashboard'); // Tu panel operativo para celular/campo
    })->name('dashboard');

    // La ruta que causaba el error (Módulo de Bienes)
    Route::get('/bienes', [BienController::class, 'index'])->name('bienes.index');
});

// Rutas del Perfil de Usuario (Estándar de Laravel Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';