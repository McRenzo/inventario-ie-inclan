<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Lote;
use App\Models\Movimiento;
use App\Models\Ubicacion;
use App\Models\UnidadBien;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use App\Models\Prestamo;

class TransferenciaV2Controller extends Controller
{
    public function createUnidad(UnidadBien $unidad): View
    {
        $unidad->load([
            'bien',
            'area',
            'ubicacion',
        ]);

        $this->validarSituacionTransferible($unidad->situacion);

        return view('v2.transferencias.create', [
            'tipo' => 'unidad',
            'unidad' => $unidad,
            'lote' => null,
            'areas' => $this->areasActivas(),
            'ubicaciones' => $this->ubicacionesActivas(),
        ]);
    }

    private function validarLoteActivo(Lote $lote): void
    {
        if ($lote->estado_registro === 'fusionado') {
            abort(
                422,
                'Este lote fue fusionado y ya no puede utilizarse.'
            );
        }
    }

    private function validarLoteSinPrestamosPendientes(
        Lote $lote
    ): void {
        $tienePrestamosPendientes = Prestamo::query()
            ->where('lote_id', $lote->id)
            ->whereIn('estado', [
                'activo',
                'vencido',
            ])
            ->exists();

        if ($tienePrestamosPendientes) {
            throw ValidationException::withMessages([
                'lotes_secundarios' =>
                    "El lote {$lote->codigo_interno} tiene préstamos "
                    . 'activos o vencidos pendientes de devolución '
                    . 'y no puede fusionarse.',
            ]);
        }
    }

