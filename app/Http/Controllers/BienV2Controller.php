<?php

namespace App\Http\Controllers;


use App\Models\Area;
use App\Models\EstadoConservacion;
use App\Models\Ubicacion;
use App\Models\Bien;
use App\Models\Lote;
use App\Models\UnidadBien;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Categoria;
use App\Models\FuenteFinanciamiento;
use Illuminate\Http\RedirectResponse;
use App\Exports\InventarioV2Export;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use App\Imports\InventarioV2Import;

class BienV2Controller extends Controller
{
    public function exportarExcel(
        Request $request
    ): BinaryFileResponse {
        $filtros = [
            'buscar' => $request->input('buscar'),
            'tipo' => $request->input('tipo', 'todos'),
            'categoria_id' => $request->input('categoria_id'),
            'area_id' => $request->input('area_id'),
            'ubicacion_id' => $request->input('ubicacion_id'),
            'estado_conservacion_id' =>
                $request->input('estado_conservacion_id'),
            'situacion' => $request->input('situacion'),
        ];

        $nombreArchivo =
            'inventario_v2_'
            . now()->format('Y-m-d_H-i-s')
            . '.xlsx';

        return Excel::download(
            new InventarioV2Export($filtros),
            $nombreArchivo
        );
    }

    public function importForm(): View
{
    return view('v2.bienes.importar');
}

public function import(Request $request): RedirectResponse
{
    $request->validate([
        'excel_file' => [
            'required',
            'file',
            'mimes:xlsx,xls,csv',
            'max:10240',
        ],
    ]);

    try {
        $importador = new InventarioV2Import();

        Excel::import(
            $importador,
            $request->file('excel_file')
        );

        return redirect()
            ->route('v2.bienes.index')
            ->with(
                'success',
                "Importación completada. Registros importados: "
                . $importador->importados()
                . ". Registros omitidos: "
                . $importador->omitidos()
                . "."
            );
    } catch (\Throwable $e) {
        return back()
            ->withInput()
            ->withErrors([
                'excel_file' =>
                    'Error al importar el archivo: '
                    . $e->getMessage(),
            ]);
    }
}

