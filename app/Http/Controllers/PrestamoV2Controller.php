<?php

namespace App\Http\Controllers;

use App\Models\EstadoConservacion;
use App\Models\Lote;
use App\Models\Movimiento;
use App\Models\Prestamo;
use App\Models\UnidadBien;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PrestamoV2Controller extends Controller
{
    public function index(Request $request): View
    {
        Prestamo::query()
            ->where('estado', 'activo')
            ->whereNotNull('fecha_devolucion_prevista')
            ->where('fecha_devolucion_prevista', '<', now())
            ->update([
                'estado' => 'vencido',
            ]);

        $buscar = trim($request->string('buscar')->toString());
        $estado = $request->string('estado')->toString();
        $fechaDesde = $request->string('fecha_desde')->toString();
        $fechaHasta = $request->string('fecha_hasta')->toString();

        $prestamos = Prestamo::query()
            ->with([
                'unidad.bien',
                'lote.bien',
            ])

            ->when(
                $buscar !== '',
                function ($query) use ($buscar) {
                    $query->where(function ($subquery) use ($buscar) {
                        $subquery
                            ->where('codigo', 'like', "%{$buscar}%")
                            ->orWhere(
                                'receptor_nombre',
                                'like',
                                "%{$buscar}%"
                            )
                            ->orWhere(
                                'receptor_dni',
                                'like',
                                "%{$buscar}%"
                            )
                            ->orWhereHas(
                                'unidad',
                                function ($unidadQuery) use ($buscar) {
                                    $unidadQuery
                                        ->where(
                                            'codigo_interno',
                                            'like',
                                            "%{$buscar}%"
                                        )
                                        ->orWhereHas(
                                            'bien',
                                            function ($bienQuery) use ($buscar) {
                                                $bienQuery->where(
                                                    'nombre',
                                                    'like',
                                                    "%{$buscar}%"
                                                );
                                            }
                                        );
                                }
                            )
                            ->orWhereHas(
                                'lote',
                                function ($loteQuery) use ($buscar) {
                                    $loteQuery
                                        ->where(
                                            'codigo_interno',
                                            'like',
                                            "%{$buscar}%"
                                        )
                                        ->orWhereHas(
                                            'bien',
                                            function ($bienQuery) use ($buscar) {
                                                $bienQuery->where(
                                                    'nombre',
                                                    'like',
                                                    "%{$buscar}%"
                                                );
                                            }
                                        );
                                }
                            );
                    });
                }
            )

            ->when(
                in_array($estado, [
                    'activo',
                    'devuelto',
                    'vencido',
                    'cancelado',
                ], true),
                function ($query) use ($estado) {
                    if ($estado === 'vencido') {
                        $query
                            ->whereIn('estado', ['activo', 'vencido'])
                            ->whereNotNull('fecha_devolucion_prevista')
                            ->where(
                                'fecha_devolucion_prevista',
                                '<',
                                now()
                            );

                        return;
                    }

                    $query->where('estado', $estado);
                }
            )

            ->when(
                $fechaDesde !== '',
                fn ($query) => $query->whereDate(
                    'fecha_prestamo',
                    '>=',
                    $fechaDesde
                )
            )

            ->when(
                $fechaHasta !== '',
                fn ($query) => $query->whereDate(
                    'fecha_prestamo',
                    '<=',
                    $fechaHasta
                )
            )

            ->latest('fecha_prestamo')
            ->paginate(15)
            ->withQueryString();

        return view(
            'v2.prestamos.index',
            compact(
                'prestamos',
                'buscar',
                'estado',
                'fechaDesde',
                'fechaHasta'
            )
        );
    }

    public function createUnidad(UnidadBien $unidad): View
    {
        $unidad->load([
            'bien',
            'area',
            'ubicacion',
            'estadoConservacion',
        ]);

        $this->validarUnidadDisponible($unidad);

        $estadosConservacion = EstadoConservacion::query()
            ->where('activo', true)
            ->orderBy('orden')
            ->get();

        return view('v2.prestamos.create', [
            'tipo' => 'unidad',
            'unidad' => $unidad,
            'lote' => null,
            'estadosConservacion' => $estadosConservacion,
        ]);
    }

    public function createLote(Lote $lote): View
    {
        $lote->load([
            'bien',
            'area',
            'ubicacion',
            'estadoConservacion',
        ]);

        if ((float) $lote->cantidad_actual <= 0) {
            abort(422, 'El lote no tiene cantidad disponible para prestar.');
        }

        if (in_array($lote->situacion, [
            'en_mantenimiento',
            'no_encontrado',
            'en_proceso_de_baja',
            'dado_de_baja',
        ], true)) {
            abort(
                422,
                'El lote no se encuentra disponible para préstamos.'
            );
        }

        $estadosConservacion = EstadoConservacion::query()
            ->where('activo', true)
            ->orderBy('orden')
            ->get();

        return view('v2.prestamos.create', [
            'tipo' => 'lote',
            'unidad' => null,
            'lote' => $lote,
            'estadosConservacion' => $estadosConservacion,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'tipo' => [
                'required',
                Rule::in(['unidad', 'lote']),
            ],

            'unidad_bien_id' => [
                'nullable',
                'integer',
                'exists:unidades_bien,id',
                'required_if:tipo,unidad',
            ],

            'lote_id' => [
                'nullable',
                'integer',
                'exists:lotes,id',
                'required_if:tipo,lote',
            ],

            'cantidad' => [
                'nullable',
                'numeric',
                'min:0.01',
                'required_if:tipo,lote',
            ],

            'receptor_nombre' => [
                'required',
                'string',
                'max:255',
            ],

            'receptor_dni' => [
                'nullable',
                'string',
                'max:20',
            ],

            'receptor_cargo' => [
                'nullable',
                'string',
                'max:255',
            ],

            'receptor_area' => [
                'nullable',
                'string',
                'max:255',
            ],

            'receptor_telefono' => [
                'nullable',
                'string',
                'max:30',
            ],

            'fecha_prestamo' => [
                'required',
                'date',
            ],

            'fecha_devolucion_prevista' => [
                'nullable',
                'date',
                'after_or_equal:fecha_prestamo',
            ],

            'estado_conservacion_salida_id' => [
                'nullable',
                'exists:estados_conservacion,id',
            ],

            'observaciones_salida' => [
                'nullable',
                'string',
            ],
        ]);

        $prestamo = DB::transaction(function () use ($datos) {
            if ($datos['tipo'] === 'unidad') {
                return $this->registrarPrestamoUnidad($datos);
            }

            return $this->registrarPrestamoLote($datos);
        });

        return redirect()
            ->route('v2.prestamos.show', $prestamo)
            ->with(
                'success',
                "El préstamo {$prestamo->codigo} fue registrado correctamente."
            );
    }

    public function show(Prestamo $prestamo): View
    {
        $prestamo->load([
            'unidad.bien',
            'unidad.area',
            'unidad.ubicacion',
            'lote.bien',
            'lote.area',
            'lote.ubicacion',
            'estadoConservacionSalida',
            'estadoConservacionDevolucion',
            'registradoPor',
            'devueltoPor',
        ]);

        $estadosConservacion = EstadoConservacion::query()
            ->where('activo', true)
            ->orderBy('orden')
            ->get();

        return view('v2.prestamos.show', compact(
            'prestamo',
            'estadosConservacion'
        ));
    }

    public function devolver(
        Request $request,
        Prestamo $prestamo
    ): RedirectResponse {
        $datos = $request->validate([
            'fecha_devolucion_real' => [
                'required',
                'date',
                'after_or_equal:' . $prestamo->fecha_prestamo->format(
                    'Y-m-d H:i'
                ),
            ],

            'estado_conservacion_devolucion_id' => [
                'nullable',
                'exists:estados_conservacion,id',
            ],

            'observaciones_devolucion' => [
                'nullable',
                'string',
            ],
        ]);

        DB::transaction(function () use ($prestamo, $datos) {
            $prestamo = Prestamo::query()
                ->lockForUpdate()
                ->findOrFail($prestamo->id);

            if (!in_array($prestamo->estado, ['activo', 'vencido'], true)) {
                throw ValidationException::withMessages([
                    'prestamo' =>
                        'Este préstamo ya fue devuelto, cancelado o cerrado.',
                ]);
            }

            if ($prestamo->unidad_bien_id) {
                $this->devolverUnidad($prestamo, $datos);
            } else {
                $this->devolverLote($prestamo, $datos);
            }

            $prestamo->update([
                'fecha_devolucion_real' =>
                    $datos['fecha_devolucion_real'],

                'estado_conservacion_devolucion_id' =>
                    $datos['estado_conservacion_devolucion_id']
                    ?? null,

                'observaciones_devolucion' =>
                    $datos['observaciones_devolucion']
                    ?? null,

                'estado' => 'devuelto',
                'devuelto_por' => auth()->id(),
            ]);
        });

        return redirect()
            ->route('v2.prestamos.show', $prestamo)
            ->with(
                'success',
                "El préstamo {$prestamo->codigo} fue devuelto correctamente."
            );
    }

    private function registrarPrestamoUnidad(array $datos): Prestamo
    {
        $unidad = UnidadBien::query()
            ->lockForUpdate()
            ->findOrFail($datos['unidad_bien_id']);

        $this->validarUnidadDisponible($unidad);

        $prestamo = Prestamo::create([
            'codigo' => $this->generarCodigo(),
            'unidad_bien_id' => $unidad->id,
            'lote_id' => null,
            'cantidad' => 1,
            'receptor_nombre' => $datos['receptor_nombre'],
            'receptor_dni' => $datos['receptor_dni'] ?? null,
            'receptor_cargo' => $datos['receptor_cargo'] ?? null,
            'receptor_area' => $datos['receptor_area'] ?? null,
            'receptor_telefono' =>
                $datos['receptor_telefono'] ?? null,
            'fecha_prestamo' => $datos['fecha_prestamo'],
            'fecha_devolucion_prevista' =>
                $datos['fecha_devolucion_prevista'] ?? null,
            'estado_conservacion_salida_id' =>
                $datos['estado_conservacion_salida_id']
                ?? $unidad->estado_conservacion_id,
            'estado' => 'activo',
            'observaciones_salida' =>
                $datos['observaciones_salida'] ?? null,
            'registrado_por' => auth()->id(),
        ]);

        $situacionAnterior = $unidad->situacion;

        $unidad->update([
            'situacion' => 'prestado',
        ]);

        Movimiento::create([
            'unidad_bien_id' => $unidad->id,
            'tipo' => 'prestamo',
            'fecha_movimiento' => $datos['fecha_prestamo'],
            'cantidad' => 1,
            'area_anterior_id' => $unidad->area_id,
            'area_nueva_id' => $unidad->area_id,
            'ubicacion_anterior_id' => $unidad->ubicacion_id,
            'ubicacion_nueva_id' => $unidad->ubicacion_id,
            'estado_conservacion_anterior_id' =>
                $unidad->estado_conservacion_id,
            'estado_conservacion_nuevo_id' =>
                $unidad->estado_conservacion_id,
            'estado_operatividad_anterior_id' =>
                $unidad->estado_operatividad_id,
            'estado_operatividad_nuevo_id' =>
                $unidad->estado_operatividad_id,
            'situacion_anterior' => $situacionAnterior,
            'situacion_nueva' => 'prestado',
            'responsable_anterior_nombre' =>
                $unidad->responsable_nombre,
            'responsable_anterior_dni' =>
                $unidad->responsable_dni,
            'responsable_nuevo_nombre' =>
                $datos['receptor_nombre'],
            'responsable_nuevo_dni' =>
                $datos['receptor_dni'] ?? null,
            'observacion' =>
                "Préstamo {$prestamo->codigo}. "
                . ($datos['observaciones_salida'] ?? ''),
            'usuario_id' => auth()->id(),
        ]);

        return $prestamo;
    }

    private function registrarPrestamoLote(array $datos): Prestamo
    {
        $lote = Lote::query()
            ->lockForUpdate()
            ->findOrFail($datos['lote_id']);

        $cantidad = (float) $datos['cantidad'];
        $disponible = (float) $lote->cantidad_actual;

        if ($cantidad > $disponible) {
            throw ValidationException::withMessages([
                'cantidad' =>
                    "Solo hay {$disponible} {$lote->unidad_medida} disponibles.",
            ]);
        }

        if ($cantidad <= 0) {
            throw ValidationException::withMessages([
                'cantidad' => 'La cantidad debe ser mayor que cero.',
            ]);
        }

        $prestamo = Prestamo::create([
            'codigo' => $this->generarCodigo(),
            'unidad_bien_id' => null,
            'lote_id' => $lote->id,
            'cantidad' => $cantidad,
            'receptor_nombre' => $datos['receptor_nombre'],
            'receptor_dni' => $datos['receptor_dni'] ?? null,
            'receptor_cargo' => $datos['receptor_cargo'] ?? null,
            'receptor_area' => $datos['receptor_area'] ?? null,
            'receptor_telefono' =>
                $datos['receptor_telefono'] ?? null,
            'fecha_prestamo' => $datos['fecha_prestamo'],
            'fecha_devolucion_prevista' =>
                $datos['fecha_devolucion_prevista'] ?? null,
            'estado_conservacion_salida_id' =>
                $datos['estado_conservacion_salida_id']
                ?? $lote->estado_conservacion_id,
            'estado' => 'activo',
            'observaciones_salida' =>
                $datos['observaciones_salida'] ?? null,
            'registrado_por' => auth()->id(),
        ]);

        $situacionAnterior = $lote->situacion;
        $cantidadRestante = round($disponible - $cantidad, 2);

        $lote->update([
            'cantidad_actual' => $cantidadRestante,
            'situacion' => $cantidadRestante <= 0
                ? 'prestado'
                : 'disponible',
        ]);

        Movimiento::create([
            'lote_id' => $lote->id,
            'tipo' => 'prestamo',
            'fecha_movimiento' => $datos['fecha_prestamo'],
            'cantidad' => -$cantidad,
            'area_anterior_id' => $lote->area_id,
            'area_nueva_id' => $lote->area_id,
            'ubicacion_anterior_id' => $lote->ubicacion_id,
            'ubicacion_nueva_id' => $lote->ubicacion_id,
            'estado_conservacion_anterior_id' =>
                $lote->estado_conservacion_id,
            'estado_conservacion_nuevo_id' =>
                $lote->estado_conservacion_id,
            'estado_operatividad_anterior_id' =>
                $lote->estado_operatividad_id,
            'estado_operatividad_nuevo_id' =>
                $lote->estado_operatividad_id,
            'situacion_anterior' => $situacionAnterior,
            'situacion_nueva' => $lote->situacion,
            'responsable_anterior_nombre' =>
                $lote->responsable_nombre,
            'responsable_anterior_dni' =>
                $lote->responsable_dni,
            'responsable_nuevo_nombre' =>
                $datos['receptor_nombre'],
            'responsable_nuevo_dni' =>
                $datos['receptor_dni'] ?? null,
            'observacion' =>
                "Préstamo {$prestamo->codigo}. "
                . ($datos['observaciones_salida'] ?? ''),
            'usuario_id' => auth()->id(),
        ]);

        return $prestamo;
    }

    private function devolverUnidad(
        Prestamo $prestamo,
        array $datos
    ): void {
        $unidad = UnidadBien::query()
            ->lockForUpdate()
            ->findOrFail($prestamo->unidad_bien_id);

        $situacionAnterior = $unidad->situacion;

        $unidad->update([
            'situacion' => 'disponible',

            'estado_conservacion_id' =>
                $datos['estado_conservacion_devolucion_id']
                ?? $unidad->estado_conservacion_id,
        ]);

        Movimiento::create([
            'unidad_bien_id' => $unidad->id,
            'tipo' => 'devolucion',
            'fecha_movimiento' =>
                $datos['fecha_devolucion_real'],
            'cantidad' => 1,
            'area_anterior_id' => $unidad->area_id,
            'area_nueva_id' => $unidad->area_id,
            'ubicacion_anterior_id' => $unidad->ubicacion_id,
            'ubicacion_nueva_id' => $unidad->ubicacion_id,
            'estado_conservacion_anterior_id' =>
                $prestamo->estado_conservacion_salida_id,
            'estado_conservacion_nuevo_id' =>
                $unidad->estado_conservacion_id,
            'estado_operatividad_anterior_id' =>
                $unidad->estado_operatividad_id,
            'estado_operatividad_nuevo_id' =>
                $unidad->estado_operatividad_id,
            'situacion_anterior' => $situacionAnterior,
            'situacion_nueva' => 'disponible',
            'responsable_anterior_nombre' =>
                $prestamo->receptor_nombre,
            'responsable_anterior_dni' =>
                $prestamo->receptor_dni,
            'responsable_nuevo_nombre' =>
                $unidad->responsable_nombre,
            'responsable_nuevo_dni' =>
                $unidad->responsable_dni,
            'observacion' =>
                "Devolución del préstamo {$prestamo->codigo}. "
                . ($datos['observaciones_devolucion'] ?? ''),
            'usuario_id' => auth()->id(),
        ]);
    }

    private function devolverLote(
        Prestamo $prestamo,
        array $datos
    ): void {
        $lote = Lote::query()
            ->lockForUpdate()
            ->findOrFail($prestamo->lote_id);

        $situacionAnterior = $lote->situacion;
        $cantidadDevuelta = (float) $prestamo->cantidad;

        $lote->update([
            'cantidad_actual' => round(
                (float) $lote->cantidad_actual + $cantidadDevuelta,
                2
            ),

            'situacion' => 'disponible',

            'estado_conservacion_id' =>
                $datos['estado_conservacion_devolucion_id']
                ?? $lote->estado_conservacion_id,
        ]);

        Movimiento::create([
            'lote_id' => $lote->id,
            'tipo' => 'devolucion',
            'fecha_movimiento' =>
                $datos['fecha_devolucion_real'],
            'cantidad' => $cantidadDevuelta,
            'area_anterior_id' => $lote->area_id,
            'area_nueva_id' => $lote->area_id,
            'ubicacion_anterior_id' => $lote->ubicacion_id,
            'ubicacion_nueva_id' => $lote->ubicacion_id,
            'estado_conservacion_anterior_id' =>
                $prestamo->estado_conservacion_salida_id,
            'estado_conservacion_nuevo_id' =>
                $lote->estado_conservacion_id,
            'estado_operatividad_anterior_id' =>
                $lote->estado_operatividad_id,
            'estado_operatividad_nuevo_id' =>
                $lote->estado_operatividad_id,
            'situacion_anterior' => $situacionAnterior,
            'situacion_nueva' => 'disponible',
            'responsable_anterior_nombre' =>
                $prestamo->receptor_nombre,
            'responsable_anterior_dni' =>
                $prestamo->receptor_dni,
            'responsable_nuevo_nombre' =>
                $lote->responsable_nombre,
            'responsable_nuevo_dni' =>
                $lote->responsable_dni,
            'observacion' =>
                "Devolución del préstamo {$prestamo->codigo}. "
                . ($datos['observaciones_devolucion'] ?? ''),
            'usuario_id' => auth()->id(),
        ]);
    }

    private function validarUnidadDisponible(
        UnidadBien $unidad
    ): void {
        if ($unidad->situacion !== 'disponible') {
            abort(
                422,
                'La unidad no se encuentra disponible para préstamos.'
            );
        }

        $tienePrestamoActivo = Prestamo::query()
            ->where('unidad_bien_id', $unidad->id)
            ->where('estado', 'activo')
            ->exists();

        if ($tienePrestamoActivo) {
            abort(
                422,
                'La unidad ya tiene un préstamo activo.'
            );
        }
    }

    private function generarCodigo(): string
    {
        $ultimoId = Prestamo::max('id') ?? 0;

        return 'PRE-' . now()->format('Y') . '-'
            . str_pad(
                (string) ($ultimoId + 1),
                6,
                '0',
                STR_PAD_LEFT
            );
    }
}