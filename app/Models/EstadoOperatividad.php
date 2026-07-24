<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoOperatividad extends Model
{
    use HasFactory;

    protected $table = 'estados_operatividad';

    protected $fillable = [
        'nombre',
        'descripcion',
        'orden',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'orden' => 'integer',
            'activo' => 'boolean',
        ];
    }

    public function unidades()
    {
        return $this->hasMany(UnidadBien::class, 'estado_operatividad_id');
    }

    public function lotes()
    {
        return $this->hasMany(Lote::class, 'estado_operatividad_id');
    }
}