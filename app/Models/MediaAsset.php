<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class MediaAsset extends CmsModel
{
    protected function casts(): array
    {
        return ['title' => 'array', 'alt_text' => 'array', 'provenance_note' => 'array', 'stock_note' => 'array', 'size' => 'integer', 'width' => 'integer', 'height' => 'integer'];
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function galleryImages(): HasMany
    {
        return $this->hasMany(GalleryImage::class);
    }

    public function contentFields(): HasMany
    {
        return $this->hasMany(ContentField::class);
    }

    public function settings(): HasMany
    {
        return $this->hasMany(SiteSetting::class, 'portrait_media_asset_id');
    }

    public function url(): string
    {
        return $this->path ? Storage::disk($this->disk)->url($this->path) : ($this->source_url ?? '');
    }
}
