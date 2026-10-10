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
use App\Services\PublicUrl;
use App\Services\PublishedArticle;
use App\Services\WebsiteContent;
use Illuminate\Http\Request;

class PublicPageController extends Controller
{
    public function article(Request $request, WebsiteContent $site, string $post)
    {
        $record = app(PublishedArticle::class)->find($post);
        if ($record->slug !== $post) {
            return redirect(app(PublicUrl::class)->route('article', ['post' => $record->slug]), 301);
        }

        return $this->show($request, $site, 'article', $record);
    }

    public function show(Request $request, WebsiteContent $site, string $page = 'home', ?Post $post = null, bool $preview = false)
    {
        $pageRecord = Page::with('sections')->where('key', $page)->firstOrFail();
        $categories = in_array($page, ['blog', 'gallery'], true)
            ? Category::where($page === 'gallery' ? 'for_gallery' : 'for_blog', true)->orderBy('sort_order')->orderBy('id')->get() : collect();
        $query = app(PublicUrl::class)->query($request, $page, $categories->pluck('slug')->all());
        $filter = $query['category'] ?? 'all';
        $posts = collect();
        $images = collect();
        if (in_array($page, ['home', 'blog'], true)) {
            $builder = Post::published()->with(['category', 'mediaAsset'])->orderBy('sort_order')->orderBy('id');
            if ($page === 'home') {
                $builder->where('on_homepage', true)->limit($site->count('home.article_count'));
            } elseif ($filter !== 'all') {
                $builder->whereHas('category', fn ($category) => $category->where('slug', $filter));
            }
            $posts = $page === 'blog'
                ? $builder->paginate(config('editorial.blog_per_page'), ['*'], 'page', $query['page'] ?? 1)->appends(['locale' => app()->getLocale(), ...array_intersect_key($query, ['category' => true])])
                : $builder->get();
        }
        if (in_array($page, ['home', 'gallery'], true)) {
            $builder = GalleryImage::visible()->with(['category', 'mediaAsset'])->orderBy('sort_order')->orderBy('id');
            if ($page === 'home') {
                $builder->where('on_homepage', true)->limit($site->count('home.gallery_count'));
            } elseif ($filter !== 'all') {
                $builder->whereHas('category', fn ($category) => $category->where('slug', $filter));
            }
            $images = $builder->get();
        }
        $sections = $pageRecord->sections->keyBy('key');

        return view('public.page', [
            'site' => $site, 'urls' => app(PublicUrl::class), 'page' => $page, 'pageRecord' => $pageRecord, 'post' => $post,
            'preview' => $preview,
            'label' => $pageRecord->localized('label'), 'locale' => app()->getLocale(),
            'metaTitle' => $post?->localized('meta_title') ?: ($post ? $site->text('meta.title_format', ['page_label' => $post->localized('title')]) : ($pageRecord->localized('meta_title') ?: $site->text('meta.title_format', ['page_label' => $pageRecord->localized('label')]))),
            'metaDescription' => $post?->localized('meta_description') ?: ($pageRecord->localized('meta_description') ?: ($post?->localized('excerpt') ?: $site->setting('doctor_biography'))),
            'visible' => fn ($key) => (bool) ($sections->get($page.'.'.$key)?->is_visible ?? false),
            'navigation' => NavigationItem::orderBy('sort_order')->orderBy('id')->get()->groupBy('placement'),
            'cards' => $page === 'home' ? CareCard::where('is_enabled', true)->orderBy('sort_order')->orderBy('id')->get() : collect(),
            'services' => $page === 'services' ? Service::where('is_enabled', true)->orderBy('sort_order')->orderBy('id')->get() : collect(),
            'posts' => $posts, 'images' => $images, 'categories' => $categories,
            'options' => $page === 'contact' ? ConsultationOption::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get() : collect(),
            'mode' => $query['mode'] ?? 'appointment', 'filter' => $filter,
        ]);
    }
}
