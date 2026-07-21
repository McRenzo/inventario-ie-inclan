<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement("
            ALTER TABLE lotes
            DROP CHECK chk_lotes_cantidades
        ");

        DB::statement("
            ALTER TABLE lotes
            ADD CONSTRAINT chk_lotes_cantidades
            CHECK (
                cantidad_inicial >= 0
                AND cantidad_actual >= 0
            )
        ");
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement("
            ALTER TABLE lotes
            DROP CHECK chk_lotes_cantidades
        ");

        DB::statement("
            ALTER TABLE lotes
            ADD CONSTRAINT chk_lotes_cantidades
            CHECK (
                cantidad_inicial >= 0
                AND cantidad_actual >= 0
                AND cantidad_actual <= cantidad_inicial
            )
        ");
    }
};