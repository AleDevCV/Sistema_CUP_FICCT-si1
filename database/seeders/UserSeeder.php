<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::insert([
            [
                'role_id'    => 1,
                'name'       => 'Administrador',
                'username'   => 'admin',
                'email'      => 'admin@gmail.com',
                'password'   => bcrypt('12345678'),
                'status'     => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id'    => 2,
                'name'       => 'Docente Demo',
                'username'   => 'docente',
                'email'      => 'docente@gmail.com',
                'password'   => bcrypt('12345678'),
                'status'     => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
