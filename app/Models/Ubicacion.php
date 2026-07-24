<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ubicacion extends Model
{
    use HasFactory;

    protected $table = 'ubicaciones';

    protected $fillable = [
        'nombre',
        'codigo',
        'descripcion',
        'area_id',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function unidades()
    {
        return $this->hasMany(UnidadBien::class, 'ubicacion_id');
    }

    public function lotes()
    {
        return $this->hasMany(Lote::class, 'ubicacion_id');
    }
}