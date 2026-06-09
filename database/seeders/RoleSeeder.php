<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Administrador', 'Docente', 'Coordinador', 'Autoridad', 'Postulante'] as $name) {
            Role::findOrCreate($name);
        }
    }
}