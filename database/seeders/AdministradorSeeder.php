<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdministradorSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'logisticainlcan@gmail.com'],
            [
                'name' => 'Administrador',
                'role' => 'administrador',
                'password' => Hash::make('InclanInventario2026*'),
                'email_verified_at' => now(),
            ]
        );
    }
}