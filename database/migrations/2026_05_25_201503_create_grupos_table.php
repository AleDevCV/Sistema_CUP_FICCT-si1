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
        Schema::create('grupos', function (Blueprint $table) {

            $table->id();

            $table->string('nombre',50);

            $table->string('codigo',20)
                ->unique();

            $table->string('aula',50)
                ->nullable();

            $table->string('horario',100)
                ->nullable();

            $table->integer('capacidad_maxima')
                ->default(70);

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
        Schema::dropIfExists('grupos');
    }
};