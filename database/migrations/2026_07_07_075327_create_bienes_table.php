<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bienes', function (Blueprint $table) {
            $table->id();

            $table->string('nombre');
            $table->text('descripcion')->nullable();

            $table->foreignId('categoria_id')
                ->nullable()
                ->constrained('categorias')
                ->nullOnDelete();

            $table->enum('tipo_control', [
                'individual',
                'lote',
                'consumible',
            ]);

            $table->string('marca')->nullable();
            $table->string('modelo')->nullable();
            $table->string('material')->nullable();
            $table->string('nivel_educativo')->nullable();
            $table->string('ciclo')->nullable();

            $table->string('procedencia')->nullable();
            $table->foreignId('fuente_financiamiento_id')
                ->nullable()
                ->constrained('fuentes_financiamiento')
                ->nullOnDelete();

            $table->boolean('es_depreciable')->default(false);
            $table->unsignedInteger('vida_util_meses')->nullable();
            $table->decimal('valor_residual_porcentaje', 5, 2)->default(0);

            $table->text('observaciones')->nullable();

            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->index('nombre');
            $table->index('tipo_control');
            $table->index('marca');
            $table->index('modelo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bienes');
    }
};