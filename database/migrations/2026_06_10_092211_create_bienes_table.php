<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('bienes', function (Blueprint $table) {
        $table->id();
        $table->string('codigo_barras_qr')->unique(); // El hash INC-26-...
        $table->string('nombre');
        $table->text('descripcion')->nullable();
        $table->string('numero_serie')->nullable();
        $table->string('procedencia')->nullable();
        $table->date('fecha_ingreso_origen')->nullable();
        
        // Relaciones (Foreign Keys)
        $table->foreignId('categoria_id')->constrained('categorias');
        $table->foreignId('ubicacion_id')->constrained('ubicaciones');
        $table->foreignId('estado_id')->constrained('estados');
        
        $table->string('estado_actual')->default('disponible');
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('bienes');
    }
};