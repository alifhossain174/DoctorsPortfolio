<?php

namespace App\Models;

class Service extends CmsModel
{
    protected function casts(): array
    {
        return ['title' => 'array', 'body' => 'array', 'sort_order' => 'integer', 'is_enabled' => 'boolean'];
    }
}
