<?php

namespace App\Http\Controllers;

use App\Models\Bien;
use App\Models\Lote;
use App\Models\UnidadBien;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BienV2Controller extends Controller
{
    public function index(Request $request): View
    {
        $busqueda = trim((string) $request->input('buscar'));

        $unidades = UnidadBien::query()
            ->with([
                'bien.categoria',
                'area',
                'ubicacion',
                'estadoConservacion',
                'estadoOperatividad',
            ])
            ->when($busqueda !== '', function ($query) use ($busqueda) {
                $query->where(function ($subquery) use ($busqueda) {
                    $subquery
                        ->where('codigo_interno', 'like', "%{$busqueda}%")
                        ->orWhere('codigo_patrimonial', 'like', "%{$busqueda}%")
                        ->orWhere('numero_serie', 'like', "%{$busqueda}%")
                        ->orWhere('responsable_nombre', 'like', "%{$busqueda}%")
                        ->orWhereHas('bien', function ($bienQuery) use ($busqueda) {
                            $bienQuery
                                ->where('nombre', 'like', "%{$busqueda}%")
                                ->orWhere('descripcion', 'like', "%{$busqueda}%")
                                ->orWhere('marca', 'like', "%{$busqueda}%")
                                ->orWhere('modelo', 'like', "%{$busqueda}%");
                        });
                });
            })
            ->latest()
            ->paginate(15, ['*'], 'unidades_page')
            ->withQueryString();

        $lotes = Lote::query()
            ->with([
                'bien.categoria',
                'area',
                'ubicacion',
                'estadoConservacion',
                'estadoOperatividad',
            ])
            ->when($busqueda !== '', function ($query) use ($busqueda) {
                $query->where(function ($subquery) use ($busqueda) {
                    $subquery
                        ->where('codigo_interno', 'like', "%{$busqueda}%")
                        ->orWhere('responsable_nombre', 'like', "%{$busqueda}%")
                        ->orWhereHas('bien', function ($bienQuery) use ($busqueda) {
                            $bienQuery
                                ->where('nombre', 'like', "%{$busqueda}%")
                                ->orWhere('descripcion', 'like', "%{$busqueda}%")
                                ->orWhere('marca', 'like', "%{$busqueda}%")
                                ->orWhere('modelo', 'like', "%{$busqueda}%");
                        });
                });
            })
            ->latest()
            ->paginate(15, ['*'], 'lotes_page')
            ->withQueryString();

        $resumen = [
            'fichas' => Bien::where('activo', true)->count(),
            'unidades' => UnidadBien::count(),
            'lotes' => Lote::count(),
            'cantidad_lotes' => Lote::sum('cantidad_actual'),
            'valor_unidades' => UnidadBien::sum('valor_en_libros'),
            'valor_lotes' => Lote::sum('valor_en_libros'),
        ];

        $resumen['valor_total'] =
            $resumen['valor_unidades'] + $resumen['valor_lotes'];

        return view('v2.bienes.index', compact(
            'unidades',
            'lotes',
            'resumen',
            'busqueda'
        ));
    }
}