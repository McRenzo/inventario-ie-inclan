<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Prestamo;

class Lote extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'lotes';

    protected $fillable = [
        'bien_id',
        'codigo_interno',
        'cantidad_inicial',
        'cantidad_actual',
        'unidad_medida',
        'area_id',
        'ubicacion_id',
        'estado_conservacion_id',
        'estado_operatividad_id',
        'situacion',
        'responsable_nombre',
        'responsable_dni',
        'responsable_cargo',
        'responsable_area',
        'responsable_telefono',
        'fecha_adquisicion',
        'fecha_ingreso',
        'fecha_puesta_en_uso',
        'anio_ingreso',
        'vida_util_meses',
        'valor_unitario',
        'valor_total',
        'valor_residual',
        'depreciacion_acumulada',
        'valor_en_libros',
        'valor_ajustado',
        'moneda',
        'proveedor',
        'tipo_comprobante',
        'numero_comprobante',
        'estado_origen',
        'ubicacion_origen',
        'archivo_origen',
        'hoja_origen',
        'fila_origen',
        'foto_principal',
        'observaciones',
        'creado_por',
        'estado_registro',
        'fusionado_en_id',
        'fecha_fusion',
    ];

    protected function casts(): array
    {
        return [
            'cantidad_inicial' => 'decimal:2',
            'cantidad_actual' => 'decimal:2',
            'fecha_adquisicion' => 'date',
            'fecha_ingreso' => 'date',
            'fecha_puesta_en_uso' => 'date',
            'anio_ingreso' => 'integer',
            'vida_util_meses' => 'integer',
            'valor_unitario' => 'decimal:2',
            'valor_total' => 'decimal:2',
            'valor_residual' => 'decimal:2',
            'depreciacion_acumulada' => 'decimal:2',
            'valor_en_libros' => 'decimal:2',
            'valor_ajustado' => 'decimal:2',
            'fila_origen' => 'integer',
            'fecha_fusion' => 'datetime',
        ];
    }

    public function bien()
    {
        return $this->belongsTo(Bien::class, 'bien_id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function ubicacion()
    {
        return $this->belongsTo(Ubicacion::class, 'ubicacion_id');
    }

    public function estadoConservacion()
    {
        return $this->belongsTo(
            EstadoConservacion::class,
            'estado_conservacion_id'
        );
    }

    public function estadoOperatividad()
    {
        return $this->belongsTo(
            EstadoOperatividad::class,
            'estado_operatividad_id'
        );
    }

    public function creador()
    {
        return $this->belongsTo(User::class, 'creado_por');
    }

    public function movimientos()
    {
        return $this->hasMany(Movimiento::class, 'lote_id');
    }

    public function prestamos()
    {
        return $this->hasMany(Prestamo::class, 'lote_id');
    }

    public function mantenimientos()
    {
        return $this->hasMany(Mantenimiento::class, 'lote_id');
    }

    public function bajas()
    {
        return $this->hasMany(Baja::class, 'lote_id');
    }

    public function ajustesValorizacion()
    {
        return $this->hasMany(
            AjusteValorizacion::class,
            'lote_id'
        );
    }

    public function adjuntos()
    {
        return $this->morphMany(Adjunto::class, 'adjuntable');
    }

    public function getValorActualAttribute()
    {
        return $this->valor_ajustado ?? $this->valor_en_libros;
    }

    public function fusionadoEn()
    {
        return $this->belongsTo(
            Lote::class,
            'fusionado_en_id'
        );
    }

    public function lotesFusionados()
    {
        return $this->hasMany(
            Lote::class,
            'fusionado_en_id'
        );
    }
}