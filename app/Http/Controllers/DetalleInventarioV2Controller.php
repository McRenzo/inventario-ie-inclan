<?php

namespace App\Http\Controllers;

use App\Models\Lote;
use App\Models\UnidadBien;
use Illuminate\View\View;

class DetalleInventarioV2Controller extends Controller
{
    public function unidad(UnidadBien $unidad): View
    {
        $unidad->load([
            'bien.categoria',
            'bien.fuenteFinanciamiento',
            'area',
            'ubicacion',
            'estadoConservacion',
            'estadoOperatividad',
            'creador',
            'movimientos' => function ($query) {
                $query
                    ->with([
                        'areaAnterior',
                        'areaNueva',
                        'ubicacionAnterior',
                        'ubicacionNueva',
                        'estadoConservacionAnterior',
                        'estadoConservacionNuevo',
                        'estadoOperatividadAnterior',
                        'estadoOperatividadNuevo',
                        'usuario',
                    ])
                    ->latest('fecha_movimiento');
            },
        ]);

        $prestamoActivo = \App\Models\Prestamo::query()
            ->where('unidad_bien_id', $unidad->id)
            ->whereIn('estado', ['activo', 'vencido'])
            ->latest('fecha_prestamo')
            ->first();

        return view(
            'v2.detalles.unidad',
            compact('unidad', 'prestamoActivo')
        );
    }

    public function lote(Lote $lote): View
    {
        $lote->load([
            'bien.categoria',
            'bien.fuenteFinanciamiento',
            'area',
            'ubicacion',
            'estadoConservacion',
            'estadoOperatividad',
            'creador',
            'movimientos' => function ($query) {
                $query
                    ->with([
                        'areaAnterior',
                        'areaNueva',
                        'ubicacionAnterior',
                        'ubicacionNueva',
                        'estadoConservacionAnterior',
                        'estadoConservacionNuevo',
                        'estadoOperatividadAnterior',
                        'estadoOperatividadNuevo',
                        'usuario',
                    ])
                    ->latest('fecha_movimiento');
            },
        ]);

        return view('v2.detalles.lote', compact('lote'));
    }
}