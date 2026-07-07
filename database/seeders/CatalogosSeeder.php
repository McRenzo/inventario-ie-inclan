<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatalogosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('estados_conservacion')->insert([
            [
                'nombre' => 'Bueno',
                'descripcion' => 'El bien se encuentra en buenas condiciones físicas.',
                'orden' => 1,
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Regular',
                'descripcion' => 'El bien presenta desgaste, pero todavía puede utilizarse.',
                'orden' => 2,
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Malo',
                'descripcion' => 'El bien presenta daños importantes.',
                'orden' => 3,
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Inservible',
                'descripcion' => 'El bien ya no puede utilizarse.',
                'orden' => 4,
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'No determinado',
                'descripcion' => 'El estado físico todavía no ha sido verificado.',
                'orden' => 5,
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('estados_operatividad')->insert([
            [
                'nombre' => 'Operativo',
                'descripcion' => 'El bien funciona correctamente.',
                'orden' => 1,
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Operatividad limitada',
                'descripcion' => 'El bien funciona parcialmente o con restricciones.',
                'orden' => 2,
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Inoperativo',
                'descripcion' => 'El bien no funciona.',
                'orden' => 3,
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'En mantenimiento',
                'descripcion' => 'El bien está siendo revisado o reparado.',
                'orden' => 4,
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'No aplica',
                'descripcion' => 'La operatividad no corresponde para este tipo de bien.',
                'orden' => 5,
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'No determinado',
                'descripcion' => 'La operatividad todavía no ha sido verificada.',
                'orden' => 6,
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('areas')->insert([
            [
                'nombre' => 'Comunicaciones',
                'descripcion' => null,
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Ingeniería',
                'descripcion' => null,
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Intendencia',
                'descripcion' => null,
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Sanidad',
                'descripcion' => null,
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Biblioteca',
                'descripcion' => null,
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Laboratorio',
                'descripcion' => null,
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Música',
                'descripcion' => null,
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Soporte técnico',
                'descripcion' => null,
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('fuentes_financiamiento')->insert([
            [
                'nombre' => 'Recursos ordinarios',
                'descripcion' => null,
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Donación',
                'descripcion' => null,
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Transferencia MINEDU',
                'descripcion' => null,
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Recursos propios',
                'descripcion' => null,
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'No determinada',
                'descripcion' => null,
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}