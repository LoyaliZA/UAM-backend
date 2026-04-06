<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Administrador Principal (Tu acceso)
        User::firstOrCreate(
            ['email' => 'realloyal1a@gmail.com'],
            [
                'name' => 'Admin Gabriel',
                'password' => Hash::make('Gabo117@') // Cambia esto por una contraseña segura
            ]
        );

        // 2. Auditor Secundario (Ejemplo de como agregar más)
        User::firstOrCreate(
            ['email' => 'itzelaromas@gmail.com'],
            [
                'name' => 'Itzel Aromas',
                'password' => Hash::make('auditor2026')
            ]
        );
    }
}