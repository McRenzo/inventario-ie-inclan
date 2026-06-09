<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    use HasFactory;

    protected $table = 'movimientos';

    protected $fillable = [
        'bien_id',
        'usuario_id',
        'tipo_movimiento',
        'detalles_ubicacion',
        'observaciones'
    ];

    /**
     * Relación: Un registro de movimiento pertenece a un único bien.
     */
    public function bien()
    {
        return $table->belongsTo(Bien::class, 'bien_id');
    }

    /**
     * Relación: Un movimiento fue registrado por un usuario (personal técnico).
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}