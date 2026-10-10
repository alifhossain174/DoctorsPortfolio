<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends CmsModel
{
    protected function casts(): array
    {
        return ['label' => 'array', 'meta_title' => 'array', 'meta_description' => 'array'];
    }

    public function fields(): HasMany
    {
        return $this->hasMany(ContentField::class);
    }

    public function sections(): HasMany
    {
        return $this->hasMany(PageSection::class);
    }
}
