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
        			// Eliminar roles antiguos que tenían nombres abreviados
			Role::whereIn('name', ['Coord. Académico', 'Coord. Convivencia'])->delete();

			$roles = [
            'Administrador',
            'Rector',
            'Coordinador Académico',
            'Coordinador de Convivencia',
            'Talento Humano'
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Crear usuario administrador
                    $admin = User::firstOrCreate([
                'email' => 'admin@institucion.edu.co',
            ], [
                'name' => 'Administrador',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]);

        $admin->assignRole('Administrador');
    }
}
