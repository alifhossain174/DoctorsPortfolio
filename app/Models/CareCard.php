<?php

namespace App\Models;

class CareCard extends CmsModel
{
    protected function casts(): array
    {
        return ['title' => 'array', 'description' => 'array', 'icon' => 'array', 'link_label' => 'array', 'target' => 'array', 'sort_order' => 'integer', 'is_enabled' => 'boolean'];
    }
}
