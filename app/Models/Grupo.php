<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Grupo extends Model
{
    protected $fillable=[

        'nombre',
        'codigo',
        'aula',
        'horario',
        'capacidad_maxima',
        'estado'

    ];

    /*
    Un grupo tiene muchos postulantes
    */
    public function postulantes():HasMany
    {
        return $this->hasMany(Postulante::class);
    }

    /*
    Un grupo puede tener varias asignaciones
    docente-materia
    */
    public function grupoDocentes():HasMany
    {
        return $this->hasMany(
            GrupoDocente::class
        );
    }

    /*
    Contar alumnos del grupo
    */
    public function totalAlumnos()
    {
        return $this->postulantes()->count();
    }

    /*
    Verificar espacio disponible
    */
    public function tieneCupo()
    {
        return $this->totalAlumnos()
            < $this->capacidad_maxima;
    }
}