<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestamo extends Model
{
    use HasFactory;

    protected $table = 'prestamos';

    protected $fillable = [
        'codigo',
        'unidad_bien_id',
        'lote_id',
        'cantidad',
        'receptor_nombre',
        'receptor_dni',
        'receptor_cargo',
        'receptor_area',
        'receptor_telefono',
        'fecha_prestamo',
        'fecha_devolucion_prevista',
        'fecha_devolucion_real',
        'estado_conservacion_salida_id',
        'estado_conservacion_devolucion_id',
        'estado',
        'observaciones_salida',
        'observaciones_devolucion',
        'registrado_por',
        'devuelto_por',
    ];

    protected function casts(): array
    {
        return [
            'cantidad' => 'decimal:2',
            'fecha_prestamo' => 'datetime',
            'fecha_devolucion_prevista' => 'datetime',
            'fecha_devolucion_real' => 'datetime',
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

    public function estadoConservacionSalida()
    {
        return $this->belongsTo(
            EstadoConservacion::class,
            'estado_conservacion_salida_id'
        );
    }

    public function estadoConservacionDevolucion()
    {
        return $this->belongsTo(
            EstadoConservacion::class,
            'estado_conservacion_devolucion_id'
        );
    }

    public function registradoPor()
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }

    public function devueltoPor()
    {
        return $this->belongsTo(User::class, 'devuelto_por');
    }

    public function adjuntos()
    {
        return $this->morphMany(Adjunto::class, 'adjuntable');
    }

    public function getElementoAttribute()
    {
        return $this->unidad ?? $this->lote;
    }

    public function getEstaVencidoAttribute(): bool
    {
        return $this->estado === 'activo'
            && $this->fecha_devolucion_prevista
            && $this->fecha_devolucion_prevista->isPast();
    }
}