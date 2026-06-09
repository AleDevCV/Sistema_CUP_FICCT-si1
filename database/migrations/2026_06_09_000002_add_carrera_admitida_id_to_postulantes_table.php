<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Añadir carrera_admitida_id
        Schema::table('postulantes', function (Blueprint $table) {
            $table->foreignId('carrera_admitida_id')
                ->nullable()
                ->after('carrera_segunda_opcion_id')
                ->constrained('carreras')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });

        // 2. Agregar 'Aprobado sin Cupo' al CHECK de estado_final
        DB::statement("ALTER TABLE postulantes DROP CONSTRAINT IF EXISTS postulantes_estado_final_check");

        DB::statement("ALTER TABLE postulantes ADD CONSTRAINT postulantes_estado_final_check CHECK (estado_final IN ('APROBADO','REPROBADO','PENDIENTE','HABILITADO','Aprobado sin Cupo'))");
    }

    public function down(): void
    {
        // Revertir CHECK
        DB::statement("ALTER TABLE postulantes DROP CONSTRAINT IF EXISTS postulantes_estado_final_check");

        DB::statement("ALTER TABLE postulantes ADD CONSTRAINT postulantes_estado_final_check CHECK (estado_final IN ('APROBADO','REPROBADO','PENDIENTE','HABILITADO'))");

        // Revertir columna
        Schema::table('postulantes', function (Blueprint $table) {
            $table->dropForeign(['carrera_admitida_id']);
            $table->dropColumn('carrera_admitida_id');
        });
    }
};
