<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{
    use HasFactory;

    protected $table = 'auditorias';

    protected $fillable = [
        'usuario_id',
        'accion',
        'modulo',
        'auditable_type',
        'auditable_id',
        'valores_anteriores',
        'valores_nuevos',
        'motivo',
        'ip_address',
        'user_agent',
        'fecha_accion',
    ];

    protected function casts(): array
    {
        return [
            'valores_anteriores' => 'array',
            'valores_nuevos' => 'array',
            'fecha_accion' => 'datetime',
        ];
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function auditable()
    {
        return $this->morphTo();
    }
}