    public function createLote(Lote $lote): View
    {
        $lote->load([
            'bien',
            'area',
            'ubicacion',
        ]);

        $this->validarLoteActivo($lote);
        $this->validarSituacionTransferible($lote->situacion);

        if ((float) $lote->cantidad_actual <= 0) {
            abort(
                422,
                'El lote no tiene cantidad disponible para transferir.'
            );
        }

        return view('v2.transferencias.create', [
            'tipo' => 'lote',
            'unidad' => null,
            'lote' => $lote,
            'areas' => $this->areasActivas(),
            'ubicaciones' => $this->ubicacionesActivas(),
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

            'area_id' => [
                'required',
                'integer',
                'exists:areas,id',
            ],

            'ubicacion_id' => [
                'required',
                'integer',
                'exists:ubicaciones,id',
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

            'fecha_transferencia' => [
                'required',
                'date',
            ],

            'observacion' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        if ($datos['tipo'] === 'unidad') {
            $unidad = DB::transaction(
                fn () => $this->transferirUnidad($datos)
            );

            return redirect()
                ->route('v2.unidades.show', $unidad)
                ->with(
                    'success',
                    'La unidad fue transferida correctamente.'
                );
        }

        $loteDestino = DB::transaction(
            fn () => $this->transferirLote($datos)
        );

        return redirect()
            ->route('v2.lotes.show', $loteDestino)
            ->with(
                'success',
                'El lote fue transferido correctamente.'
            );
    }

    private function transferirUnidad(array $datos): UnidadBien
    {
        $unidad = UnidadBien::query()
            ->lockForUpdate()
            ->findOrFail($datos['unidad_bien_id']);

        $this->validarSituacionTransferible($unidad->situacion);

        $areaAnterior = $unidad->area_id;
        $ubicacionAnterior = $unidad->ubicacion_id;
        $responsableAnteriorNombre = $unidad->responsable_nombre;
        $responsableAnteriorDni = $unidad->responsable_dni;
        $situacionAnterior = $unidad->situacion;

        $sinCambios =
            (int) $areaAnterior === (int) $datos['area_id']
            && (int) $ubicacionAnterior === (int) $datos['ubicacion_id']
            && trim((string) $responsableAnteriorNombre)
                === trim((string) ($datos['responsable_nombre'] ?? ''))
            && trim((string) $responsableAnteriorDni)
                === trim((string) ($datos['responsable_dni'] ?? ''));

        if ($sinCambios) {
            throw ValidationException::withMessages([
                'area_id' =>
                    'Debes cambiar el área, la ubicación o el responsable para realizar la transferencia.',
            ]);
        }

        $situacionNueva = filled($datos['responsable_nombre'] ?? null)
            ? 'asignado'
            : 'disponible';

        $unidad->update([
            'area_id' => $datos['area_id'],
            'ubicacion_id' => $datos['ubicacion_id'],
            'situacion' => $situacionNueva,
            'responsable_nombre' =>
                $datos['responsable_nombre'] ?? null,
            'responsable_dni' =>
                $datos['responsable_dni'] ?? null,
            'responsable_cargo' =>
                $datos['responsable_cargo'] ?? null,
            'responsable_area' =>
                $datos['responsable_area'] ?? null,
            'responsable_telefono' =>
                $datos['responsable_telefono'] ?? null,
        ]);

        Movimiento::create([
            'unidad_bien_id' => $unidad->id,
            'lote_id' => null,
            'tipo' => $this->determinarTipoMovimiento(
                $areaAnterior,
                $datos['area_id'],
                $ubicacionAnterior,
                $datos['ubicacion_id'],
                $responsableAnteriorNombre,
                $datos['responsable_nombre'] ?? null
            ),
            'fecha_movimiento' => $datos['fecha_transferencia'],
            'cantidad' => 1,
            'area_anterior_id' => $areaAnterior,
            'area_nueva_id' => $datos['area_id'],
            'ubicacion_anterior_id' => $ubicacionAnterior,
            'ubicacion_nueva_id' => $datos['ubicacion_id'],
            'estado_conservacion_anterior_id' =>
                $unidad->estado_conservacion_id,
            'estado_conservacion_nuevo_id' =>
                $unidad->estado_conservacion_id,
            'estado_operatividad_anterior_id' =>
                $unidad->estado_operatividad_id,
            'estado_operatividad_nuevo_id' =>
                $unidad->estado_operatividad_id,
            'situacion_anterior' => $situacionAnterior,
            'situacion_nueva' => $situacionNueva,
            'responsable_anterior_nombre' =>
                $responsableAnteriorNombre,
            'responsable_anterior_dni' =>
                $responsableAnteriorDni,
            'responsable_nuevo_nombre' =>
                $datos['responsable_nombre'] ?? null,
            'responsable_nuevo_dni' =>
                $datos['responsable_dni'] ?? null,
            'observacion' =>
                $datos['observacion'] ?? 'Transferencia de unidad.',
            'usuario_id' => auth()->id(),
        ]);

        return $unidad->fresh();
    }

    private function transferirLote(array $datos): Lote
    {
        $lote = Lote::query()
            ->lockForUpdate()
            ->findOrFail($datos['lote_id']);

        $this->validarLoteActivo($lote);
        $this->validarSituacionTransferible($lote->situacion);

        $cantidad = round((float) $datos['cantidad'], 2);
        $cantidadDisponible = (float) $lote->cantidad_actual;

        if ($cantidad > $cantidadDisponible) {
            throw ValidationException::withMessages([
                'cantidad' =>
                    "Solo hay {$cantidadDisponible} "
                    . "{$lote->unidad_medida} disponibles.",
            ]);
        }

        if (abs($cantidad - $cantidadDisponible) < 0.001) {
            return $this->transferirLoteCompleto(
                $lote,
                $datos,
                $cantidad
            );
        }

        return $this->transferirLoteParcial(
            $lote,
            $datos,
            $cantidad
        );
    }

    private function transferirLoteCompleto(
        Lote $lote,
        array $datos,
        float $cantidad
    ): Lote {
        $areaAnterior = $lote->area_id;
        $ubicacionAnterior = $lote->ubicacion_id;
        $responsableAnteriorNombre = $lote->responsable_nombre;
        $responsableAnteriorDni = $lote->responsable_dni;
        $situacionAnterior = $lote->situacion;

        $sinCambios =
            (int) $areaAnterior === (int) $datos['area_id']
            && (int) $ubicacionAnterior === (int) $datos['ubicacion_id']
            && trim((string) $responsableAnteriorNombre)
                === trim((string) ($datos['responsable_nombre'] ?? ''))
            && trim((string) $responsableAnteriorDni)
                === trim((string) ($datos['responsable_dni'] ?? ''));

        if ($sinCambios) {
            throw ValidationException::withMessages([
                'area_id' =>
                    'Debes cambiar el área, la ubicación o el responsable para realizar la transferencia.',
            ]);
        }

        $situacionNueva = filled($datos['responsable_nombre'] ?? null)
            ? 'asignado'
            : 'disponible';

        $lote->update([
            'area_id' => $datos['area_id'],
            'ubicacion_id' => $datos['ubicacion_id'],
            'situacion' => $situacionNueva,
            'responsable_nombre' =>
                $datos['responsable_nombre'] ?? null,
            'responsable_dni' =>
                $datos['responsable_dni'] ?? null,
            'responsable_cargo' =>
                $datos['responsable_cargo'] ?? null,
            'responsable_area' =>
                $datos['responsable_area'] ?? null,
            'responsable_telefono' =>
                $datos['responsable_telefono'] ?? null,
        ]);

        $this->registrarMovimientoLote(
            $lote,
            $datos,
            $cantidad,
            $areaAnterior,
            $ubicacionAnterior,
            $responsableAnteriorNombre,
            $responsableAnteriorDni,
            $situacionAnterior,
            $situacionNueva
        );

        return $lote->fresh();
    }

    private function transferirLoteParcial(
        Lote $lote,
        array $datos,
        float $cantidad
    ): Lote {
        /*
        * Guardamos los datos originales antes de modificar
        * la cantidad del lote de origen.
        */
        $areaAnterior = $lote->area_id;
        $ubicacionAnterior = $lote->ubicacion_id;
        $responsableAnteriorNombre = $lote->responsable_nombre;
        $responsableAnteriorDni = $lote->responsable_dni;
        $situacionAnterior = $lote->situacion;
        $cantidadAnterior = round(
            (float) $lote->cantidad_actual,
            2
        );

        $cantidadRestante = round(
            $cantidadAnterior - $cantidad,
            2
        );

        $situacionDestino = filled(
            $datos['responsable_nombre'] ?? null
        )
            ? 'asignado'
            : 'disponible';

        /*
        * Reducimos la cantidad disponible del lote original
        * y recalculamos sus valores según la nueva cantidad.
        */
        $lote->update(array_merge([
            'cantidad_actual' => $cantidadRestante,
        ], $this->recalcularValoresLote($lote, $cantidadRestante)));

        /*
        * Creamos el lote que recibirá la cantidad transferida.
        */
        $loteDestino = Lote::create([
            'bien_id' => $lote->bien_id,
            'codigo_interno' => $this->generarCodigoLote($lote),
            'cantidad_inicial' => $cantidad,
            'cantidad_actual' => $cantidad,
            'unidad_medida' => $lote->unidad_medida,

            'area_id' => $datos['area_id'],
            'ubicacion_id' => $datos['ubicacion_id'],

            'estado_conservacion_id' =>
                $lote->estado_conservacion_id,

            'estado_operatividad_id' =>
                $lote->estado_operatividad_id,

            'situacion' => $situacionDestino,

            'responsable_nombre' =>
                $datos['responsable_nombre'] ?? null,

            'responsable_dni' =>
                $datos['responsable_dni'] ?? null,

            'responsable_cargo' =>
                $datos['responsable_cargo'] ?? null,

            'responsable_area' =>
                $datos['responsable_area'] ?? null,

            'responsable_telefono' =>
                $datos['responsable_telefono'] ?? null,

            'fecha_adquisicion' =>
                $lote->fecha_adquisicion,

            'fecha_ingreso' =>
                $lote->fecha_ingreso,

            'fecha_puesta_en_uso' =>
                $lote->fecha_puesta_en_uso,

            'anio_ingreso' =>
                $lote->anio_ingreso,

            'vida_util_meses' =>
                $lote->vida_util_meses,

            'valor_unitario' =>
                $lote->valor_unitario,

            'valor_total' => $this->calcularValorPorCantidad(
                $lote->valor_unitario !== null
                    ? (float) $lote->valor_unitario
                    : null,
                $cantidad
            ),

            'valor_residual' => 0,

            'depreciacion_acumulada' => 0,

            'valor_en_libros' => $this->calcularValorPorCantidad(
                $lote->valor_unitario !== null
                    ? (float) $lote->valor_unitario
                    : null,
                $cantidad
            ),

            'valor_ajustado' => null,
            'moneda' => $lote->moneda,
            'proveedor' => $lote->proveedor,
            'tipo_comprobante' => $lote->tipo_comprobante,
            'numero_comprobante' => $lote->numero_comprobante,
            'estado_origen' => $lote->estado_origen,
            'ubicacion_origen' => $lote->ubicacion_origen,
            'archivo_origen' => $lote->archivo_origen,
            'hoja_origen' => $lote->hoja_origen,
            'fila_origen' => $lote->fila_origen,
            'foto_principal' => $lote->foto_principal,

            'observaciones' =>
                'Lote derivado de '
                . "{$lote->codigo_interno} "
                . "por transferencia parcial de {$cantidad} "
                . "{$lote->unidad_medida}.",

            'creado_por' => auth()->id(),

            'estado_registro' => 'activo',
            'fusionado_en_id' => null,
            'fecha_fusion' => null,
        ]);

        /*
        * Movimiento de salida en el lote original.
        */
        Movimiento::create([
            'unidad_bien_id' => null,
            'lote_id' => $lote->id,
            'tipo' => 'ajuste_cantidad',
            'fecha_movimiento' => $datos['fecha_transferencia'],
            'cantidad' => -$cantidad,

            'area_anterior_id' => $areaAnterior,
            'area_nueva_id' => $areaAnterior,

            'ubicacion_anterior_id' => $ubicacionAnterior,
            'ubicacion_nueva_id' => $ubicacionAnterior,

            'estado_conservacion_anterior_id' =>
                $lote->estado_conservacion_id,

            'estado_conservacion_nuevo_id' =>
                $lote->estado_conservacion_id,

            'estado_operatividad_anterior_id' =>
                $lote->estado_operatividad_id,

            'estado_operatividad_nuevo_id' =>
                $lote->estado_operatividad_id,

            'situacion_anterior' => $situacionAnterior,
            'situacion_nueva' => $situacionAnterior,

            'responsable_anterior_nombre' =>
                $responsableAnteriorNombre,

            'responsable_anterior_dni' =>
                $responsableAnteriorDni,

            'responsable_nuevo_nombre' =>
                $responsableAnteriorNombre,

            'responsable_nuevo_dni' =>
                $responsableAnteriorDni,

            'observacion' =>
                $datos['observacion']
                ?? "Salida de {$cantidad} {$lote->unidad_medida} "
                . "por transferencia parcial al lote "
                . "{$loteDestino->codigo_interno}.",

            'usuario_id' => auth()->id(),
        ]);

        Movimiento::create([
            'unidad_bien_id' => null,
            'lote_id' => $loteDestino->id,
            'tipo' => 'registro_inicial',
            'fecha_movimiento' => $datos['fecha_transferencia'],
            'cantidad' => $cantidad,

            'area_anterior_id' => $areaAnterior,
            'area_nueva_id' => $datos['area_id'],

            'ubicacion_anterior_id' => $ubicacionAnterior,
            'ubicacion_nueva_id' => $datos['ubicacion_id'],

            'estado_conservacion_anterior_id' =>
                $lote->estado_conservacion_id,

            'estado_conservacion_nuevo_id' =>
                $loteDestino->estado_conservacion_id,

            'estado_operatividad_anterior_id' =>
                $lote->estado_operatividad_id,

            'estado_operatividad_nuevo_id' =>
                $loteDestino->estado_operatividad_id,

            'situacion_anterior' => $situacionAnterior,
            'situacion_nueva' => $situacionDestino,

            'responsable_anterior_nombre' =>
                $responsableAnteriorNombre,

            'responsable_anterior_dni' =>
                $responsableAnteriorDni,

            'responsable_nuevo_nombre' =>
                $datos['responsable_nombre'] ?? null,

            'responsable_nuevo_dni' =>
                $datos['responsable_dni'] ?? null,

            'observacion' =>
                $datos['observacion']
                ?? "Ingreso de {$cantidad} {$lote->unidad_medida} "
                . "por transferencia parcial desde el lote "
                . "{$lote->codigo_interno}.",

            'usuario_id' => auth()->id(),
        ]);

        return $loteDestino->fresh();
    }

    private function registrarMovimientoLote(
        Lote $lote,
        array $datos,
        float $cantidad,
        ?int $areaAnterior,
        ?int $ubicacionAnterior,
        ?string $responsableAnteriorNombre,
        ?string $responsableAnteriorDni,
        ?string $situacionAnterior,
        string $situacionNueva,
        ?string $observacion = null
    ): void {
        Movimiento::create([
            'unidad_bien_id' => null,
            'lote_id' => $lote->id,

            'tipo' => $this->determinarTipoMovimiento(
                $areaAnterior,
                (int) $datos['area_id'],
                $ubicacionAnterior,
                (int) $datos['ubicacion_id'],
                $responsableAnteriorNombre,
                $datos['responsable_nombre'] ?? null
            ),

            'fecha_movimiento' =>
                $datos['fecha_transferencia'],

            'cantidad' => $cantidad,

            'area_anterior_id' =>
                $areaAnterior,

            'area_nueva_id' =>
                $datos['area_id'],

            'ubicacion_anterior_id' =>
                $ubicacionAnterior,

            'ubicacion_nueva_id' =>
                $datos['ubicacion_id'],

            'estado_conservacion_anterior_id' =>
                $lote->estado_conservacion_id,

            'estado_conservacion_nuevo_id' =>
                $lote->estado_conservacion_id,

            'estado_operatividad_anterior_id' =>
                $lote->estado_operatividad_id,

            'estado_operatividad_nuevo_id' =>
                $lote->estado_operatividad_id,

            'situacion_anterior' =>
                $situacionAnterior,

            'situacion_nueva' =>
                $situacionNueva,

            'responsable_anterior_nombre' =>
                $responsableAnteriorNombre,

            'responsable_anterior_dni' =>
                $responsableAnteriorDni,

            'responsable_nuevo_nombre' =>
                $datos['responsable_nombre'] ?? null,

            'responsable_nuevo_dni' =>
                $datos['responsable_dni'] ?? null,

            'observacion' => $observacion
                ?? $datos['observacion']
                ?? 'Transferencia de lote.',

            'usuario_id' => auth()->id(),
        ]);
    }

    private function determinarTipoMovimiento(
        ?int $areaAnterior,
        int $areaNueva,
        ?int $ubicacionAnterior,
        int $ubicacionNueva,
        ?string $responsableAnterior,
        ?string $responsableNuevo
    ): string {
        if ($areaAnterior !== $areaNueva) {
            return 'cambio_area';
        }

        if ($ubicacionAnterior !== $ubicacionNueva) {
            return 'cambio_ubicacion';
        }

        return 'cambio_responsable';
    }

    public function createFusion(Lote $lote): View
    {
        $lote->load([
            'bien',
            'area',
            'ubicacion',
        ]);

        $this->validarLoteActivo($lote);
        $this->validarSituacionTransferible($lote->situacion);
        $this->validarLoteSinPrestamosPendientes($lote);

        if ((float) $lote->cantidad_actual <= 0) {
            abort(
                422,
                'El lote principal no tiene cantidad disponible.'
            );
        }

        $lotesCompatibles = Lote::query()
            ->with([
                'bien',
                'area',
                'ubicacion',
            ])
            ->where('id', '!=', $lote->id)
            ->where('estado_registro', 'activo')
            ->whereDoesntHave('prestamos', function ($query) {
                $query->whereIn('estado', [
                    'activo',
                    'vencido',
                ]);
            })
            ->where('bien_id', $lote->bien_id)
            ->where('unidad_medida', $lote->unidad_medida)
            ->where('area_id', $lote->area_id)
            ->where('ubicacion_id', $lote->ubicacion_id)
            ->where(
                'estado_conservacion_id',
                $lote->estado_conservacion_id
            )
            ->where(
                'estado_operatividad_id',
                $lote->estado_operatividad_id
            )
            ->where('situacion', $lote->situacion)
            ->where('responsable_nombre', $lote->responsable_nombre)
            ->where('responsable_dni', $lote->responsable_dni)
            ->where('moneda', $lote->moneda)
            ->where('valor_unitario', $lote->valor_unitario)
            ->where('cantidad_actual', '>', 0)
            ->orderBy('codigo_interno')
            ->get();

        return view('v2.lotes.fusionar', [
            'lote' => $lote,
            'lotesCompatibles' => $lotesCompatibles,
        ]);
    }

    public function storeFusion(
        Request $request,
        Lote $lote
    ): RedirectResponse {
        $datos = $request->validate([
            'lotes_secundarios' => [
                'required',
                'array',
                'min:1',
            ],

            'lotes_secundarios.*' => [
                'integer',
                'exists:lotes,id',
                Rule::notIn([$lote->id]),
            ],

            'fecha_fusion' => [
                'required',
                'date',
            ],

            'observacion' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        DB::transaction(function () use ($lote, $datos) {
            $lotePrincipal = Lote::query()
                ->lockForUpdate()
                ->findOrFail($lote->id);

            $this->validarLoteActivo($lotePrincipal);

            $this->validarSituacionTransferible(
                $lotePrincipal->situacion
            );

            $this->validarLoteSinPrestamosPendientes(
                $lotePrincipal
            );

            $cantidadPrincipalAnterior = round(
                (float) $lotePrincipal->cantidad_actual,
                2
            );

            $valorTotalPrincipalAnterior = (float) (
                $lotePrincipal->valor_total ?? 0
            );

            $valorResidualPrincipalAnterior = (float) (
                $lotePrincipal->valor_residual ?? 0
            );

            $depreciacionPrincipalAnterior = (float) (
                $lotePrincipal->depreciacion_acumulada ?? 0
            );

            $valorLibrosPrincipalAnterior = (float) (
                $lotePrincipal->valor_en_libros ?? 0
            );

            $valorAjustadoPrincipalAnterior = (float) (
                $lotePrincipal->valor_ajustado ?? 0
            );

            $cantidadTotalFusionada = 0;
            $valorTotalFusionado = 0;
            $valorResidualFusionado = 0;
            $depreciacionFusionada = 0;
            $valorLibrosFusionado = 0;
            $valorAjustadoFusionado = 0;

            $codigosFusionados = [];

            $lotesSecundarios = Lote::query()
                ->whereIn('id', $datos['lotes_secundarios'])
                ->lockForUpdate()
                ->get();

            foreach ($lotesSecundarios as $loteSecundario) {
                $this->validarLoteActivo($loteSecundario);
            
                $this->validarSituacionTransferible(
                    $loteSecundario->situacion
                );
                
                $this->validarLoteSinPrestamosPendientes(
                    $loteSecundario
                );
                
                $this->validarLotesCompatibles(
                    $lotePrincipal,
                    $loteSecundario
                );

                $cantidadSecundaria = round(
                    (float) $loteSecundario->cantidad_actual,
                    2
                );

                $valorTotalFusionado += (float) (
                    $loteSecundario->valor_total ?? 0
                );

                $valorResidualFusionado += (float) (
                    $loteSecundario->valor_residual ?? 0
                );

                $depreciacionFusionada += (float) (
                    $loteSecundario->depreciacion_acumulada ?? 0
                );

                $valorLibrosFusionado += (float) (
                    $loteSecundario->valor_en_libros ?? 0
                );

                $valorAjustadoFusionado += (float) (
                    $loteSecundario->valor_ajustado ?? 0
                );

                if ($cantidadSecundaria <= 0) {
                    throw ValidationException::withMessages([
                        'lotes_secundarios' =>
                            "El lote {$loteSecundario->codigo_interno} "
                            . 'ya no tiene cantidad disponible.',
                    ]);
                }

                $cantidadTotalFusionada += $cantidadSecundaria;
                $codigosFusionados[] = $loteSecundario->codigo_interno;

                $loteSecundario->update([
                    'cantidad_actual' => 0,
                    'estado_registro' => 'fusionado',
                    'fusionado_en_id' => $lotePrincipal->id,
                    'fecha_fusion' => $datos['fecha_fusion'],

                    'valor_total' => 0,
                    'valor_residual' => 0,
                    'depreciacion_acumulada' => 0,
                    'valor_en_libros' => 0,
                    'valor_ajustado' => null,

                    'observaciones' => trim(
                        ($loteSecundario->observaciones
                            ? $loteSecundario->observaciones . PHP_EOL
                            : '')
                        . "Lote fusionado en "
                        . "{$lotePrincipal->codigo_interno}. "
                        . "Cantidad transferida: {$cantidadSecundaria} "
                        . "{$loteSecundario->unidad_medida}."
                    ),
                ]);

                Movimiento::create([
                    'unidad_bien_id' => null,
                    'lote_id' => $loteSecundario->id,
                    'tipo' => 'ajuste_cantidad',
                    'fecha_movimiento' => $datos['fecha_fusion'],
                    'cantidad' => -$cantidadSecundaria,

                    'area_anterior_id' => $loteSecundario->area_id,
                    'area_nueva_id' => $loteSecundario->area_id,

                    'ubicacion_anterior_id' =>
                        $loteSecundario->ubicacion_id,

                    'ubicacion_nueva_id' =>
                        $loteSecundario->ubicacion_id,

                    'estado_conservacion_anterior_id' =>
                        $loteSecundario->estado_conservacion_id,

                    'estado_conservacion_nuevo_id' =>
                        $loteSecundario->estado_conservacion_id,

                    'estado_operatividad_anterior_id' =>
                        $loteSecundario->estado_operatividad_id,

                    'estado_operatividad_nuevo_id' =>
                        $loteSecundario->estado_operatividad_id,

                    'situacion_anterior' =>
                        $loteSecundario->situacion,

                    'situacion_nueva' =>
                        $loteSecundario->situacion,

                    'responsable_anterior_nombre' =>
                        $loteSecundario->responsable_nombre,

                    'responsable_anterior_dni' =>
                        $loteSecundario->responsable_dni,

                    'responsable_nuevo_nombre' =>
                        $loteSecundario->responsable_nombre,

                    'responsable_nuevo_dni' =>
                        $loteSecundario->responsable_dni,

                    'observacion' =>
                        $datos['observacion']
                        ?? "Salida total por fusión hacia el lote "
                        . "{$lotePrincipal->codigo_interno}.",

                    'usuario_id' => auth()->id(),
                ]);
            }

            $cantidadNueva = round(
                $cantidadPrincipalAnterior + $cantidadTotalFusionada,
                2
            );

            $valoresRecalculados = $this->recalcularValoresLote(
                $lotePrincipal,
                $cantidadNueva
            );

            $valorResidualNuevo = round(
                $valorResidualPrincipalAnterior + $valorResidualFusionado,
                2
            );

            $depreciacionNueva = round(
                $depreciacionPrincipalAnterior + $depreciacionFusionada,
                2
            );

            $lotePrincipal->update(array_merge([
                'cantidad_actual' => $cantidadNueva,
                'estado_registro' => 'activo',

                'valor_residual' => $valorResidualNuevo,
                'depreciacion_acumulada' => $depreciacionNueva,
            ], $valoresRecalculados, [
                'observaciones' => trim(
                    ($lotePrincipal->observaciones
                        ? $lotePrincipal->observaciones . PHP_EOL
                        : '')
                    . 'Fusión recibida desde los lotes: '
                    . implode(', ', $codigosFusionados)
                    . ". Cantidad total recibida: {$cantidadTotalFusionada} "
                    . "{$lotePrincipal->unidad_medida}."
                ),
            ]));

            Movimiento::create([
                'unidad_bien_id' => null,
                'lote_id' => $lotePrincipal->id,
                'tipo' => 'ajuste_cantidad',
                'fecha_movimiento' => $datos['fecha_fusion'],
                'cantidad' => $cantidadTotalFusionada,

                'area_anterior_id' => $lotePrincipal->area_id,
                'area_nueva_id' => $lotePrincipal->area_id,

                'ubicacion_anterior_id' =>
                    $lotePrincipal->ubicacion_id,

                'ubicacion_nueva_id' =>
                    $lotePrincipal->ubicacion_id,

                'estado_conservacion_anterior_id' =>
                    $lotePrincipal->estado_conservacion_id,

                'estado_conservacion_nuevo_id' =>
                    $lotePrincipal->estado_conservacion_id,

                'estado_operatividad_anterior_id' =>
                    $lotePrincipal->estado_operatividad_id,

                'estado_operatividad_nuevo_id' =>
                    $lotePrincipal->estado_operatividad_id,

                'situacion_anterior' =>
                    $lotePrincipal->situacion,

                'situacion_nueva' =>
                    $lotePrincipal->situacion,

                'responsable_anterior_nombre' =>
                    $lotePrincipal->responsable_nombre,

                'responsable_anterior_dni' =>
                    $lotePrincipal->responsable_dni,

                'responsable_nuevo_nombre' =>
                    $lotePrincipal->responsable_nombre,

                'responsable_nuevo_dni' =>
                    $lotePrincipal->responsable_dni,

                'observacion' =>
                    $datos['observacion']
                    ?? 'Ingreso por fusión desde los lotes '
                    . implode(', ', $codigosFusionados)
                    . ". Cantidad anterior: {$cantidadPrincipalAnterior}. "
                    . "Cantidad recibida: {$cantidadTotalFusionada}. "
                    . "Cantidad final: {$cantidadNueva}.",

                'usuario_id' => auth()->id(),
            ]);
        });

        return redirect()
            ->route('v2.lotes.show', $lote)
            ->with(
                'success',
                'Los lotes fueron fusionados correctamente.'
            );
    }

    private function validarLotesCompatibles(
        Lote $principal,
        Lote $secundario
    ): void {
        $campos = [
            'bien_id',
            'unidad_medida',
            'area_id',
            'ubicacion_id',
            'estado_conservacion_id',
            'estado_operatividad_id',
            'situacion',
            'responsable_nombre',
            'responsable_dni',
            'moneda',
            'valor_unitario',
        ];

        foreach ($campos as $campo) {
            if ((string) $principal->{$campo}
                !== (string) $secundario->{$campo}) {
                throw ValidationException::withMessages([
                    'lote_secundario_id' =>
                        'Los lotes no tienen las mismas características '
                        . 'y no pueden fusionarse.',
                ]);
            }
        }
    }

    private function validarSituacionTransferible(
        string $situacion
    ): void {
        if (in_array($situacion, [
            'prestado',
            'en_mantenimiento',
            'no_encontrado',
            'en_proceso_de_baja',
            'dado_de_baja',
        ], true)) {
            abort(
                422,
                'El bien no se encuentra disponible para transferencia.'
            );
        }
    }

    private function areasActivas()
    {
        return Area::query()
            ->where('activo', true)
            ->orderBy('nombre')
            ->get();
    }

    private function ubicacionesActivas()
    {
        return Ubicacion::query()
            ->with('area')
            ->where('activo', true)
            ->orderBy('nombre')
            ->get();
    }

    private function generarCodigoLote(Lote $lote): string
    {
        /*
        * Si el lote ya es derivado, recuperamos el código raíz.
        *
        * Ejemplo:
        * INC-LOT-000001-T02
        * se convierte en:
        * INC-LOT-000001
        */
        $codigoBase = preg_replace(
            '/-T\d+$/',
            '',
            $lote->codigo_interno
        );

        $ultimoCodigo = Lote::query()
            ->whereRaw(
                'codigo_interno REGEXP ?',
                ['^' . preg_quote($codigoBase, '/') . '-T[0-9]{2}$']
            )
            ->orderByDesc('codigo_interno')
            ->value('codigo_interno');

        $siguienteNumero = 1;

        if ($ultimoCodigo) {
            preg_match(
                '/-T(\d+)$/',
                $ultimoCodigo,
                $coincidencias
            );

            if (isset($coincidencias[1])) {
                $siguienteNumero =
                    (int) $coincidencias[1] + 1;
            }
        }

        do {
            $codigo = sprintf(
                '%s-T%02d',
                $codigoBase,
                $siguienteNumero
            );

            $existe = Lote::query()
                ->where('codigo_interno', $codigo)
                ->exists();

            if ($existe) {
                $siguienteNumero++;
            }
        } while ($existe);

        return $codigo;
    }

    private function calcularValorPorCantidad(
        ?float $valorUnitario,
        float $cantidad
    ): ?float {
        if ($valorUnitario === null) {
            return null;
        }

        return round($valorUnitario * $cantidad, 2);
    }

    private function recalcularValoresLote(
        Lote $lote,
        float $cantidad
    ): array {
        $valorUnitario = $lote->valor_unitario !== null
            ? (float) $lote->valor_unitario
            : null;

        $valorTotal = $this->calcularValorPorCantidad(
            $valorUnitario,
            $cantidad
        );

        $depreciacion = (float) ($lote->depreciacion_acumulada ?? 0);

        $valorEnLibros = $valorTotal !== null
            ? round($valorTotal - $depreciacion, 2)
            : null;

        return [
            'valor_total' => $valorTotal,
            'valor_en_libros' => $valorEnLibros,
            'valor_ajustado' => null,
        ];
    }
}