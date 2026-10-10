<?php

namespace App\Http\Controllers;

use App\Models\CareCard;
use App\Models\Category;
use App\Models\ConsultationOption;
use App\Models\GalleryImage;
use App\Models\NavigationItem;
use App\Models\Page;
use App\Models\Post;
use App\Models\Service;
use App\Services\WebsiteContent;
use Illuminate\Http\Request;

class PublicPageController extends Controller
{
    public function article(Request $request, WebsiteContent $site, string $slug)
    {
        return $this->show($request, $site, 'article', $slug);
    }

    public function show(Request $request, WebsiteContent $site, string $page = 'home', ?string $slug = null)
    {
        $post = $slug === null ? null : Post::published()->with(['category', 'mediaAsset'])->where('slug', $slug)->firstOrFail();
        if ($post) {
            $page = 'article';
        }
        $pageRecord = Page::with('sections')->where('key', $page)->firstOrFail();
        $posts = Post::published()->with(['category', 'mediaAsset'])->orderBy('sort_order')->orderBy('id');
        $images = GalleryImage::visible()->with(['category', 'mediaAsset'])->orderBy('sort_order')->orderBy('id');
        if ($page === 'home') {
            $posts->where('on_homepage', true)->limit($site->count('home.article_count'));
            $images->where('on_homepage', true)->limit($site->count('home.gallery_count'));
        }

        return view('public.page', [
            'site' => $site, 'page' => $page, 'pageRecord' => $pageRecord, 'post' => $post,
            'navigation' => NavigationItem::where('is_visible', true)->orderBy('sort_order')->get()->groupBy('placement'),
            'cards' => CareCard::where('is_enabled', true)->orderBy('sort_order')->get(),
            'services' => Service::where('is_enabled', true)->orderBy('sort_order')->get(),
            'posts' => $posts->get(), 'images' => $images->get(),
            'categories' => Category::where($page === 'gallery' ? 'for_gallery' : 'for_blog', true)->orderBy('sort_order')->get(),
            'options' => ConsultationOption::where('is_active', true)->orderBy('sort_order')->get(),
            'mode' => $request->query('mode') === 'contact' ? 'contact' : 'appointment',
            'filter' => is_string($request->query('category')) ? $request->query('category') : 'all',
        ]);
    }
}
