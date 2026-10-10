<?php

use App\Models\GalleryImage;

return [
    'admin_per_page' => 20,
    'public_per_page' => 9,
    // Code-owned definition reuses the existing transactional ordered-collection service.
    'editor' => [
        'model' => GalleryImage::class,
        'owner' => 'GalleryImage',
        'state' => 'is_visible',
        'fields' => ['caption', 'alt_text', 'category_id', 'media_asset_id', 'on_homepage'],
    ],
];
