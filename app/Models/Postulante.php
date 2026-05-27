<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Postulante extends Model
{
    protected $fillable = [

        'ci',
        'nombres',
        'apellidos',
        'fecha_nacimiento',
        'sexo',
        'direccion',
        'telefono',
        'email',
        'colegio',
        'ciudad',
        'titulo_bachiller',
        'otros_requisitos',
        'carrera_primera_opcion_id',
        'carrera_segunda_opcion_id',
        'grupo_id',
        'promedio_final',
        'estado_final',
        'estado'

    ];

    protected function casts(): array
    {
        return [

            'fecha_nacimiento' => 'date',
            'promedio_final' => 'decimal:2',
            'estado' => 'boolean'

        ];
    }

    /*
    Primera carrera
    */
    public function primeraCarrera(): BelongsTo
    {
        return $this->belongsTo(
            Carrera::class,
            'carrera_primera_opcion_id'
        );
    }

    /*
    Segunda carrera
    */
    public function segundaCarrera(): BelongsTo
    {
        return $this->belongsTo(
            Carrera::class,
            'carrera_segunda_opcion_id'
        );
    }

    /*
    Grupo asignado
    */
    public function grupo(): BelongsTo
    {
        return $this->belongsTo(
            Grupo::class
        );
    }

    /*
    Exámenes
    */
    public function examenes(): HasMany
    {
        return $this->hasMany(
            Examen::class
        );
    }

    /*
    Pago
    */
    public function pago(): HasOne
    {
        return $this->hasOne(
            Pago::class
        );
    }

    /*
    Nombre completo (Accessor)
    */
    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombres} {$this->apellidos}";
    }
}