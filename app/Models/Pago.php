<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pago extends Model
{
    protected $fillable=[

        'postulante_id',
        'monto',
        'metodo_pago',
        'codigo_transaccion',
        'estado',
        'fecha_pago'

    ];

    protected function casts():array
    {
        return [

            'monto'=>'decimal:2',
            'fecha_pago'=>'datetime'

        ];
    }

    /**
     * Disparador automático: al aprobar pago, habilita al postulante.
     * El Webhook ahora actualiza al postulante explícitamente.
     * Se deja como respaldo.
     */
    protected static function booted(): void
    {
        static::saved(function (Pago $pago) {
            if ($pago->estado === 'PAGADO' && $pago->postulante && $pago->postulante->estado_final !== 'HABILITADO') {
                $pago->postulante->update(['estado_final' => 'HABILITADO']);
            }
        });
    }

    /*
    Un pago pertenece a un postulante
    */

    public function postulante():BelongsTo
    {
        return $this->belongsTo(
            Postulante::class
        );
    }

    /*
    Verificar si el pago fue aprobado
    */

    public function pagado():bool
    {
        return $this->estado==="PAGADO";
    }

}