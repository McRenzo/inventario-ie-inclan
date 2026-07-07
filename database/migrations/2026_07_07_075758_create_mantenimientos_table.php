<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mantenimientos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();

            $table->foreignId('unidad_bien_id')
                ->nullable()
                ->constrained('unidades_bien')
                ->nullOnDelete();

            $table->foreignId('lote_id')
                ->nullable()
                ->constrained('lotes')
                ->nullOnDelete();

            $table->enum('tipo', [
                'preventivo',
                'correctivo',
            ]);

            $table->text('falla_reportada');
            $table->text('diagnostico')->nullable();

            $table->string('tecnico_proveedor')->nullable();

            $table->dateTime('fecha_ingreso');
            $table->dateTime('fecha_retorno_estimada')->nullable();
            $table->dateTime('fecha_salida')->nullable();

            $table->decimal('costo', 14, 2)->nullable();
            $table->string('moneda', 3)->default('PEN');

            $table->foreignId('estado_conservacion_anterior_id')
                ->nullable()
                ->constrained('estados_conservacion')
                ->nullOnDelete();

            $table->foreignId('estado_conservacion_final_id')
                ->nullable()
                ->constrained('estados_conservacion')
                ->nullOnDelete();

            $table->foreignId('estado_operatividad_anterior_id')
                ->nullable()
                ->constrained('estados_operatividad')
                ->nullOnDelete();

            $table->foreignId('estado_operatividad_final_id')
                ->nullable()
                ->constrained('estados_operatividad')
                ->nullOnDelete();

            $table->enum('estado', [
                'pendiente',
                'en_proceso',
                'finalizado',
                'cancelado',
            ])->default('pendiente');

            $table->text('resultado')->nullable();
            $table->text('observaciones')->nullable();

            $table->foreignId('registrado_por')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('finalizado_por')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index('estado');
            $table->index('fecha_ingreso');
            $table->index('fecha_retorno_estimada');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mantenimientos');
    }
};