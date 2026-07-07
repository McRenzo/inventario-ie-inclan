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

            'valor_adquisicion' => [
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

        $unidad = DB::transaction(function () use ($datos) {
            $bien = Bien::findOrFail($datos['bien_id']);

            $codigoInterno = $this->generarCodigoInterno();

            $vidaUtilMeses = $bien->vida_util_meses;

            $valorAdquisicion = $datos['valor_adquisicion'] ?? null;
            $valorResidual = $datos['valor_residual'] ?? 0;

            $unidad = UnidadBien::create([
                ...$datos,
                'codigo_interno' => $codigoInterno,
                'vida_util_meses' => $vidaUtilMeses,
                'depreciacion_acumulada' => 0,
                'valor_en_libros' => $valorAdquisicion,
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