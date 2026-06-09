<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Importacion extends Model
{
    protected $table = 'importaciones';

    protected $fillable = ['nombre_archivo', 'revertida'];

    protected $casts = [
        'revertida' => 'boolean',
    ];

    public function postulantes(): HasMany
    {
        return $this->hasMany(Postulante::class);
    }
}
