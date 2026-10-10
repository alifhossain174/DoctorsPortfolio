<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends CmsModel
{
    public function getRouteKeyName(): string
    {
        return 'key';
    }

    protected function casts(): array
    {
        return ['label' => 'array', 'meta_title' => 'array', 'meta_description' => 'array', 'og_title' => 'array', 'og_description' => 'array', 'og_media_asset_id' => 'integer'];
    }

    public function fields(): HasMany
    {
        return $this->hasMany(ContentField::class);
    }

    public function sections(): HasMany
    {
        return $this->hasMany(PageSection::class);
    }

    public function ogImage(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'og_media_asset_id');
    }
}
