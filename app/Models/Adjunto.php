<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adjunto extends Model
{
    use HasFactory;

    protected $table = 'adjuntos';

    protected $fillable = [
        'adjuntable_type',
        'adjuntable_id',
        'tipo_documento',
        'nombre_original',
        'nombre_archivo',
        'ruta',
        'mime_type',
        'tamano_bytes',
        'observaciones',
        'subido_por',
    ];

    protected function casts(): array
    {
        return [
            'tamano_bytes' => 'integer',
        ];
    }

    public function adjuntable()
    {
        return $this->morphTo();
    }

    public function subidoPor()
    {
        return $this->belongsTo(User::class, 'subido_por');
    }
}