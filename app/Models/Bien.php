<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bien extends Model
{
    use HasFactory;

    protected $table = 'bienes';

    protected $fillable = [
        'nombre',
        'descripcion',
        'categoria_id',
        'tipo_control',
        'marca',
        'modelo',
        'material',
        'nivel_educativo',
        'ciclo',
        'procedencia',
        'fuente_financiamiento_id',
        'es_depreciable',
        'vida_util_meses',
        'valor_residual_porcentaje',
        'observaciones',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'es_depreciable' => 'boolean',
            'vida_util_meses' => 'integer',
            'valor_residual_porcentaje' => 'decimal:2',
            'activo' => 'boolean',
        ];
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function fuenteFinanciamiento()
    {
        return $this->belongsTo(
            FuenteFinanciamiento::class,
            'fuente_financiamiento_id'
        );
    }

    public function unidades()
    {
        return $this->hasMany(UnidadBien::class, 'bien_id');
    }

    public function lotes()
    {
        return $this->hasMany(Lote::class, 'bien_id');
    }
}