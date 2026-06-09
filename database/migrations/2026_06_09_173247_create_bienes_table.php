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
            $table->string('codigo_barras_qr')->unique(); 
            $table->enum('origen', ['LOGISTICA', 'MINEDU']);
            $table->string('subcategoria'); 
            $table->text('descripcion'); 
            $table->integer('cantidad')->default(1);
            $table->char('categoria_relevo', 1)->nullable(); 
            $table->string('procedencia')->nullable(); 
            $table->string('estado_conservacion')->nullable(); 
            $table->string('fecha_ingreso_origen')->nullable(); 
            $table->text('observaciones_origen')->nullable(); 
            
            // Campos específicos de Laboratorio
            $table->string('lab_area')->nullable(); 
            $table->string('lab_nivel')->nullable(); 
            $table->text('lab_contenido')->nullable();
            $table->string('lab_detalle_tipo')->nullable();
            $table->string('lab_ciclo')->nullable();
            
            $table->enum('estado_actual', ['disponible', 'asignado_pabellon', 'en_mantenimiento', 'baja_por_daño', 'otros'])->default('disponible');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bienes');
    }
};