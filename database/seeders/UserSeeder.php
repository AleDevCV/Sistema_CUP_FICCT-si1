<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name'     => 'Administrador',
                'email'    => 'admin@gmail.com',
                'password' => bcrypt('12345678'),
                'status'   => true,
            ]
        );
        $admin->assignRole('Administrador');

        $docente = User::updateOrCreate(
            ['username' => 'docente'],
            [
                'name'     => 'Docente Demo',
                'email'    => 'docente@gmail.com',
                'password' => bcrypt('12345678'),
                'status'   => true,
            ]
        );
        $docente->assignRole('Docente');
    }
}
