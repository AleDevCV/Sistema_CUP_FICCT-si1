<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run migrations
     */
    public function up(): void
    {
        Schema::create('grupo_docentes', function (Blueprint $table) {

            $table->id();

            /*
            Docente
            */

            $table->foreignId('docente_id')
                ->constrained('docentes')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            /*
            Grupo
            */

            $table->foreignId('grupo_id')
                ->constrained('grupos')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            /*
            Materia
            */

            $table->foreignId('materia_id')
                ->constrained('materias')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->timestamps();

            /*
            Evitar duplicados
            */

            $table->unique([
                'docente_id',
                'grupo_id',
                'materia_id'
            ]);

        });
    }

    /**
     * Reverse migrations
     */
    public function down(): void
    {
        Schema::dropIfExists('grupo_docentes');
    }
};