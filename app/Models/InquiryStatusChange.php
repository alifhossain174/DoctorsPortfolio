<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InquiryStatusChange extends Model
{
    public $timestamps = false;

    protected $fillable = ['inquiry_id', 'from_status', 'to_status', 'actor_id', 'created_at'];

    protected $hidden = ['inquiry_id', 'actor_id'];

    protected function casts(): array
    {
        return ['inquiry_id' => 'integer', 'actor_id' => 'integer', 'created_at' => 'immutable_datetime'];
    }

    public function inquiry(): BelongsTo
    {
        return $this->belongsTo(Inquiry::class);
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }
}
