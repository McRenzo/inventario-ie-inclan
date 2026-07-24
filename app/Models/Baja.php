<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Baja extends Model
{
    use HasFactory;

    protected $table = 'bajas';

    protected $fillable = [
        'codigo',
        'unidad_bien_id',
        'lote_id',
        'cantidad',
        'motivo',
        'fecha_baja',
        'valor_al_momento_baja',
        'moneda',
        'documento_sustento',
        'destino_final',
        'observaciones',
        'registrado_por',
    ];

    protected function casts(): array
    {
        return [
            'cantidad' => 'decimal:2',
            'fecha_baja' => 'datetime',
            'valor_al_momento_baja' => 'decimal:2',
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

    public function registradoPor()
    {
        return $this->belongsTo(User::class, 'registrado_por');
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