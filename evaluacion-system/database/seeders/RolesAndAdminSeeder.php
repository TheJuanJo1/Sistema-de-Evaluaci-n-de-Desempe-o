<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear roles
        $roles = [
            'Administrador',
            'Rector',
            'Coord. Académico',
            'Coord. Convivencia',
            'Talento Humano'
        ];

        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

        // Crear usuario administrador
        $admin = User::create([
            'name' => 'Administrador',
            'email' => 'admin@institucion.edu.co',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);

        $admin->assignRole('Administrador');
    }
}
