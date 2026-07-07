<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lotes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('bien_id')
                ->constrained('bienes')
                ->cascadeOnDelete();

            $table->string('codigo_interno')->unique();

            $table->decimal('cantidad_inicial', 12, 2);
            $table->decimal('cantidad_actual', 12, 2);

            $table->string('unidad_medida')->default('unidad');

            $table->foreignId('area_id')
                ->nullable()
                ->constrained('areas')
                ->nullOnDelete();

            $table->foreignId('ubicacion_id')
                ->nullable()
                ->constrained('ubicaciones')
                ->nullOnDelete();

            $table->foreignId('estado_conservacion_id')
                ->constrained('estados_conservacion');

            $table->foreignId('estado_operatividad_id')
                ->nullable()
                ->constrained('estados_operatividad')
                ->nullOnDelete();

            $table->enum('situacion', [
                'disponible',
                'asignado',
                'prestado',
                'en_mantenimiento',
                'no_encontrado',
                'en_proceso_de_baja',
                'dado_de_baja',
            ])->default('disponible');

            $table->date('fecha_adquisicion')->nullable();
            $table->date('fecha_ingreso')->nullable();
            $table->date('fecha_puesta_en_uso')->nullable();
            $table->unsignedSmallInteger('anio_ingreso')->nullable();

            $table->decimal('valor_unitario', 14, 2)->nullable();
            $table->decimal('valor_total', 14, 2)->nullable();

            $table->string('moneda', 3)->default('PEN');
            $table->string('proveedor')->nullable();
            $table->string('tipo_comprobante')->nullable();
            $table->string('numero_comprobante')->nullable();

            $table->string('estado_origen')->nullable();
            $table->string('ubicacion_origen')->nullable();
            $table->string('archivo_origen')->nullable();
            $table->string('hoja_origen')->nullable();
            $table->unsignedInteger('fila_origen')->nullable();

            $table->string('foto_principal')->nullable();
            $table->text('observaciones')->nullable();

            $table->foreignId('creado_por')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->softDeletes();
            $table->timestamps();

            $table->index('situacion');
            $table->index('fecha_ingreso');
            $table->index(['area_id', 'ubicacion_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lotes');
    }
};