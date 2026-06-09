<?php

namespace App\Traits;

use App\Models\Auditoria;

trait Auditable
{
    protected static function bootAuditable(): void
    {
        static::created(function ($model) {
            Auditoria::create([
                'user_id'            => auth()->id(),
                'ip_address'         => request()->ip(),
                'accion'             => 'created',
                'modelo'             => class_basename($model),
                'modelo_id'          => $model->getKey(),
                'valores_anteriores' => null,
                'valores_nuevos'     => $model->getAttributes(),
            ]);
        });

        static::updated(function ($model) {
            $changes = $model->getChanges();
            $original = array_intersect_key($model->getOriginal(), $changes);

            Auditoria::create([
                'user_id'            => auth()->id(),
                'ip_address'         => request()->ip(),
                'accion'             => 'updated',
                'modelo'             => class_basename($model),
                'modelo_id'          => $model->getKey(),
                'valores_anteriores' => $original,
                'valores_nuevos'     => $changes,
            ]);
        });

        static::deleted(function ($model) {
            Auditoria::create([
                'user_id'            => auth()->id(),
                'ip_address'         => request()->ip(),
                'accion'             => 'deleted',
                'modelo'             => class_basename($model),
                'modelo_id'          => $model->getKey(),
                'valores_anteriores' => $model->getOriginal(),
                'valores_nuevos'     => null,
            ]);
        });
    }
}
