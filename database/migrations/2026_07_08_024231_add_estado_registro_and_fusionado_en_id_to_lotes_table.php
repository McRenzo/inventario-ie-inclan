<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('lotes', function (Blueprint $table) {
            $table->string('estado_registro', 20)
                ->default('activo')
                ->after('situacion');

            $table->foreignId('fusionado_en_id')
                ->nullable()
                ->after('estado_registro')
                ->constrained('lotes')
                ->nullOnDelete();

            $table->timestamp('fecha_fusion')
                ->nullable()
                ->after('fusionado_en_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lotes', function (Blueprint $table) {
            $table->dropForeign(['fusionado_en_id']);

            $table->dropColumn([
                'estado_registro',
                'fusionado_en_id',
                'fecha_fusion',
            ]);
        });
    }
};
