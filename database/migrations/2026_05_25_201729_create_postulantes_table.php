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
        Schema::create('postulantes', function (Blueprint $table) {

            $table->id();

            $table->string('ci',20)
                ->unique();

            $table->string('nombres',100);

            $table->string('apellidos',100);

            $table->date('fecha_nacimiento');

            $table->enum('sexo',[
                'Masculino',
                'Femenino'
            ]);

            $table->string('direccion');

            $table->string('telefono',20);

            $table->string('email')
                ->unique();

            $table->string('colegio');

            $table->string('ciudad');

            $table->string('titulo_bachiller');

            $table->text('otros_requisitos')
                ->nullable();

            /*
            Primera opción
            */

            $table->foreignId(
                'carrera_primera_opcion_id'
            )
            ->constrained('carreras')
            ->cascadeOnUpdate()
            ->restrictOnDelete();

            /*
            Segunda opción
            */

            $table->foreignId(
                'carrera_segunda_opcion_id'
            )
            ->nullable()
            ->constrained('carreras')
            ->cascadeOnUpdate()
            ->restrictOnDelete();

            /*
            Grupo asignado
            */

            $table->foreignId('grupo_id')
                ->nullable()
                ->constrained('grupos')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            /*
            Reportes rápidos
            */

            $table->decimal(
                'promedio_final',
                5,
                2
            )->default(0);

            $table->enum(
                'estado_final',
                [
                    'APROBADO',
                    'REPROBADO',
                    'PENDIENTE'
                ]
            )->default('PENDIENTE');

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
        Schema::dropIfExists('postulantes');
    }
};