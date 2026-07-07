<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ubicaciones', function (Blueprint $table) {
            $table->id();

            $table->string('nombre');
            $table->string('codigo')->nullable()->unique();
            $table->text('descripcion')->nullable();

            $table->foreignId('area_id')
                ->nullable()
                ->constrained('areas')
                ->nullOnDelete();

            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique(['nombre', 'area_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ubicaciones');
    }
};