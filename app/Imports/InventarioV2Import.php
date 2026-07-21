<?php

namespace App\Imports;

use App\Models\Area;
use App\Models\Bien;
use App\Models\EstadoConservacion;
use App\Models\EstadoOperatividad;
use App\Models\Movimiento;
use App\Models\Ubicacion;
use App\Models\UnidadBien;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class InventarioV2Import implements ToCollection, WithHeadingRow
{
    private int $importados = 0;

    private int $omitidos = 0;

    public function headingRow(): int
    {
        return 3;
    }

    public function collection(Collection $rows): void
    {
        DB::transaction(function () use ($rows) {
            foreach ($rows as $row) {
                $nombreBien = $this->limpiarTexto($row['nombre_bien'] ?? null);

                if ($nombreBien === '') {
                    $this->omitidos++;
                    continue;
                }

                $areaNombre = $this->limpiarTexto($row['area'] ?? 'Sin área');

                $ubicacionNombre = $this->limpiarTexto($row['ubicacion'] ?? 'Sin ubicación');

                $descripcion = $this->limpiarTexto($row['descripcion'] ?? null);

                $numeroSerie = $this->limpiarTexto($row['numero_serie'] ?? null);

                $procedencia = $this->limpiarTexto($row['procedencia'] ?? null);

                $observaciones = $this->limpiarTexto($row['observaciones'] ?? null);

                $archivoOrigen = $this->limpiarTexto($row['archivo_origen'] ?? null);

                $filaOrigen = $this->limpiarTexto($row['fila_origen'] ?? null);

                $fechaIngreso = $this->normalizarFecha($row['fecha_ingreso'] ?? null);

                $anioIngreso = $this->normalizarAnio($row['anio_ingreso'] ?? null, $fechaIngreso);

                $valorAdquisicion = $this->normalizarDecimal($row['valor_unitario'] ?? null);

                $area = Area::firstOrCreate(
                    ['nombre' => $areaNombre],
                    ['activo' => true]
                );

                $ubicacion = Ubicacion::firstOrCreate(
                    [
                        'nombre' => $ubicacionNombre,
                        'area_id' => $area->id,
                    ],
                    [
                        'activo' => true,
                    ]
                );

                $estadoConservacion = $this->obtenerEstadoConservacion(
                    $row['estado_conservacion'] ?? null
                );

                $estadoOperatividad = $this->obtenerEstadoOperatividad(
                    $row['estado_operatividad'] ?? null
                );

                $bien = Bien::firstOrCreate(
                    [
                        'nombre' => $nombreBien,
                        'tipo_control' => 'individual',
                    ],
                    [
                        'descripcion' => $descripcion,
                        'procedencia' => $procedencia,
                        'activo' => true,
                        'es_depreciable' => false,
                        'vida_util_meses' => null,
                        'valor_residual_porcentaje' => 0,
                        'observaciones' => $this->armarObservacionBien(
                            $observaciones,
                            $archivoOrigen,
                            $filaOrigen
                        ),
                    ]
                );

                $unidad = UnidadBien::create([
                    'bien_id' => $bien->id,
                    'codigo_interno' => $this->generarCodigoUnidad(),
                    'numero_serie' => $numeroSerie !== '' ? $numeroSerie : null,
                    'codigo_patrimonial' => null,

                    'area_id' => $area->id,
                    'ubicacion_id' => $ubicacion->id,

                    'estado_conservacion_id' => $estadoConservacion->id,
                    'estado_operatividad_id' => $estadoOperatividad?->id,

                    'situacion' => 'disponible',

                    'responsable_nombre' => null,
                    'responsable_dni' => null,
                    'responsable_cargo' => null,
                    'responsable_area' => null,
                    'responsable_telefono' => null,

                    'fecha_adquisicion' => null,
                    'fecha_ingreso' => $fechaIngreso,
                    'fecha_puesta_en_uso' => null,
                    'anio_ingreso' => $anioIngreso,

                    'vida_util_meses' => $bien->vida_util_meses,

                    'valor_adquisicion' => $valorAdquisicion,
                    'valor_residual' => 0,
                    'depreciacion_acumulada' => 0,
                    'valor_en_libros' => $valorAdquisicion,
                    'valor_ajustado' => null,

                    'moneda' => 'PEN',
                    'proveedor' => $procedencia !== '' ? $procedencia : null,
                    'tipo_comprobante' => null,
                    'numero_comprobante' => null,

                    'estado_origen' => $this->limpiarTexto($row['estado_operatividad'] ?? null),
                    'ubicacion_origen' => $ubicacionNombre,
                    'archivo_origen' => $archivoOrigen,
                    'hoja_origen' => null,
                    'fila_origen' => $filaOrigen !== '' ? (int) $filaOrigen : null,

                    'observaciones' => $this->armarObservacionUnidad(
                        $observaciones,
                        $archivoOrigen,
                        $filaOrigen
                    ),

                    'creado_por' => auth()->id(),
                ]);

                Movimiento::create([
                    'unidad_bien_id' => $unidad->id,
                    'lote_id' => null,
                    'tipo' => 'registro_inicial',
                    'fecha_movimiento' => now(),
                    'cantidad' => 1,

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
                        'Registro inicial importado desde Excel.',

                    'usuario_id' => auth()->id(),
                ]);

                $this->importados++;
            }
        });
    }

    public function importados(): int
    {
        return $this->importados;
    }

    public function omitidos(): int
    {
        return $this->omitidos;
    }

    private function limpiarTexto($valor): string
    {
        if ($valor === null) {
            return '';
        }

        return trim(preg_replace('/\s+/', ' ', (string) $valor));
    }

    private function normalizarDecimal($valor): ?float
    {
        if ($valor === null || $valor === '') {
            return null;
        }

        $valor = str_replace(',', '.', (string) $valor);

        return is_numeric($valor) ? round((float) $valor, 2) : null;
    }

    private function normalizarFecha($valor): ?string
    {
        if ($valor === null || $valor === '') {
            return null;
        }

        if (is_numeric($valor)) {
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($valor)
                ->format('Y-m-d');
        }

        try {
            return \Carbon\Carbon::parse($valor)->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function normalizarAnio($valor, ?string $fechaIngreso): ?int
    {
        if ($valor !== null && $valor !== '' && is_numeric($valor)) {
            return (int) $valor;
        }

        if ($fechaIngreso) {
            return (int) \Carbon\Carbon::parse($fechaIngreso)->format('Y');
        }

        return null;
    }

    private function obtenerEstadoConservacion($valor): EstadoConservacion
    {
        $nombre = $this->normalizarEstado($valor);

        return EstadoConservacion::firstOrCreate(
            ['nombre' => $nombre],
            [
                'activo' => true,
                'orden' => EstadoConservacion::max('orden') + 1,
            ]
        );
    }

    private function obtenerEstadoOperatividad($valor): ?EstadoOperatividad
    {
        $nombre = $this->normalizarEstado($valor);

        if ($nombre === '') {
            return null;
        }

        return EstadoOperatividad::firstOrCreate(
            ['nombre' => $nombre],
            [
                'activo' => true,
                'orden' => EstadoOperatividad::max('orden') + 1,
            ]
        );
    }

    private function normalizarEstado($valor): string
    {
        $estado = Str::upper($this->limpiarTexto($valor));

        return match ($estado) {
            'OPERATIVA' => 'Operativo',
            'OPERATIVO' => 'Operativo',
            'REGULAR' => 'Regular',
            'INOPERATIVO' => 'Inoperativo',
            'INOPERATIVO (LIMITADA)' => 'Inoperativo',
            'OPERATIVIDAD LIMITADA' => 'Operatividad limitada',
            default => $estado !== '' ? Str::title(Str::lower($estado)) : 'Regular',
        };
    }

    private function generarCodigoUnidad(): string
    {
        $ultimoId = UnidadBien::withTrashed()->max('id') ?? 0;

        return 'INC-IND-' . str_pad(
            (string) ($ultimoId + 1),
            6,
            '0',
            STR_PAD_LEFT
        );
    }

    private function armarObservacionBien(
        string $observaciones,
        string $archivoOrigen,
        string $filaOrigen
    ): ?string {
        $texto = trim($observaciones);

        if ($archivoOrigen !== '') {
            $texto .= ($texto !== '' ? PHP_EOL : '')
                . "Archivo origen: {$archivoOrigen}";
        }

        if ($filaOrigen !== '') {
            $texto .= PHP_EOL . "Fila origen: {$filaOrigen}";
        }

        return $texto !== '' ? $texto : null;
    }

    private function armarObservacionUnidad(
        string $observaciones,
        string $archivoOrigen,
        string $filaOrigen
    ): ?string {
        $texto = trim($observaciones);

        if ($archivoOrigen !== '') {
            $texto .= ($texto !== '' ? PHP_EOL : '')
                . "Importado desde: {$archivoOrigen}";
        }

        if ($filaOrigen !== '') {
            $texto .= PHP_EOL . "Fila original: {$filaOrigen}";
        }

        return $texto !== '' ? $texto : null;
    }
}