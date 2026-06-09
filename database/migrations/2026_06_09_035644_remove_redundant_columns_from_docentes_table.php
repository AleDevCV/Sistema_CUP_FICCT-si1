<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('docentes', function (Blueprint $table) {
            $table->dropColumn(['nombres', 'apellidos', 'email', 'profesion']);
        });
    }

    public function down(): void
    {
        Schema::table('docentes', function (Blueprint $table) {
            $table->string('nombres', 100);
            $table->string('apellidos', 100);
            $table->string('email')->unique();
            $table->string('profesion');
        });
    }
};
