<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends CmsModel
{
    protected function casts(): array
    {
        return ['label' => 'array', 'sort_order' => 'integer', 'for_blog' => 'boolean', 'for_gallery' => 'boolean'];
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function galleryImages(): HasMany
    {
        return $this->hasMany(GalleryImage::class);
    }
}
