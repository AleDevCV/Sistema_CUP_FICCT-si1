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
        Schema::create('pagos', function (Blueprint $table) {

            $table->id();

            /*
            Relación con postulante
            */

            $table->foreignId('postulante_id')
                ->unique()
                ->constrained('postulantes')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            /*
            Información del pago
            */

            $table->decimal(
                'monto',
                10,
                2
            );

            $table->enum(
                'metodo_pago',
                [
                    'Transferencia',
                    'QR',
                    'Tarjeta',
                    'Efectivo'
                ]
            );

            /*
            Código externo
            */

            $table->string(
                'codigo_transaccion'
            )
            ->nullable();

            /*
            Estado
            */

            $table->enum(
                'estado',
                [
                    'PENDIENTE',
                    'PAGADO',
                    'RECHAZADO'
                ]
            )->default('PENDIENTE');

            $table->timestamp(
                'fecha_pago'
            )->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse migrations
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};