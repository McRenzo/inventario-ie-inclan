<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bien extends Model
{
    use HasFactory;

    protected $table = 'bienes';

    // Lista de campos que se pueden llenar desde el Importador o Formulario
    protected $fillable = [
    'codigo_barras_qr', 'nombre', 'descripcion', 'numero_serie', 'cantidad', 
    'procedencia', 'fecha_ingreso_origen', 'categoria_id', 'ubicacion_id', 
    'estado_id', 'estado_actual'
];

    // --- RELACIONES PARA LA BASE DE DATOS ---

    // Un bien pertenece a una Categoría
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    // Un bien pertenece a una Ubicación
    public function ubicacion()
    {
        return $this->belongsTo(Ubicacion::class, 'ubicacion_id');
    }

    // Un bien pertenece a un Estado
    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }
}