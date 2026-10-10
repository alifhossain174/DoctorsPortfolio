<?php

namespace App\Services;

use App\Models\Page;
use App\Models\Post;
use App\Rules\PublicTarget;
use Illuminate\Http\Request;

/** Request-time metadata from the same database owners as the visible page. */
final class PublicSeo
{
    public function url(string $route, string $locale, array $parameters = []): string
    {
        $base = rtrim((string) config('app.url'), '/');
        if (! PublicTarget::safeExternal($base) || parse_url($base, PHP_URL_QUERY) !== null || parse_url($base, PHP_URL_FRAGMENT) !== null) {
            throw new \RuntimeException('Configure APP_URL as the absolute public application root.');
        }
        $path = route($route, [...$parameters, 'locale' => $locale], false);
        $requestBase = request()->getBaseUrl();
        if ($requestBase !== '' && ($path === $requestBase || str_starts_with($path, $requestBase.'/') || str_starts_with($path, $requestBase.'?'))) {
            $path = substr($path, strlen($requestBase));
        }

        return $base.'/'.ltrim($path, '/');
    }

    public function forPage(Request $request, WebsiteContent $site, Page $page, ?Post $post, array $query, bool $preview): array
    {
        $label = $post?->localized('title') ?? $page->localized('label');
        $format = fn ($value) => $site->format($value, ['page_label' => $label]);
        $title = $format($post?->localized('meta_title') ?: ($page->localized('meta_title') ?: $site->text('meta.title_format', ['page_label' => $label])));
        $description = $format($post?->localized('meta_description') ?: ($post?->localized('excerpt') ?: ($page->localized('meta_description') ?: $site->setting('doctor_biography'))));
        $ogTitle = $format($page->localized('og_title') ?: $title);
        $ogDescription = $format($page->localized('og_description') ?: $description);
        $media = $post?->ogImage ?? $page->ogImage ?? $post?->mediaAsset ?? $site->settings?->portrait;
        $image = $media?->url() ?? '';
        $parameters = $post ? ['post' => $post->slug] : [];
        if (in_array($page->key, ['blog', 'gallery'], true)) {
            if (($query['category'] ?? 'all') !== 'all') {
                $parameters['category'] = $query['category'];
            }
            if (($query['page'] ?? 1) > 1) {
                $parameters['page'] = $query['page'];
            }
        }
        $alternates = [];
        if (! $preview) {
            foreach (['en', 'bn'] as $locale) {
                $alternates[$locale] = $this->url($post ? 'article' : $page->key, $locale, $parameters);
            }
        }

        return ['title' => $title, 'description' => $description, 'og_title' => $ogTitle, 'og_description' => $ogDescription, 'image' => $image, 'image_alt' => $media?->localized('alt_text') ?? '',
            'site_name' => $site->setting('doctor_name'), 'type' => $post ? 'article' : 'website', 'locale' => app()->getLocale() === 'bn' ? 'bn_BD' : 'en_GB',
            'canonical' => $alternates[app()->getLocale()] ?? '', 'alternates' => $alternates, 'robots' => $preview ? 'noindex, nofollow, nosnippet' : null];
    }
}
