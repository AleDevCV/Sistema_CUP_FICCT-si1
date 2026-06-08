<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name'        => 'Administrador',
                'description' => 'Control total del sistema'
            ],
            [
                'name'        => 'Docente',
                'description' => 'Gestión académica'
            ],
            [
                'name'        => 'Coordinador',
                'description' => 'Control de grupos'
            ],
            [
                'name'        => 'Autoridad',
                'description' => 'Supervisión'
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']],
                ['description' => $role['description']]
            );
        }
    }
}