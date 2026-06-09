<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'ver-usuarios',
            'crear-usuarios',
            'editar-usuarios',
            'eliminar-usuarios',
            'ver-roles',
            'configurar-roles',
            'ver-carreras',
            'gestionar-carreras',
            'ver-materias',
            'gestionar-materias',
            'ver-postulantes',
            'gestionar-postulantes',
            'ver-docentes',
            'gestionar-docentes',
            'ver-grupos',
            'gestionar-grupos',
            'ver-examenes',
            'gestionar-examenes',
            'ver-pagos',
            'gestionar-pagos',
            'ver-dashboard',
        ];

        foreach ($permissions as $perm) {
            Permission::updateOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        $admin = Role::findByName('Administrador');
        $admin->syncPermissions($permissions);
    }
}
