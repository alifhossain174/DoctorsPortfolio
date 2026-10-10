<?php

namespace App\Models;

use App\Services\ContentRegistry;
use App\Services\LocalizedContent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

abstract class CmsModel extends Model
{
    public function getFillable(): array
    {
        return array_keys(config('cms_models.'.class_basename($this), []));
    }

    protected static function booted(): void
    {
        static::saving(function (self $model) {
            $data = [];
            $extra = array_diff(array_keys($model->getDirty()), [...$model->getFillable(), 'id', 'created_at', 'updated_at']);
            if ($extra) {
                throw ValidationException::withMessages([reset($extra) => 'This field is not editable.']);
            }
            foreach ($model->getFillable() as $field) {
                $data[$field] = $model->getAttribute($field);
            }
            app(ContentRegistry::class)->validate(class_basename($model), $data);
        });
    }

    public function localized(string $field, ?string $locale = null, mixed $empty = ''): mixed
    {
        return app(LocalizedContent::class)->resolve($this->getAttribute($field), $locale, $empty);
    }
}
