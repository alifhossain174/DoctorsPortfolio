<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends CmsModel
{
    protected function casts(): array
    {
        return ['title' => 'array', 'excerpt' => 'array', 'body' => 'array', 'category_id' => 'integer', 'media_asset_id' => 'integer', 'editorial_badge' => 'array', 'image_alt' => 'array', 'sort_order' => 'integer', 'on_homepage' => 'boolean', 'published_at' => 'immutable_datetime', 'meta_title' => 'array', 'meta_description' => 'array'];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function mediaAsset(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class);
    }

    public function scopePublished(Builder $query): void
    {
        $query->where('status', 'published')->whereNotNull('published_at')->where('published_at', '<=', now());
    }
}
