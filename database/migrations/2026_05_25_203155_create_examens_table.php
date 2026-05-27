<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /*
    Run migrations
    */
    public function up(): void
    {
        Schema::create('examenes', function (Blueprint $table) {

            $table->id();

            /*
            Postulante
            */

            $table->foreignId('postulante_id')
                ->constrained('postulantes')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            /*
            Materia
            */

            $table->foreignId('materia_id')
                ->constrained('materias')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            /*
            Número examen
            */

            $table->tinyInteger(
                'numero_examen'
            );

            /*
            Nota
            */

            $table->decimal(
                'nota',
                5,
                2
            );

            /*
            Porcentaje
            */

            $table->decimal(
                'porcentaje',
                5,
                2
            );

            $table->timestamps();

            /*
            Evitar duplicados
            */

            $table->unique([

                'postulante_id',
                'materia_id',
                'numero_examen'

            ]);

        });
    }

    /*
    Reverse migrations
    */
    public function down(): void
    {
        Schema::dropIfExists('examenes');
    }
};