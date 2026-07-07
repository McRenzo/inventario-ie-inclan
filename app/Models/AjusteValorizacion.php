<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AjusteValorizacion extends Model
{
    use HasFactory;

    protected $table = 'ajustes_valorizacion';

    protected $fillable = [
        'unidad_bien_id',
        'lote_id',
        'tipo_ajuste',
        'valor_anterior',
        'valor_nuevo',
        'vida_util_anterior_meses',
        'vida_util_nueva_meses',
        'moneda',
        'motivo',
        'documento_sustento',
        'fecha_ajuste',
        'usuario_id',
    ];

    protected function casts(): array
    {
        return [
            'valor_anterior' => 'decimal:2',
            'valor_nuevo' => 'decimal:2',
            'vida_util_anterior_meses' => 'integer',
            'vida_util_nueva_meses' => 'integer',
            'fecha_ajuste' => 'datetime',
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

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function getElementoAttribute()
    {
        return $this->unidad ?? $this->lote;
    }
}