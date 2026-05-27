<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Examen extends Model
{  
    protected $table='examenes';
    protected $fillable=[

        'postulante_id',
        'materia_id',
        'numero_examen',
        'nota',
        'porcentaje'

    ];

    protected function casts():array
    {
        return [

            'nota'=>'decimal:2',
            'porcentaje'=>'decimal:2'

        ];
    }

    /*
    Examen pertenece a postulante
    */

    public function postulante():BelongsTo
    {
        return $this->belongsTo(
            Postulante::class
        );
    }

    /*
    Examen pertenece a materia
    */

    public function materia():BelongsTo
    {
        return $this->belongsTo(
            Materia::class
        );
    }

    /*
    Calcular nota ponderada
    */

    public function notaFinal():float
    {
        return (
            $this->nota*
            $this->porcentaje
        )/100;
    }

}