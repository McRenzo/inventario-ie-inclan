<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mantenimiento extends Model
{
    use HasFactory;

    protected $table = 'mantenimientos';

    protected $fillable = [
        'codigo',
        'unidad_bien_id',
        'lote_id',
        'tipo',
        'falla_reportada',
        'diagnostico',
        'tecnico_proveedor',
        'fecha_ingreso',
        'fecha_retorno_estimada',
        'fecha_salida',
        'costo',
        'moneda',
        'estado_conservacion_anterior_id',
        'estado_conservacion_final_id',
        'estado_operatividad_anterior_id',
        'estado_operatividad_final_id',
        'estado',
        'resultado',
        'observaciones',
        'registrado_por',
        'finalizado_por',
    ];

    protected function casts(): array
    {
        return [
            'fecha_ingreso' => 'datetime',
            'fecha_retorno_estimada' => 'datetime',
            'fecha_salida' => 'datetime',
            'costo' => 'decimal:2',
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

    public function estadoConservacionAnterior()
    {
        return $this->belongsTo(
            EstadoConservacion::class,
            'estado_conservacion_anterior_id'
        );
    }

    public function estadoConservacionFinal()
    {
        return $this->belongsTo(
            EstadoConservacion::class,
            'estado_conservacion_final_id'
        );
    }

    public function estadoOperatividadAnterior()
    {
        return $this->belongsTo(
            EstadoOperatividad::class,
            'estado_operatividad_anterior_id'
        );
    }

    public function estadoOperatividadFinal()
    {
        return $this->belongsTo(
            EstadoOperatividad::class,
            'estado_operatividad_final_id'
        );
    }

    public function registradoPor()
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }

    public function finalizadoPor()
    {
        return $this->belongsTo(User::class, 'finalizado_por');
    }

    public function adjuntos()
    {
        return $this->morphMany(Adjunto::class, 'adjuntable');
    }

    public function getElementoAttribute()
    {
        return $this->unidad ?? $this->lote;
    }
}