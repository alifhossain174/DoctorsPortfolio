<?php

namespace App\Services;

use App\Models\Post;
use App\Models\PostSlugRedirect;

final class PublishedArticle
{
    public function find(string $slug): Post
    {
        abort_unless(preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/D', $slug) && strlen($slug) <= 150, 404);
        $post = Post::published()->with(['category', 'mediaAsset', 'ogImage'])->where('slug', $slug)->first();
        if ($post) {
            return $post;
        }
        $redirect = PostSlugRedirect::where('slug', $slug)->whereHas('post', fn ($query) => $query->published())->with(['post.category', 'post.mediaAsset', 'post.ogImage'])->firstOrFail();

        return $redirect->post;
    }
}
