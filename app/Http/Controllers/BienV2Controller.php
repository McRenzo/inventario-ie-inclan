<?php

namespace App\Http\Controllers;

use App\Models\Bien;
use App\Models\Lote;
use App\Models\UnidadBien;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Categoria;
use App\Models\FuenteFinanciamiento;
use Illuminate\Http\RedirectResponse;

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

    public function create(): View
    {
        $categorias = Categoria::query()
            ->where('activo', true)
            ->orderBy('nombre')
            ->get();

        $fuentes = FuenteFinanciamiento::query()
            ->where('activo', true)
            ->orderBy('nombre')
            ->get();

        return view('v2.bienes.create', compact(
            'categorias',
            'fuentes'
        ));
    }

    public function edit(Bien $bien): View
    {
        $categorias = Categoria::query()
            ->where('activo', true)
            ->orderBy('nombre')
            ->get();

        $fuentes = FuenteFinanciamiento::query()
            ->where('activo', true)
            ->orderBy('nombre')
            ->get();

        return view('v2.bienes.edit', compact(
            'bien',
            'categorias',
            'fuentes'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'categoria_id' => ['nullable', 'exists:categorias,id'],
            'tipo_control' => [
                'required',
                'in:individual,lote,consumible',
            ],
            'marca' => ['nullable', 'string', 'max:255'],
            'modelo' => ['nullable', 'string', 'max:255'],
            'material' => ['nullable', 'string', 'max:255'],
            'nivel_educativo' => ['nullable', 'string', 'max:255'],
            'ciclo' => ['nullable', 'string', 'max:255'],
            'procedencia' => ['nullable', 'string', 'max:255'],
            'fuente_financiamiento_id' => [
                'nullable',
                'exists:fuentes_financiamiento,id',
            ],
            'es_depreciable' => ['nullable', 'boolean'],
            'vida_util_meses' => [
                'nullable',
                'integer',
                'min:1',
                'max:1200',
            ],
            'valor_residual_porcentaje' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],
            'observaciones' => ['nullable', 'string'],
        ]);

        $datos['es_depreciable'] = $request->boolean('es_depreciable');
        $datos['activo'] = true;

        if (! $datos['es_depreciable']) {
            $datos['vida_util_meses'] = null;
            $datos['valor_residual_porcentaje'] = 0;
        }

        $bien = Bien::create($datos);

        return redirect()
            ->route('v2.bienes.index')
            ->with(
                'success',
                "La ficha «{$bien->nombre}» fue creada correctamente."
            );
    }

    public function update(
        Request $request,
        Bien $bien
    ): RedirectResponse {
        $datos = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
            ],

            'descripcion' => [
                'nullable',
                'string',
            ],

            'categoria_id' => [
                'nullable',
                'exists:categorias,id',
            ],

            'tipo_control' => [
                'required',
                'in:individual,lote,consumible',
            ],

            'marca' => [
                'nullable',
                'string',
                'max:255',
            ],

            'modelo' => [
                'nullable',
                'string',
                'max:255',
            ],

            'material' => [
                'nullable',
                'string',
                'max:255',
            ],

            'nivel_educativo' => [
                'nullable',
                'string',
                'max:255',
            ],

            'ciclo' => [
                'nullable',
                'string',
                'max:255',
            ],

            'procedencia' => [
                'nullable',
                'string',
                'max:255',
            ],

            'fuente_financiamiento_id' => [
                'nullable',
                'exists:fuentes_financiamiento,id',
            ],

            'es_depreciable' => [
                'nullable',
                'boolean',
            ],

            'vida_util_meses' => [
                'nullable',
                'integer',
                'min:1',
                'max:1200',
            ],

            'valor_residual_porcentaje' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'observaciones' => [
                'nullable',
                'string',
            ],
        ]);

        $datos['es_depreciable'] = $request->boolean(
            'es_depreciable'
        );

        if (! $datos['es_depreciable']) {
            $datos['vida_util_meses'] = null;
            $datos['valor_residual_porcentaje'] = 0;
        }

        $tipoAnterior = $bien->tipo_control;
        $tipoNuevo = $datos['tipo_control'];

        $tieneUnidades = $bien->unidades()
            ->exists();

        $tieneLotes = $bien->lotes()
            ->exists();

        if (
            $tipoAnterior !== $tipoNuevo
            && ($tieneUnidades || $tieneLotes)
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'tipo_control' =>
                        'No se puede cambiar el tipo de control porque esta ficha ya tiene unidades o lotes registrados.',
                ]);
        }

        $bien->update($datos);

        return redirect()
            ->route('v2.bienes.index')
            ->with(
                'success',
                "La ficha «{$bien->nombre}» fue actualizada correctamente."
            );
    }
}