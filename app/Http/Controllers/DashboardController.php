<?php

namespace App\Http\Controllers;

use App\Models\Lote;
use App\Models\Prestamo;
use App\Models\UnidadBien;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        if (!in_array($user->role, ['logistica', 'admin', 'administrador'], true)) {
            return view('soporte.dashboard');
        }

        Prestamo::query()
            ->where('estado', 'activo')
            ->whereNotNull('fecha_devolucion_prevista')
            ->where('fecha_devolucion_prevista', '<', now())
            ->update([
                'estado' => 'vencido',
            ]);

        $data = [
            'totalUnidades' => \App\Models\UnidadBien::count(),

            'totalLotes' => \App\Models\Lote::count(),

            'totalActivos' =>
                \App\Models\UnidadBien::count()
                + \App\Models\Lote::count(),

            'activosDisponibles' =>
                \App\Models\UnidadBien::query()
                    ->where('situacion', 'disponible')
                    ->count()
                +
                \App\Models\Lote::query()
                    ->where('situacion', 'disponible')
                    ->where('cantidad_actual', '>', 0)
                    ->count(),

            'valorAdquisicionTotal' => UnidadBien::query()
                ->sum('valor_adquisicion'),

            'valorEnLibrosTotal' => UnidadBien::query()
                ->sum('valor_en_libros'),

            'valorPatrimonial' => UnidadBien::query()
                ->selectRaw('SUM(COALESCE(valor_ajustado, valor_en_libros, 0)) as total')
                ->value('total') ?? 0,

            'bienesValorizados' => UnidadBien::query()
                ->where('valor_adquisicion', '>', 0)
                ->count(),

            'bienesPendientesValorizacion' => UnidadBien::query()
                ->where(function ($query) {
                    $query->whereNull('valor_adquisicion')
                        ->orWhere('valor_adquisicion', '<=', 0);
                })
                ->count(),

            'bienesConValorAjustado' => UnidadBien::query()
                ->whereNotNull('valor_ajustado')
                ->count(),
                
            'enMantenimiento' =>
                \App\Models\UnidadBien::query()
                    ->where('situacion', 'en_mantenimiento')
                    ->count()
                +
                \App\Models\Lote::query()
                    ->where('situacion', 'en_mantenimiento')
                    ->count(),

            'prestamosActivos' => Prestamo::query()
                ->where('estado', 'activo')
                ->count(),

            'prestamosVencidos' => Prestamo::query()
                ->where('estado', 'vencido')
                ->count(),

            'prestamosDevueltos' => Prestamo::query()
                ->where('estado', 'devuelto')
                ->count(),

            'prestamosCancelados' => Prestamo::query()
                ->where('estado', 'cancelado')
                ->count(),

            'proximosAVencer' => Prestamo::query()
                ->where('estado', 'activo')
                ->whereNotNull('fecha_devolucion_prevista')
                ->whereBetween(
                    'fecha_devolucion_prevista',
                    [
                        now(),
                        now()->copy()->addDays(3),
                    ]
                )
                ->count(),

            'ultimosPrestamos' => Prestamo::query()
                ->with([
                    'unidad.bien',
                    'lote.bien',
                ])
                ->latest('fecha_prestamo')
                ->limit(5)
                ->get(),
        ];

        return view('dashboard', compact('data'));
    }
}