<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class ConsultationOption extends CmsModel
{
    protected function casts(): array
    {
        return ['label' => 'array', 'sort_order' => 'integer', 'is_active' => 'boolean'];
    }

    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class);
    }
}
