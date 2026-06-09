<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Materia extends Model
{
    protected $fillable = [

        'nombre',
        'descripcion',
        'estado',
        'ponderacion'

    ];

    protected function casts(): array
    {
        return [
            'estado' => 'boolean',
            'ponderacion' => 'decimal:2',
        ];
    }

    /*
    Una materia puede tener muchos exámenes
    */
    public function examenes(): HasMany
    {
        return $this->hasMany(Examen::class);
    }

    /*
    Una materia puede pertenecer a varios grupos-docente
    */
    public function grupoDocentes(): HasMany
    {
        return $this->hasMany(GrupoDocente::class);
    }

    /*
    Carreras a las que pertenece (pivote)
    */
    public function carreras(): BelongsToMany
    {
        return $this->belongsToMany(Carrera::class);
    }
}