<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** Historical route identity only; no content, arbitrary URL or redirect chain. */
class PostSlugRedirect extends Model
{
    protected $fillable = ['slug', 'post_id'];

    protected function casts(): array
    {
        return ['post_id' => 'integer'];
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
