<?php

namespace App\Http\Controllers;

use App\Models\Lote;
use App\Models\UnidadBien;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EtiquetasQrV2Controller extends Controller
{
    public function imprimir(Request $request): View
    {
        $datos = $request->validate([
            'unidades' => [
                'nullable',
                'array',
            ],

            'unidades.*' => [
                'integer',
                'exists:unidades_bien,id',
            ],

            'lotes' => [
                'nullable',
                'array',
            ],

            'lotes.*' => [
                'integer',
                'exists:lotes,id',
            ],
        ]);

        $idsUnidades = collect($datos['unidades'] ?? [])
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        $idsLotes = collect($datos['lotes'] ?? [])
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        if ($idsUnidades->isEmpty() && $idsLotes->isEmpty()) {
            abort(
                422,
                'Debes seleccionar al menos una unidad o un lote.'
            );
        }

        $unidades = UnidadBien::query()
            ->with([
                'bien',
                'area',
                'ubicacion',
            ])
            ->whereIn('id', $idsUnidades)
            ->orderBy('codigo_interno')
            ->get();

        $lotes = Lote::query()
            ->with([
                'bien',
                'area',
                'ubicacion',
            ])
            ->whereIn('id', $idsLotes)
            ->orderBy('codigo_interno')
            ->get();

        $etiquetas = collect();

        foreach ($unidades as $unidad) {
            $etiquetas->push([
                'tipo' => 'unidad',
                'codigo' => $unidad->codigo_interno,
                'nombre' => $unidad->bien?->nombre
                    ?? 'Bien sin nombre',
                'serie' => $unidad->numero_serie,
                'area' => $unidad->area?->nombre,
                'ubicacion' => $unidad->ubicacion?->nombre,
                'url' => route(
                    'v2.unidades.show',
                    $unidad
                ),
            ]);
        }

        foreach ($lotes as $lote) {
            $etiquetas->push([
                'tipo' => 'lote',
                'codigo' => $lote->codigo_interno,
                'nombre' => $lote->bien?->nombre
                    ?? 'Bien sin nombre',
                'cantidad' => $lote->cantidad_actual,
                'unidad_medida' => $lote->unidad_medida,
                'area' => $lote->area?->nombre,
                'ubicacion' => $lote->ubicacion?->nombre,
                'url' => route(
                    'v2.lotes.show',
                    $lote
                ),
            ]);
        }

        return view(
            'v2.etiquetas.imprimir',
            compact('etiquetas')
        );
    }
}