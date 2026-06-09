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
            $table->foreignId('bien_id')->constrained('bienes')->onDelete('cascade');
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->enum('tipo_movimiento', ['asignacion', 'mantenimiento', 'baja', 'otros']);
            $table->string('detalles_ubicacion')->nullable(); 
            $table->text('observaciones')->nullable(); 
            $table->timestamps(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos');
    }
};