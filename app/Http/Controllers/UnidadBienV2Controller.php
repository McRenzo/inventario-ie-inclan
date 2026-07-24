<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Bien;
use App\Models\EstadoConservacion;
use App\Models\EstadoOperatividad;
use App\Models\Movimiento;
use App\Models\Ubicacion;
use App\Models\UnidadBien;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UnidadBienV2Controller extends Controller
{
    public function create(Request $request): View
    {
        $bienes = Bien::query()
            ->where('activo', true)
            ->where('tipo_control', 'individual')
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

        return view('v2.unidades.create', compact(
            'bienes',
            'areas',
            'ubicaciones',
            'estadosConservacion',
            'estadosOperatividad',
            'bienSeleccionado'
        ));
    }

    public function edit(UnidadBien $unidad): View
    {
        $bienes = Bien::query()
            ->where('activo', true)
            ->where('tipo_control', 'individual')
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

        return view('v2.unidades.edit', compact(
            'unidad',
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
                        ->where('tipo_control', 'individual')
                ),
            ],

            'numero_serie' => [
                'nullable',
                'string',
                'max:255',
            ],

            'codigo_patrimonial' => [
                'nullable',
                'string',
                'max:255',
                'unique:unidades_bien,codigo_patrimonial',
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

        $unidad = DB::transaction(function () use ($datos) {
            $bien = Bien::findOrFail($datos['bien_id']);

            $codigoInterno = $this->generarCodigoInterno();

            $unidad = UnidadBien::create([
                ...$datos,
                'codigo_interno' => $codigoInterno,
                'vida_util_meses' => null,
                'valor_adquisicion' => null,
                'valor_residual' => 0,
                'depreciacion_acumulada' => 0,
                'valor_en_libros' => 0,
                'valor_ajustado' => null,
                'moneda' => 'PEN',
                'creado_por' => auth()->id(),
            ]);

            Movimiento::create([
                'unidad_bien_id' => $unidad->id,
                'tipo' => 'registro_inicial',
                'fecha_movimiento' => now(),
                'area_nueva_id' => $unidad->area_id,
                'ubicacion_nueva_id' => $unidad->ubicacion_id,
                'estado_conservacion_nuevo_id' =>
                    $unidad->estado_conservacion_id,
                'estado_operatividad_nuevo_id' =>
                    $unidad->estado_operatividad_id,
                'situacion_nueva' => $unidad->situacion,
                'responsable_nuevo_nombre' =>
                    $unidad->responsable_nombre,
                'responsable_nuevo_dni' =>
                    $unidad->responsable_dni,
                'observacion' =>
                    'Registro inicial de la unidad individual.',
                'usuario_id' => auth()->id(),
            ]);

            return $unidad;
        });

        return redirect()
            ->route('v2.bienes.index')
            ->with(
                'success',
                "La unidad {$unidad->codigo_interno} fue registrada correctamente."
            );
    }

    public function update(
        Request $request,
        UnidadBien $unidad
    ): RedirectResponse {
        $datos = $request->validate([
            'bien_id' => [
                'required',
                Rule::exists('bienes', 'id')->where(
                    fn ($query) => $query
                        ->where('activo', true)
                        ->where('tipo_control', 'individual')
                ),
            ],

            'numero_serie' => [
                'nullable',
                'string',
                'max:255',
            ],

            'codigo_patrimonial' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique(
                    'unidades_bien',
                    'codigo_patrimonial'
                )->ignore($unidad->id),
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

        DB::transaction(function () use ($datos, $unidad) {
            $valoresAnteriores = $unidad->getOriginal();

            $unidad->fill($datos);

            if (! $unidad->isDirty()) {
                return;
            }

            $camposModificados = array_keys($unidad->getDirty());

            $tipoMovimiento = $this->determinarTipoMovimiento(
                $camposModificados
            );

            $descripcionCambios = collect($camposModificados)
                ->map(fn ($campo) => str_replace('_', ' ', $campo))
                ->implode(', ');

            $unidad->save();

            Movimiento::create([
                'unidad_bien_id' => $unidad->id,
                'tipo' => $tipoMovimiento,
                'fecha_movimiento' => now(),

                'area_anterior_id' =>
                    $valoresAnteriores['area_id'] ?? null,

                'area_nueva_id' =>
                    $unidad->area_id,

                'ubicacion_anterior_id' =>
                    $valoresAnteriores['ubicacion_id'] ?? null,

                'ubicacion_nueva_id' =>
                    $unidad->ubicacion_id,

                'estado_conservacion_anterior_id' =>
                    $valoresAnteriores['estado_conservacion_id'] ?? null,

                'estado_conservacion_nuevo_id' =>
                    $unidad->estado_conservacion_id,

                'estado_operatividad_anterior_id' =>
                    $valoresAnteriores['estado_operatividad_id'] ?? null,

                'estado_operatividad_nuevo_id' =>
                    $unidad->estado_operatividad_id,

                'situacion_anterior' =>
                    $valoresAnteriores['situacion'] ?? null,

                'situacion_nueva' =>
                    $unidad->situacion,

                'responsable_anterior_nombre' =>
                    $valoresAnteriores['responsable_nombre'] ?? null,

                'responsable_anterior_dni' =>
                    $valoresAnteriores['responsable_dni'] ?? null,

                'responsable_nuevo_nombre' =>
                    $unidad->responsable_nombre,

                'responsable_nuevo_dni' =>
                    $unidad->responsable_dni,

                'observacion' =>
                    "Actualización de unidad. Campos modificados: {$descripcionCambios}.",

                'usuario_id' =>
                    auth()->id(),
            ]);
        });

        return redirect()
            ->route('v2.unidades.show', $unidad)
            ->with(
                'success',
                "La unidad {$unidad->codigo_interno} fue actualizada correctamente."
            );
    }

    private function determinarTipoMovimiento(
        array $camposModificados
    ): string {
        $campos = collect($camposModificados);

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
            $cambioArea,
            $cambioUbicacion,
            $cambioResponsable,
            $cambioEstado,
        ])->filter()->count();

        if ($cantidadTipos > 1) {
            return 'correccion';
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
        $ultimoId = UnidadBien::withTrashed()->max('id') ?? 0;

        return 'INC-IND-' . str_pad(
            (string) ($ultimoId + 1),
            6,
            '0',
            STR_PAD_LEFT
        );
    }
}