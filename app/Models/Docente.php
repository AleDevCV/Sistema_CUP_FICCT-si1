<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\Auditable;

class Docente extends Model
{
    use Auditable;

    protected $fillable=[

        'user_id',
        'ci',
        'telefono',
        'maestria',
        'diplomado_educacion_superior',
        'contratado',
        'estado'

    ];

    protected function casts():array
    {
        return [

            'maestria'=>'boolean',
            'diplomado_educacion_superior'=>'boolean',
            'contratado'=>'boolean',
            'estado'=>'boolean'

        ];
    }

    /*
    Usuario asociado
    */

    public function user():BelongsTo
    {
        return $this->belongsTo(
            User::class
        );
    }

    /*
    Asignaciones a grupos
    */

    public function grupoDocentes():HasMany
    {
        return $this->hasMany(
            GrupoDocente::class
        );
    }

    /*
    Nombre completo — delegado al modelo User
    */

    public function getNombreCompletoAttribute(): string
    {
        return $this->user?->name ?? 'Sin usuario';
    }

    /*
    Validar contratación
    */

    public function puedeContratarse()
    {
        return
        $this->maestria &&
        $this->diplomado_educacion_superior;
    }

    /*
    Carga horaria actual (CU09): cuantos grupos tiene asignados
    */
    public function cargaActual(): int
    {
        return $this->grupoDocentes()->count();
    }

}