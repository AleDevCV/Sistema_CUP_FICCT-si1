<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Carrera extends Model
{
    protected $fillable=[

        'codigo',
        'nombre',
        'cupo',
        'gestion',
        'estado'

    ];

    /*
    Primera opción
    */
    public function postulantesPrimeraOpcion():HasMany
    {
        return $this->hasMany(
            Postulante::class,
            'carrera_primera_opcion_id'
        );
    }

    /*
    Segunda opción
    */
    public function postulantesSegundaOpcion():HasMany
    {
        return $this->hasMany(
            Postulante::class,
            'carrera_segunda_opcion_id'
        );
    }
}