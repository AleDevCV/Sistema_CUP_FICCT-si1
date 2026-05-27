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
        Schema::create('materias', function (Blueprint $table) {

            $table->id();

            $table->string('nombre',100)
                ->unique();

            $table->string('descripcion')
                ->nullable();

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
        Schema::dropIfExists('materias');
    }
};