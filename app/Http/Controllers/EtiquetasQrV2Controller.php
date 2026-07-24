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

        'copias_unidades' => [
            'nullable',
            'array',
        ],

        'copias_unidades.*' => [
            'integer',
            'min:1',
            'max:20',
        ],

        'copias_lotes' => [
            'nullable',
            'array',
        ],

        'copias_lotes.*' => [
            'integer',
            'min:1',
            'max:20',
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
        return redirect()
            ->route('v2.bienes.index')
            ->withErrors([
                'etiquetas' =>
                    'Debes seleccionar al menos una unidad o un lote.',
            ]);
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

    $copiasUnidades = collect(
        $datos['copias_unidades'] ?? []
    );

    $copiasLotes = collect(
        $datos['copias_lotes'] ?? []
    );

    $etiquetas = collect();

    foreach ($unidades as $unidad) {
        $copias = (int) $copiasUnidades->get(
            $unidad->id,
            1
        );

        $copias = max(1, min($copias, 20));

        for ($i = 0; $i < $copias; $i++) {
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
    }

    foreach ($lotes as $lote) {
        $copias = (int) $copiasLotes->get(
            $lote->id,
            1
        );

        $copias = max(1, min($copias, 20));

        for ($i = 0; $i < $copias; $i++) {
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
    }

    return view(
        'v2.etiquetas.imprimir',
        compact('etiquetas')
    );
}
}