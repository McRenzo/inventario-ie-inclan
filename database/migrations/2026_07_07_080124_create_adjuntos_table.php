<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('adjuntos', function (Blueprint $table) {
            $table->id();

            $table->string('adjuntable_type');
            $table->unsignedBigInteger('adjuntable_id');

            $table->string('tipo_documento')->nullable();
            $table->string('nombre_original');
            $table->string('nombre_archivo');
            $table->string('ruta');
            $table->string('mime_type', 100)->nullable();
            $table->unsignedBigInteger('tamano_bytes')->nullable();

            $table->text('observaciones')->nullable();

            $table->foreignId('subido_por')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index(
                ['adjuntable_type', 'adjuntable_id'],
                'adjuntos_adjuntable_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('adjuntos');
    }
};