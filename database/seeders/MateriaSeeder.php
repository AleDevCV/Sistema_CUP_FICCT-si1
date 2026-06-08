<?php

namespace Database\Seeders;

use App\Models\Materia;
use Illuminate\Database\Seeder;

class MateriaSeeder extends Seeder
{
    public function run(): void
    {
        $materias = [
            ['nombre'=>'Computación', 'descripcion'=>'Curso de computación básica'],
            ['nombre'=>'Matemáticas', 'descripcion'=>'Curso de matemáticas'],
            ['nombre'=>'Inglés', 'descripcion'=>'Curso de inglés'],
            ['nombre'=>'Física', 'descripcion'=>'Curso de física'],
        ];

        foreach ($materias as $materia) {
            Materia::updateOrCreate(
                ['nombre' => $materia['nombre']],
                $materia
            );
        }
    }
}