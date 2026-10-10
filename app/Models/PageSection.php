<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageSection extends CmsModel
{
    protected function casts(): array
    {
        return ['page_id' => 'integer', 'sort_order' => 'integer', 'is_visible' => 'boolean'];
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
}
