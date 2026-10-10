<?php

namespace App\Models;

use App\Services\LocalizedContent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends CmsModel
{
    protected function casts(): array
    {
        return ['title' => 'array', 'excerpt' => 'array', 'body' => 'array', 'category_id' => 'integer', 'media_asset_id' => 'integer', 'editorial_badge' => 'array', 'image_alt' => 'array', 'sort_order' => 'integer', 'on_homepage' => 'boolean', 'published_at' => 'immutable_datetime', 'meta_title' => 'array', 'meta_description' => 'array', 'og_media_asset_id' => 'integer'];
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

    public function ogImage(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'og_media_asset_id');
    }

    public function slugRedirects(): HasMany
    {
        return $this->hasMany(PostSlugRedirect::class);
    }

    public function missingBanglaFields(): array
    {
        $localizer = app(LocalizedContent::class);
        $missing = [];
        foreach (['title', 'excerpt', 'body', 'editorial_badge', 'image_alt', 'meta_title', 'meta_description'] as $field) {
            $value = $this->getAttribute($field) ?? [];
            if ($localizer->resolve(['en' => $value['en'] ?? null], 'en', null) !== null && $localizer->resolve(['bn' => $value['bn'] ?? null], 'bn', null) === null) {
                $missing[] = $field;
            }
        }

        return $missing;
    }
}
