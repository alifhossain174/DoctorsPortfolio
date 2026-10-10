<?php

namespace Tests\Feature;

use App\Models\ContentField;
use App\Models\MediaAsset;
use App\Models\NavigationItem;
use App\Models\Page;
use App\Models\SiteSetting;
use App\Models\User;
use App\Services\CmsEditor;
use App\Services\ContentRegistry;
use App\Services\InitialWebsiteImporter;
use Database\Seeders\InquiryFormContentSeeder;
use Database\Seeders\PublicInterfaceContentSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class CmsEditorsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        if (DB::connection()->getDatabaseName() !== 'doctor_portfolio_test') {
            throw new \RuntimeException('Use doctor_portfolio_test; CMS verification must not edit owner content.');
        }
        DB::beginTransaction();
        $this->beforeApplicationDestroyed(fn () => DB::rollBack());
        app(InitialWebsiteImporter::class)->import();
        $this->seed(InquiryFormContentSeeder::class);
        $this->seed(PublicInterfaceContentSeeder::class);
        $admin = User::create(['name' => 'Test editor', 'email' => Str::uuid().'@example.test', 'password' => bin2hex(random_bytes(24))]);
        $admin->forceFill(['is_admin' => true])->save();
        $this->actingAs($admin);
    }

    public function test_every_registry_field_has_one_editor_and_all_eight_page_screens_render(): void
    {
        $editor = app(CmsEditor::class);
        $keys = [];
        $settings = [];
        foreach ($editor->groups() as $group => $definition) {
            $response = $this->get('/admin/settings?group='.$group)->assertOk()->assertSee('View page');
            foreach ($definition['settings'] as $field) {
                $settings[] = $field;
                $response->assertSee('name="settings['.$field.']', false);
            }
            foreach ($editor->groupFields($group) as $field) {
                $keys[] = $field->key;
                $response->assertSee('data-cms-key="'.$field->key.'"', false);
            }
        }
        foreach (Page::all() as $page) {
            $response = $this->get('/admin/pages/'.$page->key.'/edit')->assertOk()->assertSee('Shared content sources')->assertSee('name="page[label][en]"', false)->assertSee('name="page[label][bn]"', false);
            foreach ($editor->pageFields($page) as $field) {
                $keys[] = $field->key;
                $response->assertSee('data-cms-key="'.$field->key.'"', false);
            }
            foreach (['meta_title', 'meta_description', 'og_title', 'og_description', 'og_media_asset_id'] as $field) {
                $response->assertSee('name="page['.$field.']', false);
            }
        }
        $this->assertEqualsCanonicalizing(array_keys(config('content_fields')), $keys);
        $this->assertCount(count(array_unique($keys)), $keys);
        $registered = array_keys(array_filter(app(ContentRegistry::class)->fields('SiteSetting'), fn ($field) => $field['editable'] ?? true));
        $this->assertEqualsCanonicalizing($registered, $settings);
        $this->assertCount(count(array_unique($settings)), $settings);
        $this->get('/admin/pages')->assertOk();
        $this->get('/admin/navigation')->assertOk();
        $this->get('/admin/navigation/create')->assertOk()->assertSee('name="item[target][bn][kind]"', false);
        $this->get('/admin/settings?group=unknown')->assertNotFound();
        $this->get('/admin/pages/unknown/edit')->assertNotFound();
    }

    public function test_home_title_translations_fallback_partial_update_and_locale_survive_reload(): void
    {
        $field = ContentField::where('key', 'home.herotitle')->firstOrFail();
        $this->put('/admin/pages/home', ['fields' => [$field->id => ['value' => ['en' => 'Independent English title', 'bn' => 'স্বতন্ত্র বাংলা শিরোনাম']]]])->assertSessionHasNoErrors()->assertRedirect('/admin/pages/home/edit');
        $this->assertSame(['en' => 'Independent English title', 'bn' => 'স্বতন্ত্র বাংলা শিরোনাম'], $field->fresh()->value);
        $this->get('/?locale=en')->assertOk()->assertSee('<h1>Independent English title</h1>', false);
        $this->get('/?locale=bn')->assertOk()->assertSee('<h1>স্বতন্ত্র বাংলা শিরোনাম</h1>', false)->assertSessionHas('public_locale', 'bn');
        $this->get('/')->assertSee('lang="bn"', false)->assertSee('স্বতন্ত্র বাংলা শিরোনাম');
        $this->put('/admin/pages/home', ['fields' => [$field->id => ['value' => ['bn' => '']]]])->assertSessionHasNoErrors();
        $this->assertSame('Independent English title', $field->fresh()->value['en']);
        $this->get('/?locale=bn')->assertSee('<h1>Independent English title</h1>', false);
        $this->put('/admin/pages/home', ['fields' => [$field->id => ['value' => ['bn' => 'পুনরুদ্ধার করা বাংলা শিরোনাম']]]])->assertSessionHasNoErrors();
        $this->put('/admin/pages/home', ['fields' => [$field->id => ['value' => ['en' => 'Changed English only']]]])->assertSessionHasNoErrors();
        $this->assertSame('পুনরুদ্ধার করা বাংলা শিরোনাম', $field->fresh()->value['bn']);
        $this->get('/?locale=en')->assertSee('<h1>Changed English only</h1>', false);
        $this->get('/?locale=bn')->assertSee('<h1>পুনরুদ্ধার করা বাংলা শিরোনাম</h1>', false);
        $this->get('/')->assertSee('lang="bn"', false)->assertSee('পুনরুদ্ধার করা বাংলা শিরোনাম');
    }

    public function test_shared_profile_clinic_cta_and_notices_update_public_uses_immediately(): void
    {
        $biography = "Owner biography line one\nSecond line <script>not executable</script>";
        $bangla = "ডাক্তারের পরিচিতি\nদ্বিতীয় লাইন";
        $this->put('/admin/settings/doctor', ['settings' => ['doctor_biography' => ['en' => $biography, 'bn' => $bangla]]])->assertSessionHasNoErrors();
        $this->assertSame($bangla, SiteSetting::firstOrFail()->doctor_biography['bn']);
        foreach (['/', '/about'] as $path) {
            $this->get($path.'?locale=en')->assertSee(e($biography), false)->assertDontSee('<script>not executable</script>', false);
            $this->get($path.'?locale=bn')->assertSee($bangla);
        }
        $this->put('/admin/settings/clinic', ['settings' => [
            'address' => ['en' => "Edited address\nSecond address line", 'bn' => "পরিবর্তিত ঠিকানা\nদ্বিতীয় লাইন"],
            'consultation_hours' => ['en' => 'Owner-edited hours', 'bn' => 'পরিবর্তিত সময়সূচি'],
        ]])->assertSessionHasNoErrors();
        foreach (['/', '/about', '/contact', '/diagnostics'] as $path) {
            $this->get($path.'?locale=en')->assertSee("Edited address\nSecond address line")->assertSee('Owner-edited hours');
            $this->get($path.'?locale=bn')->assertSee("পরিবর্তিত ঠিকানা\nদ্বিতীয় লাইন")->assertSee('পরিবর্তিত সময়সূচি');
        }
        $this->put('/admin/settings/cta', ['settings' => ['cta_title' => ['en' => 'Updated request CTA', 'bn' => 'পরিবর্তিত অনুরোধ'], 'cta_label' => ['en' => 'Request follow-up', 'bn' => 'যোগাযোগের অনুরোধ']]])->assertSessionHasNoErrors();
        $this->get('/services?locale=en')->assertSee('Updated request CTA')->assertSee('Request follow-up');
        $this->get('/gallery?locale=bn')->assertSee('পরিবর্তিত অনুরোধ')->assertSee('যোগাযোগের অনুরোধ');
        $this->put('/admin/settings/notices', ['settings' => ['medical_disclaimer' => ['en' => 'Edited educational disclaimer', 'bn' => 'পরিবর্তিত শিক্ষামূলক সতর্কতা'], 'stock_notice' => ['en' => 'Edited stock photo caveat', 'bn' => 'ছবির পরিবর্তিত সতর্কতা']]])->assertSessionHasNoErrors();
        $this->get('/blog/pcos?locale=en')->assertSee('Edited educational disclaimer');
        $this->get('/about?locale=bn')->assertSee('ছবির পরিবর্তিত সতর্কতা');
    }

    public function test_shared_labels_images_alt_text_and_copyright_are_database_owned(): void
    {
        $heroAlt = ContentField::where('key', 'home.hero_alt')->firstOrFail();
        $image = MediaAsset::where('id', '!=', SiteSetting::firstOrFail()->portrait_media_asset_id)->firstOrFail();
        $this->put('/admin/settings/branding', ['settings' => ['portrait_media_asset_id' => $image->id], 'fields' => [$heroAlt->id => ['value' => ['en' => 'Edited hero alt', 'bn' => 'পরিবর্তিত হিরো ছবির বিবরণ']]]])->assertSessionHasNoErrors();
        $this->get('/?locale=bn')->assertSee('alt="পরিবর্তিত হিরো ছবির বিবরণ"', false)->assertSee(e($image->url()), false);
        $aboutImage = ContentField::where('key', 'about.image')->firstOrFail();
        $aboutAlt = ContentField::where('key', 'about.image_alt')->firstOrFail();
        $this->put('/admin/pages/about', ['fields' => [$aboutImage->id => ['media_asset_id' => $image->id], $aboutAlt->id => ['value' => ['en' => 'Changed profile alternative text', 'bn' => 'পরিবর্তিত পরিচিতির ছবির বিবরণ']]]])->assertSessionHasNoErrors();
        $this->get('/about?locale=en')->assertSee('alt="Changed profile alternative text"', false)->assertSee(e($image->url()), false);
        $all = ContentField::where('key', 'ui.filter_all')->firstOrFail();
        $this->put('/admin/settings/ui', ['fields' => [$all->id => ['value' => ['en' => 'Every category', 'bn' => 'সব বিভাগ']]]])->assertSessionHasNoErrors();
        $this->get('/blog?locale=bn')->assertSee('সব বিভাগ');
        $copyright = ContentField::where('key', 'footer.copyright_format')->firstOrFail();
        $this->put('/admin/settings/footer', ['fields' => [$copyright->id => ['value' => ['en' => '© {year} Owner footer', 'bn' => '© {year} মালিকের ফুটার']]]])->assertSessionHasNoErrors();
        $this->get('/?locale=bn')->assertSee('© '.now()->format('Y').' মালিকের ফুটার');
        $this->put('/admin/settings/footer', ['fields' => [$copyright->id => ['value' => ['en' => '© 2000 Stale year']]]])->assertSessionHasErrors('fields.'.$copyright->id.'.value.en');
        $this->assertSame('© {year} Owner footer', $copyright->fresh()->value['en']);
    }

    public function test_page_metadata_is_localized_persisted_safe_and_falls_back_without_sources(): void
    {
        $image = MediaAsset::firstOrFail();
        $this->put('/admin/pages/home', ['page' => [
            'meta_title' => ['en' => 'Owner SEO title', 'bn' => 'মালিকের এসইও শিরোনাম'],
            'meta_description' => ['en' => 'Owner SEO description', 'bn' => 'মালিকের এসইও বিবরণ'],
            'og_title' => ['en' => 'Owner OG title', 'bn' => 'মালিকের সামাজিক শিরোনাম'],
            'og_description' => ['en' => 'Owner OG description', 'bn' => 'মালিকের সামাজিক বিবরণ'],
            'og_media_asset_id' => $image->id,
        ]])->assertSessionHasNoErrors();
        $page = Page::where('key', 'home')->firstOrFail();
        $this->assertSame('মালিকের সামাজিক শিরোনাম', $page->og_title['bn']);
        $this->assertSame($image->id, $page->ogImage->id);
        $this->get('/?locale=en')->assertSee('<title>Owner SEO title</title>', false)->assertSee('content="Owner SEO description"', false);
        $this->get('/?locale=bn')->assertSee('<title>মালিকের এসইও শিরোনাম</title>', false)->assertSee('content="মালিকের এসইও বিবরণ"', false);
        $this->put('/admin/pages/home', ['page' => ['meta_title' => ['bn' => '']]])->assertSessionHasNoErrors();
        $this->get('/?locale=bn')->assertSee('<title>Owner SEO title</title>', false);
        $this->put('/admin/pages/home', ['page' => ['meta_title' => ['en' => '', 'bn' => ''], 'meta_description' => ['en' => '', 'bn' => '']]])->assertSessionHasNoErrors();
        $this->get('/?locale=en')->assertOk()->assertDontSee('<title></title>', false)->assertDontSee('content=""', false);
        $this->put('/admin/pages/home', ['page' => ['og_title' => ['en' => str_repeat('x', 201)]]])->assertSessionHasErrors('page.og_title.en');
        $this->put('/admin/pages/home', ['page' => ['og_media_asset_id' => 99999999]])->assertSessionHasErrors('page.og_media_asset_id');
    }

    public function test_navigation_targets_labels_visibility_and_custom_items_use_safe_routes(): void
    {
        $item = NavigationItem::where('key', 'header:about')->firstOrFail();
        $bn = $item->label['bn'];
        $this->put('/admin/navigation/'.$item->id, ['item' => ['label' => ['en' => 'Owner menu', 'bn' => 'মালিকের মেনু'], 'target' => ['en' => ['kind' => 'internal', 'route' => 'services'], 'bn' => ['kind' => 'internal', 'route' => 'diagnostics']]]])->assertSessionHasNoErrors();
        $this->get('/?locale=en')->assertSee('href="'.route('services', ['locale' => 'en']).'" class="">Owner menu</a>', false);
        $this->get('/?locale=bn')->assertSee('href="'.route('diagnostics', ['locale' => 'bn']).'" class="">মালিকের মেনু</a>', false);
        $this->put('/admin/navigation/'.$item->id, ['item' => ['label' => ['en' => 'English-only menu']]])->assertSessionHasNoErrors();
        $this->assertSame('মালিকের মেনু', $item->fresh()->label['bn']);
        $this->assertNotSame($bn, $item->fresh()->label['bn']);
        foreach (['javascript:alert(1)', 'data:text/html,evil', 'admin.dashboard', 'https://user:password@example.test', '//example.test', ['kind' => 'external', 'url' => 'home'], ['kind' => 'internal', 'route' => 'https://example.test'], ['kind' => 'internal', 'route' => 'services', 'unknown' => 'extra']] as $target) {
            $this->put('/admin/navigation/'.$item->id, ['item' => ['target' => ['en' => $target]]])->assertSessionHasErrors('item.target.en');
            $this->assertSame('services', $item->fresh()->target['en']);
        }
        $this->put('/admin/navigation/'.$item->id, ['item' => ['placement' => 'footer']])->assertSessionHasErrors('item.placement');
        $this->delete('/admin/navigation/'.$item->id, ['confirm_delete' => '1'])->assertSessionHasErrors('item');
        $this->put('/admin/navigation/'.$item->id, ['item' => ['is_visible' => '0']])->assertSessionHasNoErrors();
        $this->get('/?locale=en')->assertDontSee('English-only menu')->assertSee('class="lang"', false);
        $this->post('/admin/navigation', ['item' => ['label' => ['en' => 'Owner external link', 'bn' => 'বাহ্যিক লিংক'], 'target' => ['en' => ['kind' => 'external', 'url' => 'https://example.test/info'], 'bn' => ''], 'placement' => 'footer', 'sort_order' => 10, 'is_visible' => '1']])->assertSessionHasNoErrors();
        $custom = NavigationItem::where('label->en', 'Owner external link')->firstOrFail();
        $this->assertStringStartsWith('custom:', $custom->key);
        $this->get('/?locale=bn')->assertSee('href="https://example.test/info"', false)->assertSee('বাহ্যিক লিংক');
        $this->delete('/admin/navigation/'.$custom->id, ['confirm_delete' => '1'])->assertSessionHasNoErrors();
        $this->assertNull($custom->fresh());
    }

    public function test_scoped_whitelists_essential_sections_and_atomic_saves_protect_other_content(): void
    {
        $settings = SiteSetting::firstOrFail();
        $original = $settings->doctor_name;
        $this->put('/admin/settings/doctor', ['settings' => ['doctor_name' => ['en' => 'Should not persist'], 'address' => ['en' => 'Wrong group']]])->assertSessionHasErrors('settings');
        $this->assertSame($original, $settings->fresh()->doctor_name);
        $title = ContentField::where('key', 'home.herotitle')->firstOrFail();
        $this->put('/admin/pages/about', ['fields' => [$title->id => ['value' => ['en' => 'Wrong page']]]])->assertSessionHasErrors('fields');
        $this->put('/admin/pages/home', ['fields' => [$title->id => ['value' => ['en' => 'Changed', 'fr' => 'Extra language']]]])->assertSessionHasErrors('fields.'.$title->id.'.value');
        $this->put('/admin/pages/home', ['page' => ['key' => 'contact']])->assertSessionHasErrors('page');
        $hero = Page::where('key', 'home')->firstOrFail()->sections()->where('key', 'home.hero')->firstOrFail();
        $this->put('/admin/pages/home', ['sections' => [$hero->id => ['is_visible' => '0']]])->assertSessionHasErrors('sections.'.$hero->id.'.is_visible');
        $this->put('/admin/pages/home', ['sections' => [$hero->id => ['sort_order' => 5]]])->assertSessionHasErrors('sections.'.$hero->id);
        // Form rules permit partial translations; complete-record validation fails inside the transaction.
        $copyright = ContentField::where('key', 'footer.copyright_format')->firstOrFail();
        $footer = $settings->footer_description;
        $this->put('/admin/settings/footer', ['settings' => ['footer_description' => ['en' => 'Must roll back']], 'fields' => [$copyright->id => ['value' => ['en' => 'Missing required year']]]])->assertSessionHasErrors('fields.'.$copyright->id.'.value.en');
        $this->assertSame($footer, $settings->fresh()->footer_description);
        $this->put('/admin/settings/cta', ['settings' => ['cta_target' => ['en' => 'https://facebook.com/example']]])->assertSessionHasErrors('settings.cta_target.en');
        $this->put('/admin/settings/notices', ['settings' => ['medical_disclaimer' => ['en' => '']]])->assertSessionHasErrors('settings.medical_disclaimer.en');
    }

    public function test_preview_counts_section_visibility_and_article_backlink_apply_on_next_request(): void
    {
        $count = ContentField::where('key', 'home.article_count')->firstOrFail();
        $this->put('/admin/pages/home', ['fields' => [$count->id => ['value' => 1]]])->assertSessionHasNoErrors();
        $html = $this->get('/?locale=en')->assertOk()->getContent();
        $this->assertSame(1, substr_count($html, '<article class="card article-card"'));
        $this->put('/admin/pages/home', ['fields' => [$count->id => ['value' => 25]]])->assertSessionHasErrors('fields.'.$count->id.'.value');
        $section = Page::where('key', 'home')->firstOrFail()->sections()->where('key', 'home.journal')->firstOrFail();
        $this->put('/admin/pages/home', ['sections' => [$section->id => ['is_visible' => '0']]])->assertSessionHasNoErrors();
        $this->get('/')->assertDontSee('class="card article-card"', false);
        $this->put('/admin/pages/article', ['page' => ['label' => ['en' => 'Return to journal', 'bn' => 'জার্নালে ফিরে যান']]])->assertSessionHasNoErrors();
        $this->get('/blog/pcos?locale=bn')->assertSee('জার্নালে ফিরে যান');
        $this->assertSame(3, $section->fresh()->sort_order);
    }

    public function test_og_image_usage_blocks_deletion_and_is_replaced_with_other_owners(): void
    {
        $asset = MediaAsset::firstOrFail();
        $replacement = MediaAsset::where('id', '!=', $asset->id)->firstOrFail();
        $this->put('/admin/pages/about', ['page' => ['og_media_asset_id' => $asset->id]])->assertSessionHasNoErrors();
        $this->assertTrue($asset->fresh()->pages()->where('key', 'about')->exists());
        $this->get('/admin/media/'.$asset->id.'/edit')->assertOk()->assertSee('Page OG image');
        $this->delete('/admin/media/'.$asset->id, ['confirm_delete' => '1'])->assertSessionHasErrors('media');
        $this->put('/admin/media/'.$asset->id.'/references', ['replacement_id' => $replacement->id, 'confirm_replace' => '1'])->assertSessionHasNoErrors();
        $this->assertSame($replacement->id, Page::where('key', 'about')->firstOrFail()->og_media_asset_id);
    }

    public function test_new_editor_routes_deny_guests_and_non_admins_on_reads_and_writes(): void
    {
        $this->app['auth']->guard()->logout();
        foreach (['/admin/settings', '/admin/pages', '/admin/pages/home/edit', '/admin/navigation', '/admin/navigation/create'] as $url) {
            $this->get($url)->assertRedirect('/admin/login');
        }
        $this->put('/admin/settings/doctor', [])->assertRedirect('/admin/login');
        $this->put('/admin/pages/home', [])->assertRedirect('/admin/login');
        $this->post('/admin/navigation', [])->assertRedirect('/admin/login');
        $user = User::create(['name' => 'Non-admin', 'email' => Str::uuid().'@example.test', 'password' => bin2hex(random_bytes(24))]);
        $this->actingAs($user);
        $this->get('/admin/settings')->assertForbidden();
        $this->put('/admin/pages/home', [])->assertForbidden();
        $this->post('/admin/navigation', [])->assertForbidden();
    }
}
