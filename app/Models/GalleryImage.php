<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GalleryImage extends CmsModel
{
    protected function casts(): array
    {
        return ['category_id' => 'integer', 'media_asset_id' => 'integer', 'caption' => 'array', 'alt_text' => 'array', 'sort_order' => 'integer', 'is_visible' => 'boolean', 'on_homepage' => 'boolean'];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function mediaAsset(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class);
    }

    public function scopeVisible(Builder $query): void
    {
        $query->where('is_visible', true);
    }
}
