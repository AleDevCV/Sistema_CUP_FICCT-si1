<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('materias', function (Blueprint $table) {
            $table->decimal('ponderacion', 5, 2)
                ->default(25.00)
                ->after('estado');
        });
    }

    public function down(): void
    {
        Schema::table('materias', function (Blueprint $table) {
            $table->dropColumn('ponderacion');
        });
    }
};
