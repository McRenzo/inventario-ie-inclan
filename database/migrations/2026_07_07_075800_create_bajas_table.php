<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bajas', function (Blueprint $table) {
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

            $table->decimal('cantidad', 12, 2)->nullable();

            $table->enum('motivo', [
                'inservible',
                'perdida',
                'robo',
                'donacion',
                'transferencia',
                'obsolescencia',
                'descarte',
                'otro',
            ]);

            $table->dateTime('fecha_baja');
            $table->decimal('valor_al_momento_baja', 14, 2)->nullable();
            $table->string('moneda', 3)->default('PEN');

            $table->string('documento_sustento')->nullable();
            $table->string('destino_final')->nullable();
            $table->text('observaciones')->nullable();

            $table->foreignId('registrado_por')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index('motivo');
            $table->index('fecha_baja');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bajas');
    }
};
