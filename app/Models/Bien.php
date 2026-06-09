<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bien extends Model
{
    use HasFactory;

    // Indicamos explícitamente la tabla de la base de datos
    protected $table = 'bienes';

    // Campos que permitiremos registrar masivamente desde el Excel o formulario
    protected $fillable = [
        'codigo_barras_qr',
        'origen',
        'subcategoria',
        'descripcion',
        'cantidad',
        'categoria_relevo',
        'procedencia',
        'estado_conservacion',
        'fecha_ingreso_origen',
        'observaciones_origen',
        'lab_area',
        'lab_nivel',
        'lab_contenido',
        'lab_detalle_tipo',
        'lab_ciclo',
        'estado_actual'
    ];

    /**
     * Relación: Un bien patrimonial puede tener muchos movimientos (historial técnico).
     */
    public function movimientos()
    {
        return $this->hasMany(Movimiento::class, 'bien_id');
    }
}