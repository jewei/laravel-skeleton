<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use TypeID\TypeID;

trait HasTypeId
{
    public static function bootHasTypeId(): void
    {
        static::creating(function (Model $model): void {
            $model->setAttribute(
                'typeid',
                TypeID::generate($model->getKeyName())
            );
        });
    }
}
