<?php

namespace App\Models;

use App\Models\Importacion;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Postulante extends Model
{
    protected $fillable = [

        'user_id',
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
        'carrera_primera_opcion_id',
        'carrera_segunda_opcion_id',
        'grupo_id',
        'importacion_id',
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
    Usuario asociado (creado automáticamente al registrarse)
    */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
    Lote de importación
    */
    public function importacion(): BelongsTo
    {
        return $this->belongsTo(Importacion::class);
    }

    /*
    Nombre completo (Accessor)
    */
    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombres} {$this->apellidos}";
    }
}