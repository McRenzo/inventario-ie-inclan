<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    use HasFactory;

    protected $table = 'movimientos';

    protected $fillable = [
        'unidad_bien_id',
        'lote_id',
        'tipo',
        'fecha_movimiento',
        'cantidad',
        'area_anterior_id',
        'area_nueva_id',
        'ubicacion_anterior_id',
        'ubicacion_nueva_id',
        'estado_conservacion_anterior_id',
        'estado_conservacion_nuevo_id',
        'estado_operatividad_anterior_id',
        'estado_operatividad_nuevo_id',
        'situacion_anterior',
        'situacion_nueva',
        'responsable_anterior_nombre',
        'responsable_anterior_dni',
        'responsable_nuevo_nombre',
        'responsable_nuevo_dni',
        'observacion',
        'documento_referencia',
        'usuario_id',
    ];

    protected function casts(): array
    {
        return [
            'fecha_movimiento' => 'datetime',
            'cantidad' => 'decimal:2',
        ];
    }

    public function unidad()
    {
        return $this->belongsTo(UnidadBien::class, 'unidad_bien_id');
    }

    public function lote()
    {
        return $this->belongsTo(Lote::class, 'lote_id');
    }

    public function areaAnterior()
    {
        return $this->belongsTo(Area::class, 'area_anterior_id');
    }

    public function areaNueva()
    {
        return $this->belongsTo(Area::class, 'area_nueva_id');
    }

    public function ubicacionAnterior()
    {
        return $this->belongsTo(
            Ubicacion::class,
            'ubicacion_anterior_id'
        );
    }

    public function ubicacionNueva()
    {
        return $this->belongsTo(
            Ubicacion::class,
            'ubicacion_nueva_id'
        );
    }

    public function estadoConservacionAnterior()
    {
        return $this->belongsTo(
            EstadoConservacion::class,
            'estado_conservacion_anterior_id'
        );
    }

    public function estadoConservacionNuevo()
    {
        return $this->belongsTo(
            EstadoConservacion::class,
            'estado_conservacion_nuevo_id'
        );
    }

    public function estadoOperatividadAnterior()
    {
        return $this->belongsTo(
            EstadoOperatividad::class,
            'estado_operatividad_anterior_id'
        );
    }

    public function estadoOperatividadNuevo()
    {
        return $this->belongsTo(
            EstadoOperatividad::class,
            'estado_operatividad_nuevo_id'
        );
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function getElementoAttribute()
    {
        return $this->unidad ?? $this->lote;
    }

    public function unidadBien()
    {
        return $this->belongsTo(UnidadBien::class, 'unidad_bien_id');
    }
}