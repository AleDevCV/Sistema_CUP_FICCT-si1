<?php

namespace Database\Seeders;

use App\Models\Grupo;
use Illuminate\Database\Seeder;

class GrupoSeeder extends Seeder
{
    public function run(): void
    {
        Grupo::insert([

            [

                'nombre'=>'Grupo A',
                'codigo'=>'GRP-001',
                'aula'=>'LAB-1',
                'horario'=>'Lunes-Miércoles 08:00-10:00'

            ],

            [

                'nombre'=>'Grupo B',
                'codigo'=>'GRP-002',
                'aula'=>'LAB-2',
                'horario'=>'Martes-Jueves 08:00-10:00'

            ]

        ]);
    }
}