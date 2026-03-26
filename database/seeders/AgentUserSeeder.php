<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AgentUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Crear el usuario de sistema para nuestro Agente
        // Usamos firstOrCreate para que no marque error si corremos el seeder dos veces
        $agent = User::firstOrCreate(
            ['email' => 'agente@uam.local'],
            [
                'name' => 'Agente UAM Endpoint',
                'password' => Hash::make('UAM_Secure_Password_2026!'), // Obligatorio para el modelo User
            ]
        );

        // 2. Borrar tokens anteriores de este agente (por si estamos regenerando la llave)
        $agent->tokens()->delete();

        // 3. Generar el nuevo Token de Acceso con un permiso específico
        $token = $agent->createToken('uam-agent-key', ['logs:create']);

        // 4. Imprimir el Token en la terminal de Ubuntu para que lo puedas copiar
        $this->command->info('=============================================');
        $this->command->info('¡Token del Agente generado exitosamente!');
        $this->command->warn('Copia el siguiente código. Es la API Key que usará tu extensión/agente:');
        $this->command->line($token->plainTextToken);
        $this->command->info('=============================================');
    }
}