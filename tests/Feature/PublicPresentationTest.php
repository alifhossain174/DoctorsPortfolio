<?php

namespace Tests\Feature;

use App\Models\ContentField;
use App\Models\Inquiry;
use App\Models\MediaAsset;
use App\Models\Post;
use App\Models\SiteSetting;
use App\Services\InitialWebsiteImporter;
use App\Services\WebsiteContent;
use Database\Seeders\InquiryFormContentSeeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ViewErrorBag;
use Tests\TestCase;

class PublicPresentationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        if (DB::connection()->getDatabaseName() !== 'doctor_portfolio_testing') {
            throw new \RuntimeException('Use the dedicated doctor_portfolio_testing database.');
        }
        DB::beginTransaction();
        $this->beforeApplicationDestroyed(fn () => DB::rollBack());
        app(InitialWebsiteImporter::class)->import();
        $this->seed(InquiryFormContentSeeder::class);
    }

    public function test_locale_priority_fallback_same_article_and_whitelisted_queries(): void
    {
        SiteSetting::firstOrFail()->update(['default_locale' => 'bn']);
        $this->get('/about')->assertSee('lang="bn"', false);
        $this->withSession(['public_locale' => 'bn'])->get('/about?locale=en')->assertSee('lang="en"', false);
        $this->get('/services')->assertSee('lang="en"', false);
        $this->withSession(['public_locale' => 'bn'])->get('/about?locale[]=xx')->assertSee('lang="bn"', false);
        $html = $this->get('/blog/pcos?locale=en&category=clinical&page=2&evil=ignored')->assertOk()->getContent();
        $this->assertStringContainsString('/blog/pcos?locale=bn', $html);
        $this->assertStringNotContainsString('evil=', $html);
        $this->assertStringNotContainsString('page=2', $html);
        $this->get('/gallery?locale=en&category=facility&page=2&evil=x')->assertSee('/gallery?category=facility&amp;page=2&amp;locale=bn', false)->assertDontSee('evil=');
        $this->get('/contact?mode=contact&locale=bn')->assertSee('/contact?kind=contact&amp;locale=en', false)->assertSee('name="locale" value="bn"', false);
    }

    public function test_all_legacy_links_redirect_only_to_published_internal_routes(): void
    {
        foreach (['index' => '', 'about' => '/about', 'services' => '/services', 'diagnostics' => '/diagnostics', 'blog' => '/blog', 'gallery' => '/gallery', 'contact' => '/contact'] as $entry => $path) {
            $this->get('/'.$entry.'.html?locale=bn&redirect=https://example.org')->assertStatus(301)->assertRedirect(url($path).'?locale=bn');
        }
        foreach (['pregnancy-care', 'pcos', 'fertility-first-visit', 'menopause'] as $slug) {
            $this->get('/article.html?id='.$slug.'&locale=bn&evil=x')->assertStatus(301)->assertRedirect('/blog/'.$slug.'?locale=bn');
        }
        $this->get('/blog.html?category=facility&locale=en')->assertNotFound();
        $this->get('/gallery.html?category=facility&page=2&locale=en')->assertRedirect('/gallery?locale=en&category=facility&page=2');
        foreach (['', '?id=missing', '?id[]=pcos', '?id=../pcos'] as $suffix) {
            $this->get('/article.html'.$suffix)->assertNotFound();
        }
        Post::where('slug', 'pcos')->firstOrFail()->update(['status' => 'draft']);
        $this->get('/article.html?id=pcos')->assertNotFound();
        $this->get('/blog/pcos')->assertNotFound();
        Post::where('slug', 'menopause')->firstOrFail()->update(['published_at' => now()->addDay()]);
        $this->get('/blog/menopause')->assertNotFound();
    }

    public function test_filters_apply_to_database_results_and_home_previews_keep_source_order(): void
    {
        $home = $this->get('/?locale=en')->assertOk()->getContent();
        $xpath = $this->xpath($home);
        $this->assertCount(4, $xpath->query('//a[contains(@class,"service-card")]'));
        $this->assertCount(3, $xpath->query('//article[contains(@class,"article-card")]'));
        $this->assertCount(3, $xpath->query('//figure[contains(@class,"gallery-item")]'));
        $this->assertStringNotContainsString('/blog/menopause?', $home);
        $this->assertLessThan(strpos($home, '/blog/pcos?'), strpos($home, '/blog/pregnancy-care?'));
        $this->get('/blog?category=clinical&locale=bn')->assertSee(Post::where('slug', 'pcos')->firstOrFail()->title['bn'])->assertDontSee('data-category="wellness"', false);
        $this->assertCount(3, $this->xpath($this->get('/gallery?category=facility')->getContent())->query('//figure[@data-category="facility"]'));
        $this->assertCount(8, $this->xpath($this->get('/gallery?category[]=facility')->getContent())->query('//figure'));
        $this->get('/blog?category=facility')->assertNotFound();
        Post::published()->whereHas('category', fn ($query) => $query->where('slug', 'clinical'))->update(['status' => 'draft']);
        $this->get('/blog?category=clinical&locale=en')->assertSee(app(WebsiteContent::class)->text('ui.empty_filter_results'))->assertDontSee('class="card article-card"', false);
        $this->assertSame(0, Inquiry::count());
    }

    public function test_database_edits_fallback_and_escaping_reach_server_html_without_lazy_queries(): void
    {
        ContentField::where('key', 'home.herotitle')->firstOrFail()->update(['value' => ['en' => '<script>Owner text</script>', 'bn' => '']]);
        $post = Post::where('slug', 'pcos')->firstOrFail();
        $post->update(['body' => ['en' => ['<img src=x onerror=alert(1)>', 'Second paragraph'], 'bn' => null]]);
        // Simulate corrupt stored URL without bypassing validation in any application path.
        DB::table('media_assets')->where('id', $post->media_asset_id)->update(['source_url' => 'javascript:alert(1)']);
        Model::preventLazyLoading();
        try {
            $this->get('/?locale=bn')->assertOk()->assertSee('&lt;script&gt;Owner text&lt;/script&gt;', false)->assertDontSee('<script>Owner text', false);
            $this->get('/blog/pcos?locale=bn')->assertOk()->assertSee('&lt;img src=x onerror=alert(1)&gt;', false)->assertDontSee('src="javascript:', false)->assertSee('<p>Second paragraph</p>', false);
            foreach (['about', 'services', 'diagnostics', 'blog', 'gallery', 'contact'] as $page) {
                $this->get('/'.$page.'?locale=bn')->assertOk();
            }
        } finally {
            Model::preventLazyLoading(false);
        }
        $this->assertSame('', MediaAsset::findOrFail($post->media_asset_id)->url());
    }

    public function test_hidden_locale_is_validated_and_errors_redirect_internally_without_false_success(): void
    {
        $this->withHeader('Referer', 'https://example.org/')->post('/inquiries', ['locale' => 'bn', 'kind' => 'contact', 'name' => ''])->assertRedirect('/contact?locale=bn&kind=contact')->assertSessionHasErrors('name')->assertSessionMissing('inquiry_success');
        $this->post('/inquiries', ['locale' => 'evil', 'kind' => 'contact', 'name' => 'Test', 'phone' => 'Test'])->assertSessionHasErrors('locale')->assertSessionMissing('inquiry_success');
        $this->assertSame(0, Inquiry::count());
        $this->get('/contact?locale=bn&kind=contact')->assertSee('lang="bn"', false)->assertSee('value="contact" selected', false);
    }

    public function test_shared_admin_repeater_keeps_escaped_plain_paragraph_arrays(): void
    {
        view()->share('errors', new ViewErrorBag);
        $html = view('admin.partials.paragraph-repeater', ['body' => ['en' => ['First', '<script>plain text</script>'], 'bn' => ['বাংলা']]])->render();
        $this->assertStringContainsString('name="body[en][]"', $html);
        $this->assertStringContainsString('name="body[bn][]"', $html);
        $this->assertStringContainsString('&lt;script&gt;plain text&lt;/script&gt;', $html);
        $this->assertStringNotContainsString('<script>plain text</script>', $html);
    }

    private function xpath(string $html): \DOMXPath
    {
        $document = new \DOMDocument;
        $before = libxml_use_internal_errors(true);
        $document->loadHTML('<?xml encoding="UTF-8">'.$html);
        libxml_clear_errors();
        libxml_use_internal_errors($before);

        return new \DOMXPath($document);
    }
}
