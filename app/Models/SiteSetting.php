<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiteSetting extends CmsModel
{
    protected function casts(): array
    {
        return ['doctor_name' => 'array', 'subtitle' => 'array', 'doctor_biography' => 'array', 'credential' => 'array', 'department' => 'array', 'institution' => 'array', 'clinic_name' => 'array', 'address' => 'array', 'consultation_hours' => 'array', 'friday_note' => 'array', 'location_label' => 'array', 'facebook_url' => 'array', 'map_url' => 'array', 'portrait_media_asset_id' => 'integer', 'footer_description' => 'array', 'stock_notice' => 'array', 'medical_disclaimer' => 'array', 'cta_title' => 'array', 'cta_description' => 'array', 'cta_label' => 'array', 'cta_target' => 'array'];
    }

    public function portrait(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'portrait_media_asset_id');
    }
}
