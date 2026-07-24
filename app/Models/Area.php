<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $table = 'areas';

    protected $fillable = [
        'nombre',
        'descripcion',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function ubicaciones()
    {
        return $this->hasMany(Ubicacion::class, 'area_id');
    }

    public function unidades()
    {
        return $this->hasMany(UnidadBien::class, 'area_id');
    }

    public function lotes()
    {
        return $this->hasMany(Lote::class, 'area_id');
    }
}