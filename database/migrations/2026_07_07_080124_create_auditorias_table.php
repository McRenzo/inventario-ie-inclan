<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auditorias', function (Blueprint $table) {
            $table->id();

            $table->foreignId('usuario_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('accion', 100);
            $table->string('modulo', 100);

            $table->string('auditable_type')->nullable();
            $table->unsignedBigInteger('auditable_id')->nullable();

            $table->json('valores_anteriores')->nullable();
            $table->json('valores_nuevos')->nullable();

            $table->text('motivo')->nullable();

            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamp('fecha_accion')->useCurrent();

            $table->timestamps();

            $table->index('accion');
            $table->index('modulo');
            $table->index('fecha_accion');

            $table->index(
                ['auditable_type', 'auditable_id'],
                'auditorias_auditable_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auditorias');
    }
};