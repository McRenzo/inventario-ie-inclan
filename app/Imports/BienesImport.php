<?php

namespace App\Imports;

use App\Models\Bien;
use App\Models\Categoria;
use App\Models\Estado;
use App\Models\Ubicacion;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Str;

class BienesImport implements ToCollection
{
    protected $categoriaNombre;

    public function __construct($categoriaNombre)
    {
        $this->categoriaNombre = strtoupper(trim($categoriaNombre));
    }

    public function collection(Collection $rows)
    {
        // Creamos la categoría seleccionada
        $categoria = Categoria::firstOrCreate(['nombre' => $this->categoriaNombre]);

        foreach ($rows as $row) {
            // Extraemos el nombre del bien desde el Índice 2 (Columna C)
            $nombreBien = isset($row[2]) ? trim($row[2]) : '';

            // ── FILTROS DE BASURA USANDO LA VARIABLE CORRECTA ──
            if (empty($nombreBien) || 
                $nombreBien === 'DESCRIPCIÓN' || 
                $nombreBien === 'D E S C R I P C I Ó N' || 
                str_contains(strtoupper($nombreBien), 'SISTEMA DE') || 
                str_contains(strtoupper($nombreBien), 'ARTÍCULOS DE') || 
                str_contains(strtoupper($nombreBien), 'ARTICULOS DE') ||
                preg_match('/^[A-Z]\.\s+/i', $nombreBien)
            ) {
                continue; // Salta esta fila si es un título o está vacía
            }

            // Índice 3 (Columna D): CANTIDAD
            $cantidadRaw = isset($row[3]) ? trim($row[3]) : '1';
            $cantidad = is_numeric($cantidadRaw) ? (int)$cantidadRaw : 1;
            if ($cantidad <= 0) $cantidad = 1;

            // Mapeo inteligente según área
            if ($this->categoriaNombre === 'INTENDENCIA') {
                $procedencia   = 'NO ESPECIFICADO';
                $estadoTexto   = isset($row[8]) && trim($row[8]) !== '' ? trim($row[8]) : 'NO EVALUADO';
                $fechaIngreso  = isset($row[9]) ? trim($row[9]) : null;
                $observaciones = isset($row[10]) && trim($row[10]) !== '' ? trim($row[10]) : 'ALMACÉN CENTRAL';
            } else {
                $procedencia   = isset($row[8]) && trim($row[8]) !== '' ? trim($row[8]) : 'NO ESPECIFICADO';
                $estadoTexto   = isset($row[9]) && trim($row[9]) !== '' ? trim($row[9]) : 'NO EVALUADO';
                $fechaIngreso  = isset($row[10]) ? trim($row[10]) : null;
                $observaciones = isset($row[11]) && trim($row[11]) !== '' ? trim($row[11]) : 'ALMACÉN CENTRAL';
            }

            if ($procedencia === '---' || $procedencia === '--') $procedencia = 'NO ESPECIFICADO';
            if ($estadoTexto === '---' || $estadoTexto === '--') $estadoTexto = 'NO EVALUADO';

            // Registramos Estado y Ubicación
            $estado    = Estado::firstOrCreate(['nombre' => strtoupper(trim($estadoTexto))]);
            $ubicacion = Ubicacion::firstOrCreate(['nombre' => strtoupper(trim($observaciones))]);

            // Bucle Multiplicador
            for ($i = 0; $i < $cantidad; $i++) {
                $codigoUnico = 'INC-' . date('y') . '-' . strtoupper(Str::random(5));

                Bien::create([
                    'codigo_barras_qr'     => $codigoUnico,
                    'nombre'               => $nombreBien, // 👈 Guardamos en la columna nombre
                    'descripcion'          => '',        // 👈 La dejamos vacía
                    'cantidad'             => 1,
                    'categoria_id'         => $categoria->id,
                    'estado_id'            => $estado->id,
                    'ubicacion_id'         => $ubicacion->id,
                    'procedencia'          => $procedencia,
                    'fecha_ingreso_origen' => $fechaIngreso ? (string)$fechaIngreso : null,
                    'observaciones_origen' => strtoupper(trim($observaciones)),
                    'estado_actual'        => 'disponible',
                ]);
            }
        }
    }
}