<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | 1. Impedir que borrar una ficha de bien elimine sus unidades o lotes
        |--------------------------------------------------------------------------
        */

        Schema::table('unidades_bien', function (Blueprint $table) {
            $table->dropForeign(['bien_id']);

            $table->foreign('bien_id')
                ->references('id')
                ->on('bienes')
                ->restrictOnDelete();
        });

        Schema::table('lotes', function (Blueprint $table) {
            $table->dropForeign(['bien_id']);

            $table->foreign('bien_id')
                ->references('id')
                ->on('bienes')
                ->restrictOnDelete();
        });

        /*
        |--------------------------------------------------------------------------
        | 2. Responsable actual y datos económicos adicionales
        |--------------------------------------------------------------------------
        */

        Schema::table('unidades_bien', function (Blueprint $table) {
            $table->string('responsable_nombre')->nullable()->after('situacion');
            $table->string('responsable_dni', 20)->nullable()->after('responsable_nombre');
            $table->string('responsable_cargo')->nullable()->after('responsable_dni');
            $table->string('responsable_area')->nullable()->after('responsable_cargo');
            $table->string('responsable_telefono', 30)->nullable()->after('responsable_area');

            $table->unsignedInteger('vida_util_meses')
                ->nullable()
                ->after('anio_ingreso');

            $table->index('responsable_dni');
        });

        Schema::table('lotes', function (Blueprint $table) {
            $table->string('responsable_nombre')->nullable()->after('situacion');
            $table->string('responsable_dni', 20)->nullable()->after('responsable_nombre');
            $table->string('responsable_cargo')->nullable()->after('responsable_dni');
            $table->string('responsable_area')->nullable()->after('responsable_cargo');
            $table->string('responsable_telefono', 30)->nullable()->after('responsable_area');

            $table->unsignedInteger('vida_util_meses')
                ->nullable()
                ->after('anio_ingreso');

            $table->decimal('valor_residual', 14, 2)
                ->default(0)
                ->after('valor_total');

            $table->decimal('depreciacion_acumulada', 14, 2)
                ->default(0)
                ->after('valor_residual');

            $table->decimal('valor_en_libros', 14, 2)
                ->nullable()
                ->after('depreciacion_acumulada');

            $table->decimal('valor_ajustado', 14, 2)
                ->nullable()
                ->after('valor_en_libros');

            $table->index('responsable_dni');
        });

        /*
        |--------------------------------------------------------------------------
        | 3. Cada operación debe pertenecer a una unidad o a un lote, no a ambos
        |--------------------------------------------------------------------------
        |
        | MySQL 8 sí aplica estas restricciones CHECK.
        |
        */

        

        /*
        |--------------------------------------------------------------------------
        | 4. Validaciones básicas de cantidades y valores
        |--------------------------------------------------------------------------
        */

        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("
                ALTER TABLE lotes
                ADD CONSTRAINT chk_lotes_cantidades
                CHECK (
                    cantidad_inicial >= 0
                    AND cantidad_actual >= 0
                )
            ");
        }

        
    }

    public function down(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Eliminar restricciones CHECK
        |--------------------------------------------------------------------------
        */

        
        if (DB::getDriverName() === 'mysql') {
            DB::statement("
                ALTER TABLE lotes
                DROP CHECK chk_lotes_cantidades
            ");
        }

        
        
        

        /*
        |--------------------------------------------------------------------------
        | Retirar campos agregados
        |--------------------------------------------------------------------------
        */

        Schema::table('lotes', function (Blueprint $table) {
            $table->dropIndex(['responsable_dni']);

            $table->dropColumn([
                'responsable_nombre',
                'responsable_dni',
                'responsable_cargo',
                'responsable_area',
                'responsable_telefono',
                'vida_util_meses',
                'valor_residual',
                'depreciacion_acumulada',
                'valor_en_libros',
                'valor_ajustado',
            ]);
        });

        Schema::table('unidades_bien', function (Blueprint $table) {
            $table->dropIndex(['responsable_dni']);

            $table->dropColumn([
                'responsable_nombre',
                'responsable_dni',
                'responsable_cargo',
                'responsable_area',
                'responsable_telefono',
                'vida_util_meses',
            ]);
        });

        /*
        |--------------------------------------------------------------------------
        | Restaurar eliminación en cascada original
        |--------------------------------------------------------------------------
        */

        Schema::table('lotes', function (Blueprint $table) {
            $table->dropForeign(['bien_id']);

            $table->foreign('bien_id')
                ->references('id')
                ->on('bienes')
                ->cascadeOnDelete();
        });

        Schema::table('unidades_bien', function (Blueprint $table) {
            $table->dropForeign(['bien_id']);

            $table->foreign('bien_id')
                ->references('id')
                ->on('bienes')
                ->cascadeOnDelete();
        });
    }
};