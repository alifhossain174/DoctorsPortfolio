<?php

namespace Tests\Feature;

use App\Models\CareCard;
use App\Models\ContentField;
use App\Models\GalleryImage;
use App\Models\MediaAsset;
use App\Models\Page;
use App\Models\Post;
use App\Models\SiteSetting;
use App\Models\User;
use App\Services\CmsEditor;
use App\Services\PublicSeo;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\PublicInterfaceContentSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class PublicContentAuditTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        if (DB::connection()->getDatabaseName() !== 'doctor_portfolio_testing') {
            throw new \RuntimeException('Public content audit requires the isolated MySQL test schema.');
        }
        DB::beginTransaction();
        $this->beforeApplicationDestroyed(fn () => DB::rollBack());
        $this->seed(DatabaseSeeder::class);
        $admin = User::create(['name' => 'Disposable content editor', 'email' => Str::uuid().'@example.test', 'password' => bin2hex(random_bytes(24))]);
        $admin->forceFill(['is_admin' => true])->save();
        $this->actingAs($admin);
        config(['app.url' => 'https://clinic.example.test']);
    }

    private function xpath(string $html): \DOMXPath
    {
        $document = new \DOMDocument;
        @$document->loadHTML('<?xml encoding="UTF-8">'.$html);

        return new \DOMXPath($document);
    }

    private function content(string $html, string $query, string $attribute = 'content'): string
    {
        return $this->xpath($html)->query($query)->item(0)?->getAttribute($attribute) ?? '';
    }

    public function test_all_eight_page_types_render_admin_authored_localized_metadata_and_escape_values(): void
    {
        $image = MediaAsset::where('key', 'demo-photo-lab')->firstOrFail();
        foreach (Page::all() as $page) {
            if ($page->key === 'article') {
                $this->put('/admin/articles/'.Post::where('slug', 'pcos')->value('id'), ['post' => ['meta_title' => ['en' => '', 'bn' => '']]])->assertSessionHasNoErrors();
            }
            $this->put('/admin/pages/'.$page->key, ['page' => [
                'meta_title' => ['en' => 'EN {page_label} " <script>plain</script>', 'bn' => 'বাংলা {page_label} " <script>plain</script>'],
                'meta_description' => ['en' => 'English stored description & "', 'bn' => 'বাংলা সংরক্ষিত বিবরণ & "'],
                'og_title' => ['en' => 'Social {page_label}', 'bn' => 'সামাজিক {page_label}'],
                'og_description' => ['en' => 'English social description', 'bn' => 'বাংলা সামাজিক বিবরণ'],
                'og_media_asset_id' => $image->id,
            ]])->assertSessionHasNoErrors();
            foreach (['en', 'bn'] as $locale) {
                $path = $page->key === 'home' ? '/' : ($page->key === 'article' ? '/blog/pcos' : '/'.$page->key);
                $html = $this->get($path.'?locale='.$locale)->assertOk()->assertSee('lang="'.$locale.'"', false)->assertDontSee('<script>plain</script>', false)->getContent();
                $label = $page->key === 'article' ? Post::where('slug', 'pcos')->firstOrFail()->localized('title', $locale) : $page->localized('label', $locale);
                $this->assertSame(($locale === 'en' ? 'EN ' : 'বাংলা ').$label.' " <script>plain</script>', $this->xpath($html)->query('//title')->item(0)->textContent);
                $this->assertSame(($locale === 'en' ? 'Social ' : 'সামাজিক ').$label, $this->content($html, '//meta[@property="og:title"]'));
                $this->assertSame($image->url(), $this->content($html, '//meta[@property="og:image"]'));
                $this->assertSame($image->localized('alt_text', $locale), $this->content($html, '//meta[@property="og:image:alt"]'));
                $this->assertCount(1, $this->xpath($html)->query('//link[@rel="canonical"]'));
                $this->assertCount(2, $this->xpath($html)->query('//link[@rel="alternate"][@hreflang]'));
            }
        }
    }

    public function test_canonical_and_alternate_urls_preserve_language_valid_filters_pages_and_article_identity(): void
    {
        $cases = [
            '/?locale=bn&tracking=x' => '/?locale=bn',
            '/contact?locale=bn&kind=contact&redirect=https://bad.example' => '/contact?locale=bn',
            '/blog?category=clinical&page=2&locale=bn&tracking=x' => '/blog?category=clinical&page=2&locale=bn',
            '/gallery?category=all&page=1&locale=en' => '/gallery?locale=en',
            '/gallery?category=facility&page=2&locale=bn' => '/gallery?category=facility&page=2&locale=bn',
            '/blog/pcos?locale=bn&category=clinical&page=2' => '/blog/pcos?locale=bn',
        ];
        foreach ($cases as $request => $expected) {
            $html = $this->get($request)->assertOk()->getContent();
            $this->assertSame('https://clinic.example.test'.$expected, $this->content($html, '//link[@rel="canonical"]', 'href'));
            $this->assertSame('https://clinic.example.test'.$expected, $this->content($html, '//meta[@property="og:url"]'));
            foreach (['en', 'bn'] as $locale) {
                $alternate = preg_replace('/locale=(?:en|bn)/', 'locale='.$locale, $expected);
                $this->assertSame('https://clinic.example.test'.$alternate, $this->content($html, '//link[@hreflang="'.$locale.'"]', 'href'));
            }
        }
        $this->withSession(['public_locale' => 'bn'])->get('/about?locale[]=evil')->assertSee('https://clinic.example.test/about?locale=bn');
        $this->get('/blog?category=facility&locale=bn')->assertNotFound();
        config(['app.url' => 'https://clinic.example.test/subdirectory']);
        $this->assertSame('https://clinic.example.test/subdirectory/blog/pcos?locale=bn', app(PublicSeo::class)->url('article', 'bn', ['post' => 'pcos']));
        $mounted = Request::create('https://untrusted-host.example/subdirectory/about', 'GET', [], [], [], ['SCRIPT_FILENAME' => '/web/public/index.php', 'SCRIPT_NAME' => '/subdirectory/index.php', 'PHP_SELF' => '/subdirectory/index.php']);
        $this->assertSame('/subdirectory', $mounted->getBaseUrl());
        $this->app->instance('request', $mounted);
        $this->app['url']->setRequest($mounted);
        $this->assertSame('https://clinic.example.test/subdirectory/?locale=bn', app(PublicSeo::class)->url('home', 'bn'));
        $this->assertSame('https://clinic.example.test/subdirectory/blog/pcos?locale=en', app(PublicSeo::class)->url('article', 'en', ['post' => 'pcos']));
    }

    public function test_article_metadata_fallback_is_actual_article_and_blank_bangla_uses_english(): void
    {
        $post = Post::where('slug', 'pcos')->firstOrFail();
        $this->put('/admin/articles/'.$post->id, ['post' => ['title' => ['en' => 'Stored article identity', 'bn' => 'সংরক্ষিত নিবন্ধ পরিচয়'], 'excerpt' => ['en' => 'Actual excerpt', 'bn' => 'প্রকৃত সারাংশ'], 'meta_title' => ['en' => 'Explicit article SEO', 'bn' => ''], 'meta_description' => ['en' => 'Explicit description', 'bn' => '']]])->assertSessionHasNoErrors();
        $html = $this->get('/blog/pcos?locale=bn')->assertOk()->getContent();
        $this->assertSame('Explicit article SEO', $this->xpath($html)->query('//title')->item(0)->textContent);
        $this->assertSame('Explicit description', $this->content($html, '//meta[@name="description"]'));
        $this->assertSame('Explicit article SEO', $this->content($html, '//meta[@property="og:title"]'));
        $this->put('/admin/articles/'.$post->id, ['post' => ['meta_title' => ['en' => '', 'bn' => ''], 'meta_description' => ['en' => '', 'bn' => '']]])->assertSessionHasNoErrors();
        $html = $this->get('/blog/pcos?locale=bn')->assertOk()->getContent();
        $this->assertStringContainsString('সংরক্ষিত নিবন্ধ পরিচয়', $this->xpath($html)->query('//title')->item(0)->textContent);
        $this->assertSame('প্রকৃত সারাংশ', $this->content($html, '//meta[@name="description"]'));
        $this->assertSame($post->mediaAsset->url(), $this->content($html, '//meta[@property="og:image"]'));
    }

    public function test_sitemap_is_public_only_with_both_languages_and_revalidates_database_edits(): void
    {
        $response = $this->get('/sitemap.xml')->assertOk()->assertHeader('Content-Type', 'application/xml; charset=UTF-8');
        $response->assertHeader('Cache-Control', 'max-age=0, must-revalidate, public');
        $document = new \DOMDocument;
        $this->assertTrue($document->loadXML($response->getContent()));
        $xpath = new \DOMXPath($document);
        $xpath->registerNamespace('s', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $xpath->registerNamespace('x', 'http://www.w3.org/1999/xhtml');
        $this->assertCount(22, $xpath->query('//s:url'));
        $this->assertCount(44, $xpath->query('//x:link'));
        foreach ($xpath->query('//s:loc') as $node) {
            $this->assertMatchesRegularExpression('~^https://clinic\.example\.test/(?:\?locale=(?:en|bn)|(?:about|services|diagnostics|blog|gallery|contact|blog/[a-z-]+)\?locale=(?:en|bn))$~', $node->textContent);
        }
        $etag = $response->headers->get('ETag');
        $this->withHeaders(['If-None-Match' => $etag])->get('/sitemap.xml')->assertStatus(304);
        $this->flushHeaders();
        $this->put('/admin/articles/'.Post::where('slug', 'pcos')->value('id'), ['post' => ['status' => 'draft']])->assertSessionHasNoErrors();
        Post::where('slug', 'menopause')->firstOrFail()->update(['published_at' => now()->addDay()]);
        $this->withHeaders(['If-None-Match' => $etag])->get('/sitemap.xml')->assertOk()->assertDontSee('/blog/pcos?')->assertDontSee('/blog/menopause?')->assertDontSee('/admin')->assertDontSee('/inquiries')->assertDontSee('.html');
        $this->flushHeaders();
        $this->get('/robots.txt')->assertOk()->assertSee('Disallow: /admin')->assertSee('Disallow: /inquiries')->assertSee('Sitemap: https://clinic.example.test/sitemap.xml')->assertDontSee('locale=');
        $this->assertFileDoesNotExist(public_path('robots.txt'));
    }

    public function test_preview_and_public_missing_empty_validation_states_keep_private_or_database_copy(): void
    {
        $post = Post::where('slug', 'pcos')->firstOrFail();
        $this->put('/admin/articles/'.$post->id, ['post' => ['status' => 'draft']])->assertSessionHasNoErrors();
        $this->get('/admin/articles/'.$post->id.'/preview?locale=bn')->assertOk()->assertHeader('X-Robots-Tag', 'noindex, nofollow, nosnippet')->assertHeader('Cache-Control', 'max-age=0, no-store, private')->assertDontSee('rel="canonical"', false)->assertDontSee('hreflang=', false);
        $title = ContentField::where('key', 'ui.not_found_title')->firstOrFail();
        $this->put('/admin/settings/ui', ['fields' => [$title->id => ['value' => ['en' => 'Edited missing page', 'bn' => 'সম্পাদিত অনুপস্থিত পৃষ্ঠা']]]])->assertSessionHasNoErrors();
        $this->get('/blog/pcos?locale=bn')->assertNotFound()->assertSee('সম্পাদিত অনুপস্থিত পৃষ্ঠা')->assertSee('assets/style.css')->assertDontSee('assets/admin-')->assertHeader('X-Robots-Tag', 'noindex, follow');
        $this->get('/does-not-exist?locale=en')->assertNotFound()->assertSee('Edited missing page');
        $this->get('/does-not-exist?locale=bn')->assertNotFound()->assertSee('সম্পাদিত অনুপস্থিত পৃষ্ঠা');
        $this->get('/another-missing-page')->assertNotFound()->assertSee('lang="bn"', false);
        $this->app['auth']->forgetGuards();
        $this->get('/admin/articles/'.$post->id.'/preview')->assertRedirect('/admin/login');
    }

    public function test_every_regular_named_text_control_changes_actual_public_output_via_validated_admin_input(): void
    {
        $editor = app(CmsEditor::class);
        $groups = [];
        foreach ($editor->groups() as $group => $definition) {
            foreach ($definition['fields'] as $key) {
                $groups[$key] = $group;
            }
        }
        $special = ['blog.blogdesc', 'contact.mode_apply', 'contact.validation_required', 'contact.validation_invalid', 'contact.validation_max', 'contact.success_appointment', 'contact.success_contact', 'contact.save_failure', 'contact.submission_expired', 'contact.rate_limited', 'contact.submission_rejected', 'contact.reference_label'];
        $overrides = ['shared.qualification' => '/about', 'shared.hoursTitle' => '/about', 'shared.visitTitle' => '/about', 'shared.visitText' => '/about', 'shared.gallerytitle' => '/gallery', 'shared.gallerydesc' => '/gallery', 'shared.department_format' => '/', 'shared.profession' => '/', 'ui.filter_all' => '/blog'];
        Page::query()->update(['meta_title' => null]);
        foreach (config('content_fields') as $key => $definition) {
            if (! $definition['localized'] || $definition['type'] === 'target' || in_array($key, $special, true)) {
                continue;
            }
            $field = ContentField::where('key', $key)->firstOrFail();
            $original = $field->value;
            $values = ['en' => 'CMS audit '.str_replace('.', ' ', $key), 'bn' => 'সিএমএস যাচাই '.str_replace('.', ' ', $key)];
            if ($key === 'footer.copyright_format') {
                $values = array_map(fn ($value) => $value.' {year}', $values);
            }
            $destination = isset($groups[$key]) ? '/admin/settings/'.$groups[$key] : '/admin/pages/'.explode('.', $key)[0];
            $this->put($destination, ['fields' => [$field->id => ['value' => $values]]])->assertSessionHasNoErrors();
            $this->assertSame($values, $field->fresh()->value, $key);
            $prefix = explode('.', $key)[0];
            $path = $overrides[$key] ?? (in_array($prefix, ['home', 'ui', 'footer', 'meta'], true) ? '/' : '/'.$prefix);
            if (str_starts_with($key, 'ui.not_found_')) {
                $path = '/audit-missing-page';
            }
            if ($key === 'ui.empty_filter_results') {
                CareCard::query()->update(['is_enabled' => false]);
            }
            if ($key === 'shared.department_format') {
                $this->put('/admin/pages/home', ['page' => ['meta_title' => ['en' => '{department_full}', 'bn' => '{department_full}']]])->assertSessionHasNoErrors();
            }
            foreach (['en', 'bn'] as $locale) {
                if (($key === 'ui.switch_to_bn' && $locale === 'bn') || ($key === 'ui.switch_to_en' && $locale === 'en')) {
                    continue;
                }
                $response = $this->get($path.'?locale='.$locale);
                $response->assertStatus(str_starts_with($key, 'ui.not_found_') ? 404 : 200)->assertSee(str_replace('{year}', now()->format('Y'), $values[$locale]), true);
            }
            $this->put($destination, ['fields' => [$field->id => ['value' => $original]]])->assertSessionHasNoErrors();
            if ($key === 'ui.empty_filter_results') {
                CareCard::query()->update(['is_enabled' => true]);
            }
            if ($key === 'shared.department_format') {
                $this->put('/admin/pages/home', ['page' => ['meta_title' => ['en' => '', 'bn' => '']]])->assertSessionHasNoErrors();
            }
        }
    }

    public function test_media_reference_replacement_reaches_public_card_lightbox_and_og_without_changing_provenance(): void
    {
        $image = GalleryImage::orderBy('sort_order')->firstOrFail();
        $source = $image->mediaAsset;
        $target = MediaAsset::where('id', '!=', $source->id)->whereHas('posts')->firstOrFail();
        $this->put('/admin/pages/home', ['page' => ['og_media_asset_id' => $source->id]])->assertSessionHasNoErrors();
        $this->put('/admin/media/'.$source->id.'/references', ['replacement_id' => $target->id, 'confirm_replace' => '1'])->assertSessionHasNoErrors();
        $this->assertSame($target->id, $image->fresh()->media_asset_id);
        $html = $this->get('/gallery?locale=bn')->assertOk()->getContent();
        $this->assertStringContainsString('data-url="'.e($target->url()).'"', $html);
        $this->get('/?locale=bn')->assertSee('data-url="'.e($target->url()).'"', false)->assertSee('content="'.e($target->url()).'"', false)->assertSee(SiteSetting::firstOrFail()->stock_notice['bn']);
        $this->assertSame(9, MediaAsset::count());
        $this->assertSame(0, MediaAsset::whereNotNull('path')->count());
    }

    public function test_new_initialization_skips_owner_edits_and_intentional_deletions(): void
    {
        $title = ContentField::where('key', 'ui.not_found_title')->firstOrFail();
        $this->put('/admin/settings/ui', ['fields' => [$title->id => ['value' => ['en' => 'Owner error title']]]])->assertSessionHasNoErrors();
        ContentField::where('key', 'ui.not_found_description')->delete();
        $this->seed(PublicInterfaceContentSeeder::class);
        $this->assertSame('Owner error title', $title->fresh()->value['en']);
        $this->assertSame('পৃষ্ঠাটি পাওয়া যায়নি', $title->fresh()->value['bn']);
        $this->assertFalse(ContentField::where('key', 'ui.not_found_description')->exists());
        $this->get('/missing?locale=en')->assertNotFound()->assertSee('Owner error title')->assertDontSee('The page may have moved');
    }
}
