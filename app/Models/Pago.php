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