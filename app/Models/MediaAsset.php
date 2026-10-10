<?php

namespace App\Models;

use App\Rules\PublicTarget;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class MediaAsset extends CmsModel
{
    protected function casts(): array
    {
        return ['title' => 'array', 'alt_text' => 'array', 'caption' => 'array', 'provenance_note' => 'array', 'stock_note' => 'array', 'size' => 'integer', 'width' => 'integer', 'height' => 'integer'];
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

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class, 'og_media_asset_id');
    }

    public function ogPosts(): HasMany
    {
        return $this->hasMany(Post::class, 'og_media_asset_id');
    }

    public function url(): string
    {
        if ($this->path) {
            // Public raster uploads only; never emit arbitrary disks, SVG or traversal paths.
            if ($this->disk !== 'public-media' || ! self::safePath($this->path)) {
                return '';
            }

            $url = Storage::disk('public-media')->url($this->path);

            return PublicTarget::safeExternal($url) ? $url : '';
        }

        return PublicTarget::safeExternal($this->source_url) ? $this->source_url : '';
    }

    public static function safePath(string $path): bool
    {
        return (bool) preg_match('~^images/[0-9]{4}/(?:0[1-9]|1[0-2])/[a-f0-9]{32}\.(?:jpg|png|webp)$~D', $path);
    }

    public function inUse(): bool
    {
        return $this->posts()->exists() || $this->galleryImages()->exists() || $this->contentFields()->exists() || $this->settings()->exists() || $this->pages()->exists() || $this->ogPosts()->exists();
    }
}
