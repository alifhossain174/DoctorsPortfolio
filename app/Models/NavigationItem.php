<?php

namespace App\Models;

class NavigationItem extends CmsModel
{
    protected function casts(): array
    {
        return ['label' => 'array', 'target' => 'array', 'sort_order' => 'integer', 'is_visible' => 'boolean'];
    }
}