    public function index(Request $request): View
    {
        $busqueda = trim((string) $request->input('buscar'));

        $tipo = (string) $request->input('tipo', 'todos');
        $categoriaId = $request->integer('categoria_id');
        $areaId = $request->integer('area_id');
        $ubicacionId = $request->integer('ubicacion_id');
        $estadoConservacionId = $request->integer(
            'estado_conservacion_id'
        );
        $situacion = (string) $request->input('situacion');

        $unidadesQuery = UnidadBien::query()
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
                        ->where(
                            'codigo_interno',
                            'like',
                            "%{$busqueda}%"
                        )
                        ->orWhere(
                            'codigo_patrimonial',
                            'like',
                            "%{$busqueda}%"
                        )
                        ->orWhere(
                            'numero_serie',
                            'like',
                            "%{$busqueda}%"
                        )
                        ->orWhere(
                            'responsable_nombre',
                            'like',
                            "%{$busqueda}%"
                        )
                        ->orWhereHas(
                            'bien',
                            function ($bienQuery) use ($busqueda) {
                                $bienQuery
                                    ->where(
                                        'nombre',
                                        'like',
                                        "%{$busqueda}%"
                                    )
                                    ->orWhere(
                                        'descripcion',
                                        'like',
                                        "%{$busqueda}%"
                                    )
                                    ->orWhere(
                                        'marca',
                                        'like',
                                        "%{$busqueda}%"
                                    )
                                    ->orWhere(
                                        'modelo',
                                        'like',
                                        "%{$busqueda}%"
                                    );
                            }
                        );
                });
            })
            ->when(
                $categoriaId > 0,
                fn ($query) => $query->whereHas(
                    'bien',
                    fn ($bienQuery) => $bienQuery->where(
                        'categoria_id',
                        $categoriaId
                    )
                )
            )
            ->when(
                $areaId > 0,
                fn ($query) => $query->where('area_id', $areaId)
            )
            ->when(
                $ubicacionId > 0,
                fn ($query) => $query->where(
                    'ubicacion_id',
                    $ubicacionId
                )
            )
            ->when(
                $estadoConservacionId > 0,
                fn ($query) => $query->where(
                    'estado_conservacion_id',
                    $estadoConservacionId
                )
            )
            ->when(
                $situacion !== '',
                fn ($query) => $query->where(
                    'situacion',
                    $situacion
                )
            );

        $lotesQuery = Lote::query()
            ->with([
                'bien.categoria',
                'area',
                'ubicacion',
                'estadoConservacion',
                'estadoOperatividad',
            ])
            ->where('estado_registro', 'activo')
            ->where('cantidad_actual', '>', 0)
            ->when($busqueda !== '', function ($query) use ($busqueda) {
                $query->where(function ($subquery) use ($busqueda) {
                    $subquery
                        ->where(
                            'codigo_interno',
                            'like',
                            "%{$busqueda}%"
                        )
                        ->orWhere(
                            'responsable_nombre',
                            'like',
                            "%{$busqueda}%"
                        )
                        ->orWhereHas(
                            'bien',
                            function ($bienQuery) use ($busqueda) {
                                $bienQuery
                                    ->where(
                                        'nombre',
                                        'like',
                                        "%{$busqueda}%"
                                    )
                                    ->orWhere(
                                        'descripcion',
                                        'like',
                                        "%{$busqueda}%"
                                    )
                                    ->orWhere(
                                        'marca',
                                        'like',
                                        "%{$busqueda}%"
                                    )
                                    ->orWhere(
                                        'modelo',
                                        'like',
                                        "%{$busqueda}%"
                                    );
                            }
                        );
                });
            })
            ->when(
                $categoriaId > 0,
                fn ($query) => $query->whereHas(
                    'bien',
                    fn ($bienQuery) => $bienQuery->where(
                        'categoria_id',
                        $categoriaId
                    )
                )
            )
            ->when(
                $areaId > 0,
                fn ($query) => $query->where('area_id', $areaId)
            )
            ->when(
                $ubicacionId > 0,
                fn ($query) => $query->where(
                    'ubicacion_id',
                    $ubicacionId
                )
            )
            ->when(
                $estadoConservacionId > 0,
                fn ($query) => $query->where(
                    'estado_conservacion_id',
                    $estadoConservacionId
                )
            )
            ->when(
                $situacion !== '',
                fn ($query) => $query->where(
                    'situacion',
                    $situacion
                )
            );

        $unidades = $unidadesQuery
            ->when(
                $tipo === 'lotes',
                fn ($query) => $query->whereRaw('1 = 0')
            )
            ->latest()
            ->paginate(15, ['*'], 'unidades_page')
            ->withQueryString();

        $lotes = $lotesQuery
            ->when(
                $tipo === 'unidades',
                fn ($query) => $query->whereRaw('1 = 0')
            )
            ->latest()
            ->paginate(15, ['*'], 'lotes_page')
            ->withQueryString();

        $resumen = [
            'fichas' => Bien::where('activo', true)->count(),
            'unidades' => UnidadBien::count(),
            'lotes' => Lote::query()
                ->where('estado_registro', 'activo')
                ->where('cantidad_actual', '>', 0)
                ->count(),
            'cantidad_lotes' => Lote::query()
                ->where('estado_registro', 'activo')
                ->where('cantidad_actual', '>', 0)
                ->sum('cantidad_actual'),
            'valor_unidades' => UnidadBien::sum('valor_en_libros'),
            'valor_lotes' => Lote::query()
                ->where('estado_registro', 'activo')
                ->where('cantidad_actual', '>', 0)
                ->sum('valor_en_libros'),
        ];

        $resumen['valor_total'] =
            $resumen['valor_unidades']
            + $resumen['valor_lotes'];

        $categorias = Categoria::query()
            ->where('activo', true)
            ->orderBy('nombre')
            ->get();

        $areas = Area::query()
            ->where('activo', true)
            ->orderBy('nombre')
            ->get();

        $ubicaciones = Ubicacion::query()
            ->where('activo', true)
            ->orderBy('nombre')
            ->get();

        $estadosConservacion = EstadoConservacion::query()
            ->where('activo', true)
            ->orderBy('orden')
            ->get();

        $filtros = [
            'tipo' => $tipo,
            'categoria_id' => $categoriaId,
            'area_id' => $areaId,
            'ubicacion_id' => $ubicacionId,
            'estado_conservacion_id' =>
                $estadoConservacionId,
            'situacion' => $situacion,
        ];

        return view('v2.bienes.index', compact(
            'unidades',
            'lotes',
            'resumen',
            'busqueda',
            'categorias',
            'areas',
            'ubicaciones',
            'estadosConservacion',
            'filtros'
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
    ->with('success', 'Ficha de bien registrada correctamente.');
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