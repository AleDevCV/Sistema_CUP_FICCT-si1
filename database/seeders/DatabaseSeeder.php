<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       $this->call([
    RoleSeeder::class,
    CarreraSeeder::class,
    MateriaSeeder::class,
    GrupoSeeder::class

]);
    }
}
