<?php

namespace App\Http\Controllers;

use App\Models\Lote;
use App\Models\UnidadBien;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BusquedaCodigoV2Controller extends Controller
{
    public function buscar(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'codigo' => [
                'required',
                'string',
                'max:50',
            ],
        ]);

        $codigo = strtoupper(trim($datos['codigo']));

        if (str_starts_with($codigo, 'INC-IND-')) {
            $unidad = UnidadBien::query()
                ->where('codigo_interno', $codigo)
                ->first();

            if ($unidad) {
                return redirect()
                    ->route('v2.unidades.show', $unidad);
            }
        }

        if (str_starts_with($codigo, 'INC-LOT-')) {
            $lote = Lote::query()
                ->where('codigo_interno', $codigo)
                ->first();

            if ($lote) {
                return redirect()
                    ->route('v2.lotes.show', $lote);
            }
        }

        return redirect()
            ->route('bienes.escanear')
            ->withInput()
            ->withErrors([
                'codigo' =>
                    "No se encontró ningún activo con el código {$codigo}.",
            ]);
    }
}