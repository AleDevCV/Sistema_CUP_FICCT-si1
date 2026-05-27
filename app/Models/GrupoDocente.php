<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GrupoDocente extends Model
{
    protected $table='grupo_docentes';

    protected $fillable=[

        'docente_id',
        'grupo_id',
        'materia_id'

    ];

    /*
    Pertenece a docente
    */

    public function docente():BelongsTo
    {
        return $this->belongsTo(
            Docente::class
        );
    }

    /*
    Pertenece a grupo
    */

    public function grupo():BelongsTo
    {
        return $this->belongsTo(
            Grupo::class
        );
    }

    /*
    Pertenece a materia
    */

    public function materia():BelongsTo
    {
        return $this->belongsTo(
            Materia::class
        );
    }
}