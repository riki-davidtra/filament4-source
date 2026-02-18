<?php

namespace App\Models\Concerns;

use Ramsey\Uuid\Uuid;

trait HasPublicUuid
{
    protected static function bootHasPublicUuid()
    {
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Uuid::uuid7();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }
}
