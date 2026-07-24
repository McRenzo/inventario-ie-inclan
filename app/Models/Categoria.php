<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $table = 'categorias';

    protected $fillable = [
        'nombre',
        'descripcion',
        'es_depreciable',
        'vida_util_meses',
        'valor_residual_porcentaje',
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

    public function bienes()
    {
        return $this->hasMany(Bien::class, 'categoria_id');
    }
}