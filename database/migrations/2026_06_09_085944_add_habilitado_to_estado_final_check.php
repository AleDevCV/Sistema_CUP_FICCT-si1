<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // PostgreSQL: recrear CHECK constraint para incluir HABILITADO
        DB::statement("ALTER TABLE postulantes DROP CONSTRAINT IF EXISTS postulantes_estado_final_check");
        DB::statement("ALTER TABLE postulantes ADD CONSTRAINT postulantes_estado_final_check CHECK (estado_final::text = ANY (ARRAY['APROBADO'::text, 'REPROBADO'::text, 'PENDIENTE'::text, 'HABILITADO'::text]))");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE postulantes DROP CONSTRAINT IF EXISTS postulantes_estado_final_check");
        DB::statement("ALTER TABLE postulantes ADD CONSTRAINT postulantes_estado_final_check CHECK (estado_final::text = ANY (ARRAY['APROBADO'::text, 'REPROBADO'::text, 'PENDIENTE'::text]))");
    }
};
