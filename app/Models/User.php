<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [

        'role_id',
        'name',
        'username',
        'email',
        'password',
        'status'

    ];

    protected $hidden = [

        'password',
        'remember_token'

    ];

    protected $casts = [

        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'status' => 'boolean'

    ];

    /*
    Usuario pertenece a un rol
    */

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function docente(): HasOne
    {
        return $this->hasOne(
            Docente::class
        );
    }

    public function isAdmin(): bool
    {
        return $this->role?->name === 'Administrador';
    }

    public function isDocente(): bool
    {
        return $this->role?->name === 'Docente';
    }
}