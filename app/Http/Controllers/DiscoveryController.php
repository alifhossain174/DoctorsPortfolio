<?php

namespace App\Http\Controllers;

use App\Models\CareCard;
use App\Models\Category;
use App\Models\ConsultationOption;
use App\Models\ContentField;
use App\Models\GalleryImage;
use App\Models\MediaAsset;
use App\Models\NavigationItem;
use App\Models\Page;
use App\Models\PageSection;
use App\Models\Post;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Rules\PublicTarget;
use App\Services\PublicSeo;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;

final class DiscoveryController extends Controller
{
    public function robots(PublicSeo $seo)
    {
        $base = $seo->url('home', 'en');
        $root = rtrim(parse_url($base, PHP_URL_PATH) ?? '', '/');
        $lines = ['User-agent: *', 'Disallow: '.$root.'/admin', 'Disallow: '.$root.'/inquiries', 'Disallow: '.$root.'/up', 'Sitemap: '.strtok($seo->url('sitemap', 'en'), '?')];

        return response(implode("\n", $lines)."\n", 200, ['Content-Type' => 'text/plain; charset=UTF-8', 'Cache-Control' => 'public, max-age=0, must-revalidate']);
    }

    public function sitemap(Request $request, PublicSeo $seo)
    {
        // No patient data, source reads, scheduler or persistent content cache.
        $shared = [];
        foreach ([SiteSetting::class, ContentField::class, PageSection::class, NavigationItem::class, CareCard::class, Service::class, ConsultationOption::class, Category::class, GalleryImage::class, MediaAsset::class] as $model) {
            $shared[] = $model::max('updated_at');
        }
        $shared[] = Post::published()->max('updated_at');
        $shared = array_filter($shared);
        $shared = $shared ? max($shared) : null;
        $entries = [];
        foreach (Page::whereIn('key', PublicTarget::ROUTES)->orderBy('id')->get() as $page) {
            $entries[] = ['route' => $page->key, 'parameters' => [], 'lastmod' => $this->lastmod([$shared, $page->updated_at])];
        }
        $template = Page::where('key', 'article')->value('updated_at');
        foreach (Post::published()->orderBy('sort_order')->orderBy('id')->get(['slug', 'updated_at', 'published_at']) as $post) {
            $entries[] = ['route' => 'article', 'parameters' => ['post' => $post->slug], 'lastmod' => $this->lastmod([$shared, $template, $post->updated_at, $post->published_at])];
        }
        $xml = new \XMLWriter;
        $xml->openMemory();
        $xml->startDocument('1.0', 'UTF-8');
        $xml->startElementNS(null, 'urlset', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $xml->writeAttribute('xmlns:xhtml', 'http://www.w3.org/1999/xhtml');
        foreach ($entries as $entry) {
            foreach (['en', 'bn'] as $locale) {
                $xml->startElement('url');
                $xml->writeElement('loc', $seo->url($entry['route'], $locale, $entry['parameters']));
                if ($entry['lastmod']) {
                    $xml->writeElement('lastmod', $entry['lastmod']);
                }
                foreach (['en', 'bn'] as $alternate) {
                    $xml->startElement('xhtml:link');
                    $xml->writeAttribute('rel', 'alternate');
                    $xml->writeAttribute('hreflang', $alternate);
                    $xml->writeAttribute('href', $seo->url($entry['route'], $alternate, $entry['parameters']));
                    $xml->endElement();
                }
                $xml->endElement();
            }
        }
        $xml->endElement();
        $xml->endDocument();
        $response = response($xml->outputMemory(), 200, ['Content-Type' => 'application/xml; charset=UTF-8', 'Cache-Control' => 'public, max-age=0, must-revalidate']);
        $response->setEtag(hash('sha256', $response->getContent()));
        $response->isNotModified($request);

        return $response;
    }

    private function lastmod(array $values): ?string
    {
        $dates = array_map(fn ($value) => CarbonImmutable::parse($value), array_filter($values));

        return $dates ? max($dates)->min(now())->toAtomString() : null;
    }
}
