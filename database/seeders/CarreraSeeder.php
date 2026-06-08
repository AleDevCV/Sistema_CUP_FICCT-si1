<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Carrera;

class CarreraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
  public function run(): void
{
    $carreras = [
        ['codigo'=>'INF', 'nombre'=>'Ingeniería Informática', 'cupo'=>100, 'gestion'=>2026],
        ['codigo'=>'SIS', 'nombre'=>'Ingeniería de Sistemas', 'cupo'=>80, 'gestion'=>2026],
        ['codigo'=>'TEL', 'nombre'=>'Ingeniería Telecomunicaciones', 'cupo'=>60, 'gestion'=>2026],
    ];

    foreach ($carreras as $carrera) {
        Carrera::updateOrCreate(
            ['codigo' => $carrera['codigo']],
            $carrera
        );
    }
}
}
