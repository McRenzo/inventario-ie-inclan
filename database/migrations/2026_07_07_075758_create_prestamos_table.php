<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prestamos', function (Blueprint $table) {
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

            $table->string('receptor_nombre');
            $table->string('receptor_dni', 20)->nullable();
            $table->string('receptor_cargo')->nullable();
            $table->string('receptor_area')->nullable();
            $table->string('receptor_telefono', 30)->nullable();

            $table->dateTime('fecha_prestamo');
            $table->dateTime('fecha_devolucion_prevista')->nullable();
            $table->dateTime('fecha_devolucion_real')->nullable();

            $table->foreignId('estado_conservacion_salida_id')
                ->nullable()
                ->constrained('estados_conservacion')
                ->nullOnDelete();

            $table->foreignId('estado_conservacion_devolucion_id')
                ->nullable()
                ->constrained('estados_conservacion')
                ->nullOnDelete();

            $table->enum('estado', [
                'activo',
                'devuelto',
                'vencido',
                'cancelado',
            ])->default('activo');

            $table->text('observaciones_salida')->nullable();
            $table->text('observaciones_devolucion')->nullable();

            $table->foreignId('registrado_por')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('devuelto_por')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index('estado');
            $table->index('fecha_prestamo');
            $table->index('fecha_devolucion_prevista');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prestamos');
    }
};