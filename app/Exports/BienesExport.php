<?php

namespace App\Exports;

use App\Models\Bien;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border; // Importa esta para usar 'Border::BORDER_THIN'

class BienesExport implements FromQuery, WithHeadings, WithStyles
{
    public function query()
    {
        return Bien::query()
            ->join('categorias', 'bienes.categoria_id', '=', 'categorias.id')
            ->join('estados', 'bienes.estado_id', '=', 'estados.id')
            ->join('ubicaciones', 'bienes.ubicacion_id', '=', 'ubicaciones.id')
            ->select(
                'bienes.nombre', // DESCRIPCIÓN
                DB::raw('SUM(bienes.cantidad) as total_cantidad'), // CANT.
                DB::raw('GROUP_CONCAT(bienes.numero_serie SEPARATOR ", ") as series'), // Nº SERIE
                'categorias.nombre as categoria_nombre', // CATEGORÍA
                'estados.nombre as estado_nombre', // SITUACIÓN / ESTADO
                'bienes.fecha_ingreso_origen', // FECHA DE INGRESO
                'bienes.observaciones_origen' // OBS
            )
            ->groupBy(
                'bienes.nombre', 
                'categorias.nombre', 
                'estados.nombre', 
                'ubicaciones.nombre',
                'bienes.fecha_ingreso_origen',
                'bienes.observaciones_origen'
            )
            ->reorder();
    }

    public function headings(): array
    {
        return [
            "DESCRIPCIÓN", 
            "CANT.", 
            "Nº SERIE", 
            "CATEGORÍA", 
            "ESTADO",
            "FECHA DE INGRESO",
            "OBS"
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Aplica negrita a la fila 1 (encabezados)
            1 => ['font' => ['bold' => true]], 
            
            // Aplica bordes a todas las celdas
            'A1:G100' => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ],
        ];
    }
}