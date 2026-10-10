<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Services\PublicUrl;
use Illuminate\Http\Request;

final class LegacyPageController extends Controller
{
    public function page(Request $request, PublicUrl $urls, string $page)
    {
        $categories = in_array($page, ['blog', 'gallery'], true)
            ? Category::where($page === 'gallery' ? 'for_gallery' : 'for_blog', true)->pluck('slug')->all() : [];

        return redirect($urls->route($page, $urls->query($request, $page, $categories)), 301);
    }

    public function article(Request $request, PublicUrl $urls)
    {
        $slug = $request->query('id');
        abort_unless(is_string($slug) && preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/D', $slug), 404);
        $post = Post::published()->where('slug', $slug)->firstOrFail();

        return redirect($urls->route('article', ['post' => $post->slug]), 301);
    }
}
