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
        Schema::create('docentes', function (Blueprint $table) {

            $table->id();

            /*
            Usuario asociado
            */

            $table->foreignId('user_id')
                ->unique()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('ci',20)
                ->unique();

            $table->string('nombres',100);

            $table->string('apellidos',100);

            $table->string('telefono',20)
                ->nullable();

            $table->string('email')
                ->unique();

            /*
            Requisitos del examen
            */

            $table->string('profesion');

            $table->boolean('maestria')
                ->default(false);

            $table->boolean(
                'diplomado_educacion_superior'
            )->default(false);

            /*
            Contratación
            */

            $table->boolean('contratado')
                ->default(false);

            $table->boolean('estado')
                ->default(true);

            $table->timestamps();

        });
    }

    /**
     * Reverse migrations
     */
    public function down(): void
    {
        Schema::dropIfExists('docentes');
    }
};