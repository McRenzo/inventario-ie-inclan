<?php

namespace App\Exports;

use App\Models\Lote;
use App\Models\UnidadBien;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InventarioV2Export implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize,
    WithStyles
{
    public function __construct(
        private readonly array $filtros = []
    ) {
    }

    public function collection()
    {
        $tipo = $this->filtros['tipo'] ?? 'todos';

        $registros = collect();

        if ($tipo !== 'lotes') {
            $registros = $registros->concat(
                $this->consultaUnidades()->get()
            );
        }

        if ($tipo !== 'unidades') {
            $registros = $registros->concat(
                $this->consultaLotes()->get()
            );
        }

        return $registros;
    }

    public function headings(): array
    {
        return [
            'Código',
            'Tipo',
            'Bien',
            'Categoría',
            'Marca',
            'Modelo',
            'Cantidad',
            'Unidad de medida',
            'Área',
            'Ubicación',
            'Responsable',
            'DNI responsable',
            'Estado de conservación',
            'Estado operativo',
            'Situación',
            'Valor unitario',
            'Valor en libros',
            'Fecha de ingreso',
        ];
    }

    public function map($registro): array
    {
        $esUnidad = $registro instanceof UnidadBien;

        return [
            $registro->codigo_interno,
            $esUnidad ? 'Unidad' : 'Lote',
            $registro->bien?->nombre ?? '',
            $registro->bien?->categoria?->nombre ?? '',
            $registro->bien?->marca ?? '',
            $registro->bien?->modelo ?? '',
            $esUnidad
                ? 1
                : (float) $registro->cantidad_actual,
            $esUnidad
                ? 'unidad'
                : $registro->unidad_medida,
            $registro->area?->nombre ?? '',
            $registro->ubicacion?->nombre ?? '',
            $registro->responsable_nombre ?? '',
            $registro->responsable_dni ?? '',
            $registro->estadoConservacion?->nombre ?? '',
            $registro->estadoOperatividad?->nombre ?? '',
            str_replace('_', ' ', $registro->situacion),
            $esUnidad
                ? (float) ($registro->valor_adquisicion ?? 0)
                : (float) ($registro->valor_unitario ?? 0),
            (float) ($registro->valor_en_libros ?? 0),
            optional($registro->fecha_ingreso)->format('d/m/Y'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->freezePane('A2');

        $sheet->getStyle('A1:R1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => [
                    'argb' => 'FFFFFFFF',
                ],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FF2563EB',
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sheet->getStyle('G:G')->getNumberFormat()
            ->setFormatCode('0.00');

        $sheet->getStyle('P:Q')->getNumberFormat()
            ->setFormatCode('"S/ " #,##0.00');

        $sheet->setAutoFilter('A1:R1');

        return [];
    }

    private function consultaUnidades(): Builder
    {
        $query = UnidadBien::query()
            ->with([
                'bien.categoria',
                'area',
                'ubicacion',
                'estadoConservacion',
                'estadoOperatividad',
            ]);

        return $this->aplicarFiltros($query);
    }

    private function consultaLotes(): Builder
    {
        $query = Lote::query()
            ->with([
                'bien.categoria',
                'area',
                'ubicacion',
                'estadoConservacion',
                'estadoOperatividad',
            ])
            ->where('estado_registro', 'activo')
            ->where('cantidad_actual', '>', 0);

        return $this->aplicarFiltros($query);
    }

    private function aplicarFiltros(Builder $query): Builder
    {
        $busqueda = trim((string) (
            $this->filtros['buscar'] ?? ''
        ));

        $categoriaId = (int) (
            $this->filtros['categoria_id'] ?? 0
        );

        $areaId = (int) (
            $this->filtros['area_id'] ?? 0
        );

        $ubicacionId = (int) (
            $this->filtros['ubicacion_id'] ?? 0
        );

        $estadoConservacionId = (int) (
            $this->filtros['estado_conservacion_id'] ?? 0
        );

        $situacion = (string) (
            $this->filtros['situacion'] ?? ''
        );

        return $query
            ->when(
                $busqueda !== '',
                function (Builder $query) use ($busqueda) {
                    $query->where(
                        function (Builder $subquery) use ($busqueda) {
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
                                    function (Builder $bienQuery) use ($busqueda) {
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
                        }
                    );
                }
            )
            ->when(
                $categoriaId > 0,
                fn (Builder $query) => $query->whereHas(
                    'bien',
                    fn (Builder $bienQuery) =>
                        $bienQuery->where(
                            'categoria_id',
                            $categoriaId
                        )
                )
            )
            ->when(
                $areaId > 0,
                fn (Builder $query) =>
                    $query->where('area_id', $areaId)
            )
            ->when(
                $ubicacionId > 0,
                fn (Builder $query) =>
                    $query->where(
                        'ubicacion_id',
                        $ubicacionId
                    )
            )
            ->when(
                $estadoConservacionId > 0,
                fn (Builder $query) =>
                    $query->where(
                        'estado_conservacion_id',
                        $estadoConservacionId
                    )
            )
            ->when(
                $situacion !== '',
                fn (Builder $query) =>
                    $query->where(
                        'situacion',
                        $situacion
                    )
            )
            ->orderBy('codigo_interno');
    }
}