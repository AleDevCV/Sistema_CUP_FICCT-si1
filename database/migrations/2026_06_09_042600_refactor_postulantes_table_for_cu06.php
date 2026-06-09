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
        Schema::table('postulantes', function (Blueprint $table) {
            $table->dropColumn(['otros_requisitos', 'titulo_bachiller']);
        });

        Schema::table('postulantes', function (Blueprint $table) {
            $table->boolean('titulo_bachiller')->default(false)->after('ciudad');
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('postulantes', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'titulo_bachiller']);
            $table->string('titulo_bachiller')->after('ciudad');
            $table->text('otros_requisitos')->nullable()->after('titulo_bachiller');
        });
    }
};
