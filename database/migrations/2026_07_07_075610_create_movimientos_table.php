<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimientos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('unidad_bien_id')
                ->nullable()
                ->constrained('unidades_bien')
                ->nullOnDelete();

            $table->foreignId('lote_id')
                ->nullable()
                ->constrained('lotes')
                ->nullOnDelete();

            $table->enum('tipo', [
                'registro_inicial',
                'asignacion',
                'cambio_ubicacion',
                'cambio_area',
                'cambio_responsable',
                'prestamo',
                'devolucion',
                'entrada_mantenimiento',
                'salida_mantenimiento',
                'cambio_estado',
                'ajuste_cantidad',
                'baja',
                'correccion',
            ]);

            $table->dateTime('fecha_movimiento');

            $table->decimal('cantidad', 12, 2)->nullable();

            $table->foreignId('area_anterior_id')
                ->nullable()
                ->constrained('areas')
                ->nullOnDelete();

            $table->foreignId('area_nueva_id')
                ->nullable()
                ->constrained('areas')
                ->nullOnDelete();

            $table->foreignId('ubicacion_anterior_id')
                ->nullable()
                ->constrained('ubicaciones')
                ->nullOnDelete();

            $table->foreignId('ubicacion_nueva_id')
                ->nullable()
                ->constrained('ubicaciones')
                ->nullOnDelete();

            $table->foreignId('estado_conservacion_anterior_id')
                ->nullable()
                ->constrained('estados_conservacion')
                ->nullOnDelete();

            $table->foreignId('estado_conservacion_nuevo_id')
                ->nullable()
                ->constrained('estados_conservacion')
                ->nullOnDelete();

            $table->foreignId('estado_operatividad_anterior_id')
                ->nullable()
                ->constrained('estados_operatividad')
                ->nullOnDelete();

            $table->foreignId('estado_operatividad_nuevo_id')
                ->nullable()
                ->constrained('estados_operatividad')
                ->nullOnDelete();

            $table->string('situacion_anterior')->nullable();
            $table->string('situacion_nueva')->nullable();

            $table->string('responsable_anterior_nombre')->nullable();
            $table->string('responsable_anterior_dni', 20)->nullable();

            $table->string('responsable_nuevo_nombre')->nullable();
            $table->string('responsable_nuevo_dni', 20)->nullable();

            $table->text('observacion')->nullable();
            $table->string('documento_referencia')->nullable();

            $table->foreignId('usuario_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index('tipo');
            $table->index('fecha_movimiento');
            $table->index(['unidad_bien_id', 'fecha_movimiento']);
            $table->index(['lote_id', 'fecha_movimiento']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos');
    }
};