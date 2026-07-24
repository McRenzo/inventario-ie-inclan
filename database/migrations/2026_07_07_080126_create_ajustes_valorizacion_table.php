<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ajustes_valorizacion', function (Blueprint $table) {
            $table->id();

            $table->foreignId('unidad_bien_id')
                ->nullable()
                ->constrained('unidades_bien')
                ->nullOnDelete();

            $table->foreignId('lote_id')
                ->nullable()
                ->constrained('lotes')
                ->nullOnDelete();

            $table->enum('tipo_ajuste', [
                'valor_adquisicion',
                'valor_residual',
                'vida_util',
                'depreciacion_acumulada',
                'valor_en_libros',
                'revaluacion',
                'deterioro',
                'correccion',
            ]);

            $table->decimal('valor_anterior', 14, 2)->nullable();
            $table->decimal('valor_nuevo', 14, 2)->nullable();

            $table->unsignedInteger('vida_util_anterior_meses')->nullable();
            $table->unsignedInteger('vida_util_nueva_meses')->nullable();

            $table->string('moneda', 3)->default('PEN');

            $table->text('motivo');
            $table->string('documento_sustento')->nullable();

            $table->dateTime('fecha_ajuste');

            $table->foreignId('usuario_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index('tipo_ajuste');
            $table->index('fecha_ajuste');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ajustes_valorizacion');
    }
};