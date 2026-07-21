<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Bien;
use App\Models\EstadoConservacion;
use App\Models\EstadoOperatividad;
use App\Models\Lote;
use App\Models\Movimiento;
use App\Models\Ubicacion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LoteV2Controller extends Controller
{
    public function create(Request $request): View
    {
        $bienes = Bien::query()
            ->where('activo', true)
            ->whereIn('tipo_control', ['lote', 'consumible'])
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

        $estadosOperatividad = EstadoOperatividad::query()
            ->where('activo', true)
            ->orderBy('orden')
            ->get();

        $bienSeleccionado = $request->integer('bien_id');

        return view('v2.lotes.create', compact(
            'bienes',
            'areas',
            'ubicaciones',
            'estadosConservacion',
            'estadosOperatividad',
            'bienSeleccionado'
        ));
    }

    public function edit(Lote $lote): View
    {
        if ($lote->estado_registro === 'fusionado') {
            abort(
                422,
                'Este lote fue fusionado y no puede editarse.'
            );
        }

        $bienes = Bien::query()
            ->where('activo', true)
            ->whereIn('tipo_control', ['lote', 'consumible'])
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

        $estadosOperatividad = EstadoOperatividad::query()
            ->where('activo', true)
            ->orderBy('orden')
            ->get();

        return view('v2.lotes.edit', compact(
            'lote',
            'bienes',
            'areas',
            'ubicaciones',
            'estadosConservacion',
            'estadosOperatividad'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'bien_id' => [
                'required',
                Rule::exists('bienes', 'id')->where(
                    fn ($query) => $query
                        ->where('activo', true)
                        ->whereIn('tipo_control', ['lote', 'consumible'])
                ),
            ],

            'cantidad_inicial' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'unidad_medida' => [
                'required',
                'string',
                'max:50',
            ],

            'area_id' => [
                'nullable',
                'exists:areas,id',
            ],

            'ubicacion_id' => [
                'nullable',
                'exists:ubicaciones,id',
            ],

            'estado_conservacion_id' => [
                'required',
                'exists:estados_conservacion,id',
            ],

            'estado_operatividad_id' => [
                'nullable',
                'exists:estados_operatividad,id',
            ],

            'situacion' => [
                'required',
                'in:disponible,asignado',
            ],

            'responsable_nombre' => [
                'nullable',
                'string',
                'max:255',
            ],

            'responsable_dni' => [
                'nullable',
                'string',
                'max:20',
            ],

            'responsable_cargo' => [
                'nullable',
                'string',
                'max:255',
            ],

            'responsable_area' => [
                'nullable',
                'string',
                'max:255',
            ],

            'responsable_telefono' => [
                'nullable',
                'string',
                'max:30',
            ],

            'fecha_adquisicion' => [
                'nullable',
                'date',
            ],

            'fecha_ingreso' => [
                'nullable',
                'date',
            ],

            'fecha_puesta_en_uso' => [
                'nullable',
                'date',
            ],

            'anio_ingreso' => [
                'nullable',
                'integer',
                'min:1900',
                'max:' . now()->year,
            ],

            'valor_unitario' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'valor_residual' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'proveedor' => [
                'nullable',
                'string',
                'max:255',
            ],

            'tipo_comprobante' => [
                'nullable',
                'string',
                'max:255',
            ],

            'numero_comprobante' => [
                'nullable',
                'string',
                'max:255',
            ],

            'observaciones' => [
                'nullable',
                'string',
            ],
        ]);

        $lote = DB::transaction(function () use ($datos) {
            $bien = Bien::findOrFail($datos['bien_id']);

            $codigoInterno = $this->generarCodigoInterno();

            $cantidad = (float) $datos['cantidad_inicial'];
            $valorUnitario = isset($datos['valor_unitario'])
                ? (float) $datos['valor_unitario']
                : null;

            $valorTotal = $valorUnitario !== null
                ? round($cantidad * $valorUnitario, 2)
                : null;

            $lote = Lote::create([
                ...$datos,
                'codigo_interno' => $codigoInterno,
                'cantidad_actual' => $cantidad,
                'vida_util_meses' => $bien->vida_util_meses,
                'valor_total' => $valorTotal,
                'valor_en_libros' => $valorTotal,
                'depreciacion_acumulada' => 0,
                'valor_ajustado' => null,
                'moneda' => 'PEN',
                'creado_por' => auth()->id(),
            ]);

            Movimiento::create([
                'lote_id' => $lote->id,
                'tipo' => 'registro_inicial',
                'fecha_movimiento' => now(),
                'cantidad' => $lote->cantidad_actual,
                'area_nueva_id' => $lote->area_id,
                'ubicacion_nueva_id' => $lote->ubicacion_id,
                'estado_conservacion_nuevo_id' =>
                    $lote->estado_conservacion_id,
                'estado_operatividad_nuevo_id' =>
                    $lote->estado_operatividad_id,
                'situacion_nueva' => $lote->situacion,
                'responsable_nuevo_nombre' =>
                    $lote->responsable_nombre,
                'responsable_nuevo_dni' =>
                    $lote->responsable_dni,
                'observacion' =>
                    'Registro inicial del lote.',
                'usuario_id' => auth()->id(),
            ]);

            return $lote;
        });

        return redirect()
            ->route('v2.bienes.index')
            ->with(
                'success',
                "El lote {$lote->codigo_interno} fue registrado correctamente."
            );
    }

    public function update(
        Request $request,
        Lote $lote
    ): RedirectResponse {
        if ($lote->estado_registro === 'fusionado') {
            abort(
                422,
                'Este lote fue fusionado y no puede modificarse.'
            );
        }
        $datos = $request->validate([
            'bien_id' => [
                'required',
                Rule::exists('bienes', 'id')->where(
                    fn ($query) => $query
                        ->where('activo', true)
                        ->whereIn('tipo_control', ['lote', 'consumible'])
                ),
            ],

            'cantidad_actual' => [
                'required',
                'numeric',
                'min:0',
            ],

            'unidad_medida' => [
                'required',
                'string',
                'max:50',
            ],

            'area_id' => [
                'nullable',
                'exists:areas,id',
            ],

            'ubicacion_id' => [
                'nullable',
                'exists:ubicaciones,id',
            ],

            'estado_conservacion_id' => [
                'required',
                'exists:estados_conservacion,id',
            ],

            'estado_operatividad_id' => [
                'nullable',
                'exists:estados_operatividad,id',
            ],

            'situacion' => [
                'required',
                'in:disponible,asignado',
            ],

            'responsable_nombre' => [
                'nullable',
                'string',
                'max:255',
            ],

            'responsable_dni' => [
                'nullable',
                'string',
                'max:20',
            ],

            'responsable_cargo' => [
                'nullable',
                'string',
                'max:255',
            ],

            'responsable_area' => [
                'nullable',
                'string',
                'max:255',
            ],

            'responsable_telefono' => [
                'nullable',
                'string',
                'max:30',
            ],

            'fecha_adquisicion' => [
                'nullable',
                'date',
            ],

            'fecha_ingreso' => [
                'nullable',
                'date',
            ],

            'fecha_puesta_en_uso' => [
                'nullable',
                'date',
            ],

            'anio_ingreso' => [
                'nullable',
                'integer',
                'min:1900',
                'max:' . now()->year,
            ],

            'valor_unitario' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'valor_residual' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'proveedor' => [
                'nullable',
                'string',
                'max:255',
            ],

            'tipo_comprobante' => [
                'nullable',
                'string',
                'max:255',
            ],

            'numero_comprobante' => [
                'nullable',
                'string',
                'max:255',
            ],

            'observaciones' => [
                'nullable',
                'string',
            ],
        ]);

        DB::transaction(function () use ($datos, $lote) {
            $valoresAnteriores = $lote->getOriginal();

            $cantidadAnterior = (float) $lote->cantidad_actual;
            $cantidadNueva = (float) $datos['cantidad_actual'];

            $valorUnitario = isset($datos['valor_unitario'])
                ? (float) $datos['valor_unitario']
                : null;

            $datos['valor_total'] = $valorUnitario !== null
                ? round($cantidadNueva * $valorUnitario, 2)
                : null;

            $datos['valor_en_libros'] = $datos['valor_total'];

            $lote->fill($datos);

            if (! $lote->isDirty()) {
                return;
            }

            $camposModificados = array_keys($lote->getDirty());

            $tipoMovimiento = $this->determinarTipoMovimiento(
                $camposModificados
            );

            $descripcionCambios = collect($camposModificados)
                ->map(fn ($campo) => str_replace('_', ' ', $campo))
                ->implode(', ');

            $diferenciaCantidad = round(
                $cantidadNueva - $cantidadAnterior,
                2
            );

            $lote->save();

            Movimiento::create([
                'lote_id' => $lote->id,
                'tipo' => $tipoMovimiento,
                'fecha_movimiento' => now(),

                'cantidad' => in_array(
                    'cantidad_actual',
                    $camposModificados,
                    true
                )
                    ? $diferenciaCantidad
                    : null,

                'area_anterior_id' =>
                    $valoresAnteriores['area_id'] ?? null,

                'area_nueva_id' =>
                    $lote->area_id,

                'ubicacion_anterior_id' =>
                    $valoresAnteriores['ubicacion_id'] ?? null,

                'ubicacion_nueva_id' =>
                    $lote->ubicacion_id,

                'estado_conservacion_anterior_id' =>
                    $valoresAnteriores['estado_conservacion_id'] ?? null,

                'estado_conservacion_nuevo_id' =>
                    $lote->estado_conservacion_id,

                'estado_operatividad_anterior_id' =>
                    $valoresAnteriores['estado_operatividad_id'] ?? null,

                'estado_operatividad_nuevo_id' =>
                    $lote->estado_operatividad_id,

                'situacion_anterior' =>
                    $valoresAnteriores['situacion'] ?? null,

                'situacion_nueva' =>
                    $lote->situacion,

                'responsable_anterior_nombre' =>
                    $valoresAnteriores['responsable_nombre'] ?? null,

                'responsable_anterior_dni' =>
                    $valoresAnteriores['responsable_dni'] ?? null,

                'responsable_nuevo_nombre' =>
                    $lote->responsable_nombre,

                'responsable_nuevo_dni' =>
                    $lote->responsable_dni,

                'observacion' =>
                    "Actualización de lote. Campos modificados: {$descripcionCambios}.",

                'usuario_id' =>
                    auth()->id(),
            ]);
        });

        return redirect()
            ->route('v2.lotes.show', $lote)
            ->with(
                'success',
                "El lote {$lote->codigo_interno} fue actualizado correctamente."
            );
    }

    private function determinarTipoMovimiento(
        array $camposModificados
    ): string {
        $campos = collect($camposModificados);

        $cambioCantidad = $campos->contains('cantidad_actual');

        $cambioArea = $campos->contains('area_id');

        $cambioUbicacion = $campos->contains('ubicacion_id');

        $cambioResponsable = $campos->intersect([
            'responsable_nombre',
            'responsable_dni',
            'responsable_cargo',
            'responsable_area',
            'responsable_telefono',
        ])->isNotEmpty();

        $cambioEstado = $campos->intersect([
            'estado_conservacion_id',
            'estado_operatividad_id',
            'situacion',
        ])->isNotEmpty();

        $cantidadTipos = collect([
            $cambioCantidad,
            $cambioArea,
            $cambioUbicacion,
            $cambioResponsable,
            $cambioEstado,
        ])->filter()->count();

        if ($cantidadTipos > 1) {
            return 'correccion';
        }

        if ($cambioCantidad) {
            return 'ajuste_cantidad';
        }

        if ($cambioArea) {
            return 'cambio_area';
        }

        if ($cambioUbicacion) {
            return 'cambio_ubicacion';
        }

        if ($cambioResponsable) {
            return 'cambio_responsable';
        }

        if ($cambioEstado) {
            return 'cambio_estado';
        }

        return 'correccion';
    }

    private function generarCodigoInterno(): string
    {
        $ultimoId = Lote::withTrashed()->max('id') ?? 0;

        return 'INC-LOT-' . str_pad(
            (string) ($ultimoId + 1),
            6,
            '0',
            STR_PAD_LEFT
        );
    }
}