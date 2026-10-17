<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\ContentField;
use App\Models\GalleryImage;
use App\Models\MediaAsset;
use App\Models\User;
use App\Services\CareCollectionManager;
use App\Services\InitialWebsiteImporter;
use App\Services\WebsiteContent;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class GalleryCmsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        if (DB::connection()->getDatabaseName() !== 'doctor_portfolio_testing') {
            throw new \RuntimeException('Use the isolated doctor_portfolio_testing database.');
        }
        DB::beginTransaction();
        $this->beforeApplicationDestroyed(fn () => DB::rollBack());
        app(InitialWebsiteImporter::class)->import();
        $admin = User::create(['name' => 'Test gallery editor', 'email' => Str::uuid().'@example.test', 'password' => bin2hex(random_bytes(24))]);
        $admin->forceFill(['is_admin' => true])->save();
        $this->actingAs($admin);
    }

    private function input(string $caption = 'Owner gallery image'): array
    {
        return ['caption' => ['en' => $caption, 'bn' => 'মালিকের গ্যালারির ছবি'], 'alt_text' => ['en' => 'Accurate image description', 'bn' => 'ছবির সঠিক বর্ণনা'],
            'category_id' => Category::where('slug', 'facility')->firstOrFail()->id, 'media_asset_id' => MediaAsset::firstOrFail()->id,
            'is_visible' => '1', 'on_homepage' => '0'];
    }

    private function createImage(string $caption = 'Owner gallery image', array $changes = []): GalleryImage
    {
        $this->post('/admin/gallery', ['item' => array_replace($this->input($caption), $changes)])->assertSessionHasNoErrors();

        return GalleryImage::where('caption->en', $caption)->firstOrFail();
    }

    private function figures(string $html): array
    {
        $dom = new \DOMDocument;
        @$dom->loadHTML('<?xml encoding="UTF-8">'.$html);
        $result = [];
        foreach ((new \DOMXPath($dom))->query('//figure[contains(@class,"gallery-item")]') as $figure) {
            $result[] = ['caption' => $figure->getAttribute('data-caption'), 'category' => $figure->getAttribute('data-category'), 'url' => $figure->getAttribute('data-url'),
                'alt' => $figure->getElementsByTagName('img')->item(0)?->getAttribute('alt'), 'role' => $figure->getAttribute('role'), 'tabindex' => $figure->getAttribute('tabindex')];
        }

        return $result;
    }

    private function assertOrder(array $ids): void
    {
        $this->assertSame($ids, GalleryImage::orderBy('sort_order')->orderBy('id')->pluck('id')->all());
        $this->assertSame(range(0, count($ids) - 1), GalleryImage::orderBy('sort_order')->orderBy('id')->pluck('sort_order')->all());
    }

    public function test_all_eight_original_entries_and_bengali_captions_are_preserved(): void
    {
        $expected = [
            ['Healthcare environment', 'স্বাস্থ্যসেবার পরিবেশ', 'facility', 'clinic'],
            ['Consultation and care', 'পরামর্শ ও চিকিৎসা', 'clinical', 'consult'],
            ['Laboratory research', 'ল্যাবরেটরি কার্যক্রম', 'facility', 'lab'],
            ['Pregnancy wellbeing', 'গর্ভকালীন সুস্থতা', 'wellness', 'pregnancy'],
            ['Healthcare professionals', 'স্বাস্থ্যকর্মীদের সেবা', 'clinical', 'team'],
            ['Medical facilities', 'চিকিৎসা কেন্দ্র', 'facility', 'interior'],
            ['Women’s health', 'নারীস্বাস্থ্য', 'wellness', 'care'],
            ['Clinical guidance', 'চিকিৎসা পরামর্শ', 'clinical', 'doctor'],
        ];
        $images = GalleryImage::with(['category', 'mediaAsset'])->orderBy('sort_order')->get();
        $this->assertCount(8, $images);
        $source = file_get_contents(base_path('frontend/assets/app.js'));
        foreach ($images as $i => $image) {
            [$en,$bn,$category,$photo] = $expected[$i];
            $this->assertSame(['en' => $en, 'bn' => $bn], $image->caption);
            $this->assertSame($image->caption, $image->alt_text);
            $this->assertStringContainsString($bn, $source);
            $this->assertSame($category, $image->category->slug);
            $this->assertSame('demo-photo-'.$photo, $image->mediaAsset->key);
            $this->assertTrue($image->is_visible);
            $this->assertSame($i < 3, $image->on_homepage);
            $this->get('/admin/gallery/'.$image->id.'/edit')->assertOk()->assertSee('item[caption][en]', false)->assertSee('item[alt_text][bn]', false)->assertSee('confirm_delete')->assertDontSee('name="item[sort_order]"', false);
        }
        foreach (['en', 'bn'] as $locale) {
            $response = $this->get('/gallery?locale='.$locale)->assertOk();
            $figures = $this->figures($response->getContent());
            $this->assertSame(array_column($expected, $locale === 'en' ? 0 : 1), array_column($figures, 'caption'));
            $this->assertSame(array_column($figures, 'caption'), array_column($figures, 'alt'));
            $this->assertSame(array_fill(0, 8, 'button'), array_column($figures, 'role'));
            $this->assertSame(array_fill(0, 8, '0'), array_column($figures, 'tabindex'));
            $response->assertSee(e(app(WebsiteContent::class)->setting('stock_notice')), false)->assertDontSee('/build/assets/admin-')->assertDontSee('frontend/assets/app.js');
            $this->assertSame(array_slice(array_column($figures, 'caption'), 0, 3), array_column($this->figures($this->get('/?locale='.$locale)->getContent()), 'caption'));
        }
        $this->get('/admin/gallery')->assertOk()->assertSee('Move up')->assertSee('Confirm hide');
        $this->get('/admin/gallery/create')->assertOk()->assertSee('Browse library')->assertSee('Upload from device')->assertSee('data-media-upload', false);
        $this->get('/admin/gallery/999999/edit')->assertNotFound();
        $this->assertTrue(app(InitialWebsiteImporter::class)->import()['skipped']);
    }

    public function test_crud_preserves_other_language_escapes_copy_and_uses_alt_fallback(): void
    {
        $image = $this->createImage();
        $this->assertStringStartsWith('custom-', $image->key);
        $this->assertSame(8, $image->sort_order);
        $this->put('/admin/gallery/'.$image->id, ['item' => ['caption' => ['en' => ' English " <script>alert(1)</script> '], 'alt_text' => ['en' => ' Alt <img onerror="bad"> ']]])->assertSessionHasNoErrors();
        $image->refresh();
        $this->assertSame('মালিকের গ্যালারির ছবি', $image->caption['bn']);
        $this->assertSame('ছবির সঠিক বর্ণনা', $image->alt_text['bn']);
        $this->get('/gallery?locale=en')->assertSee('English &quot; &lt;script&gt;alert(1)&lt;/script&gt;', false)->assertSee('Alt &lt;img onerror=&quot;bad&quot;&gt;', false)->assertDontSee('<script>alert(1)</script>', false);
        $this->put('/admin/gallery/'.$image->id, ['item' => ['caption' => ['bn' => ''], 'alt_text' => ['bn' => '']]])->assertSessionHasNoErrors();
        $this->get('/gallery?locale=bn')->assertSee('English &quot; &lt;script&gt;alert(1)&lt;/script&gt;', false)->assertSee('Alt &lt;img onerror=&quot;bad&quot;&gt;', false);
        $this->get('/admin/gallery')->assertSee('Bangla caption missing');
        $this->put('/admin/gallery/'.$image->id, ['item' => ['alt_text' => ['en' => '', 'bn' => '']]])->assertSessionHasNoErrors();
        $figures = $this->figures($this->get('/gallery?locale=bn')->getContent());
        $this->assertSame($image->mediaAsset->localized('alt_text', 'bn'), $figures[8]['alt']);
        $this->put('/admin/gallery/'.$image->id, ['item' => ['caption' => ['bn' => 'পুনরুদ্ধার করা ক্যাপশন']]])->assertSessionHasNoErrors();
        $this->assertSame('English " <script>alert(1)</script>', $image->fresh()->caption['en']);
        $this->delete('/admin/gallery/'.$image->id)->assertSessionHasErrors('confirm_delete');
        $mediaId = $image->media_asset_id;
        $this->delete('/admin/gallery/'.$image->id, ['confirm_delete' => 1])->assertSessionHasNoErrors();
        $this->assertNull($image->fresh());
        $this->assertNotNull(MediaAsset::find($mediaId));
        $this->assertSame(9, MediaAsset::count());
    }

    public function test_visibility_homepage_selection_and_count_are_independent(): void
    {
        $first = GalleryImage::orderBy('sort_order')->firstOrFail();
        $this->patch('/admin/gallery/'.$first->id.'/visibility', ['state' => 0])->assertSessionHasErrors('confirm_disable');
        $this->patch('/admin/gallery/'.$first->id.'/visibility', ['state' => 0, 'confirm_disable' => 1])->assertSessionHasNoErrors();
        $this->assertTrue($first->fresh()->on_homepage);
        $this->assertFalse($first->fresh()->is_visible);
        $this->get('/gallery')->assertDontSee('data-caption="Healthcare environment"', false);
        $this->assertCount(2, $this->figures($this->get('/')->getContent()));
        $this->patch('/admin/gallery/'.$first->id.'/visibility', ['state' => 1])->assertSessionHasNoErrors();
        $this->assertCount(3, $this->figures($this->get('/')->getContent()));
        $this->put('/admin/gallery/'.$first->id, ['item' => ['on_homepage' => 0]])->assertSessionHasNoErrors();
        $this->get('/gallery')->assertSee('data-caption="Healthcare environment"', false);
        $this->assertCount(2, $this->figures($this->get('/')->getContent()));
        $last = GalleryImage::orderByDesc('sort_order')->firstOrFail();
        $this->put('/admin/gallery/'.$last->id, ['item' => ['on_homepage' => 1]])->assertSessionHasNoErrors();
        $this->assertSame(['Consultation and care', 'Laboratory research', 'Clinical guidance'], array_column($this->figures($this->get('/')->getContent()), 'caption'));
        $count = ContentField::where('key', 'home.gallery_count')->firstOrFail();
        $this->put('/admin/pages/home', ['fields' => [$count->id => ['value' => 1]]])->assertSessionHasNoErrors();
        $this->assertSame(['Consultation and care'], array_column($this->figures($this->get('/')->getContent()), 'caption'));
    }

    public function test_accessible_ordering_is_global_transactional_and_includes_hidden_rows(): void
    {
        config(['gallery.admin_per_page' => 2]);
        $ids = GalleryImage::orderBy('sort_order')->pluck('id')->all();
        $first = GalleryImage::findOrFail($ids[0]);
        $last = GalleryImage::findOrFail($ids[7]);
        $category = Category::where('slug', 'facility')->firstOrFail();
        $this->get('/admin/gallery?category='.$category->id.'&page=2')->assertOk()->assertSee('Move up')->assertSee('name="page" value="2"', false);
        $this->patch('/admin/gallery/'.$ids[2].'/move', ['direction' => 'up', 'category' => $category->id, 'page' => 2, 'evil' => 'ignored'])->assertRedirect(route('admin.gallery.index', ['category' => (string) $category->id, 'page' => 2]));
        [$ids[1],$ids[2]] = [$ids[2], $ids[1]];
        $this->assertOrder($ids);
        $this->patch('/admin/gallery/'.$first->id.'/move', ['direction' => 'up'])->assertSessionHasNoErrors();
        $this->patch('/admin/gallery/'.$last->id.'/move', ['direction' => 'down'])->assertSessionHasNoErrors();
        $this->assertOrder($ids);
        $this->patch('/admin/gallery/'.$ids[1].'/visibility', ['state' => 0, 'confirm_disable' => 1])->assertSessionHasNoErrors();
        $this->patch('/admin/gallery/'.$ids[1].'/move', ['direction' => 'down'])->assertSessionHasNoErrors();
        [$ids[1],$ids[2]] = [$ids[2], $ids[1]];
        $this->assertOrder($ids);
        $attempts = 0;
        $events = GalleryImage::getEventDispatcher();
        GalleryImage::setEventDispatcher(clone $events);
        GalleryImage::updating(function () use (&$attempts) {
            if (++$attempts === 2) {
                throw new \RuntimeException('Simulated second order write failure');
            }
        });
        try {
            $this->patch('/admin/gallery/'.$ids[3].'/move', ['direction' => 'up'])->assertSessionHasErrors('item');
        } finally {
            GalleryImage::setEventDispatcher($events);
        }
        $this->assertOrder($ids);
        $this->delete('/admin/gallery/'.$ids[3], ['confirm_delete' => 1])->assertSessionHasNoErrors();
        array_splice($ids, 3, 1);
        $this->assertOrder($ids);
    }

    public function test_public_filters_precede_pagination_and_keep_safe_localized_links(): void
    {
        config(['gallery.public_per_page' => 2]);
        $facility = Category::where('slug', 'facility')->firstOrFail();
        $this->createImage('Extra facility', ['category_id' => $facility->id]);
        $this->createImage('Hidden facility', ['category_id' => $facility->id, 'is_visible' => 0, 'on_homepage' => 1]);
        $this->createImage('Another visible facility', ['category_id' => $facility->id]);
        $first = $this->get('/gallery?category=facility&locale=bn&untrusted=discard')->assertOk();
        $figures = $this->figures($first->getContent());
        $this->assertSame(['স্বাস্থ্যসেবার পরিবেশ', 'ল্যাবরেটরি কার্যক্রম'], array_column($figures, 'caption'));
        $first->assertSee('/gallery?locale=bn&amp;category=facility&amp;page=2', false)->assertDontSee('untrusted=');
        $second = $this->get('/gallery?category=facility&locale=bn&page=2')->assertOk();
        $this->assertSame(['চিকিৎসা কেন্দ্র', 'মালিকের গ্যালারির ছবি'], array_column($this->figures($second->getContent()), 'caption'));
        $second->assertSee('/gallery?category=facility&amp;page=2&amp;locale=en', false)->assertSee('aria-current="page">2', false);
        $third = $this->get('/gallery?category=facility&locale=en&page=3')->assertOk();
        $this->assertSame(['Another visible facility'], array_column($this->figures($third->getContent()), 'caption'));
        $this->assertSame(['facility'], array_unique(array_column($this->figures($third->getContent()), 'category')));
        $third->assertDontSee('Hidden facility');
        $this->get('/gallery.html?category=facility&page=3&locale=bn&bad=x')->assertRedirect('/gallery?locale=bn&category=facility&page=3');
        $this->assertCount(2, $this->figures($this->get('/gallery?category[]=facility&page[]=9')->getContent()));
        $this->get('/gallery?category=unknown&page=0&locale=bn')->assertOk()->assertDontSee('category=unknown');
        $this->get('/gallery?category=facility&page=999')->assertSee(e(app(WebsiteContent::class)->text('ui.empty_filter_results')), false);
    }

    public function test_admin_category_visibility_search_and_pagination_apply_before_results(): void
    {
        config(['gallery.admin_per_page' => 2]);
        $facility = Category::where('slug', 'facility')->firstOrFail();
        $first = $this->get('/admin/gallery?category='.$facility->id)->assertOk()->assertSee('3 matching entries')->assertSee('Pagination Navigation')->assertSee('category='.$facility->id.'&amp;page=2', false);
        $first->assertSee('Healthcare environment')->assertSee('Laboratory research')->assertDontSee('Consultation and care</p>', false);
        $this->get('/admin/gallery?category='.$facility->id.'&page=2')->assertSee('Medical facilities');
        $this->get('/admin/gallery?q=পরামর্শ')->assertOk()->assertSee('Consultation and care')->assertSee('Clinical guidance');
        $hidden = $this->createImage('Owner hidden image', ['is_visible' => 0]);
        $this->get('/admin/gallery?visibility=hidden')->assertSee('Owner hidden image')->assertSee('1 matching entries');
        $this->get('/admin/gallery?visibility=visible')->assertDontSee('Owner hidden image');
        $this->get('/admin/gallery?q=nonexistent')->assertSee('No gallery entries found');
        $this->get('/admin/gallery?category[]=facility')->assertSessionHasErrors('category');
        $this->get('/admin/gallery?visibility=invalid')->assertSessionHasErrors('visibility');
        $this->get('/admin/gallery?category=999999')->assertSessionHasErrors('category');
        $this->get('/admin/gallery/'.$hidden->id.'/edit')->assertSee('homepage preview')->assertSee('Stable key:');
    }

    public function test_upload_picker_context_local_urls_and_shared_file_survive_entry_deletion(): void
    {
        $disk = Storage::fake('gallery-test-'.bin2hex(random_bytes(12)), ['url' => 'https://example.test/uploads', 'throw' => true]);
        Storage::set('public-media', $disk);
        $this->beforeApplicationDestroyed(fn () => $disk->delete($disk->allFiles()));
        $this->get('/admin/media?picker=1')->assertSee('/admin/media/create?picker=1', false);
        $this->get('/admin/media/create?picker=1')->assertSee('name="picker" value="1"', false);
        $this->post('/admin/media', ['picker' => '1', 'title' => ['en' => 'Local gallery fixture', 'bn' => 'স্থানীয় ছবির নমুনা'], 'alt_text' => ['en' => 'Verified test raster', 'bn' => 'যাচাইকৃত পরীক্ষার ছবি'], 'provenance_note' => ['en' => 'Generated test fixture', 'bn' => 'পরীক্ষার জন্য তৈরি নমুনা'], 'image' => UploadedFile::fake()->image('sample.png', 12, 8)])->assertSessionHasNoErrors();
        $media = MediaAsset::whereNotNull('path')->firstOrFail();
        $this->assertSame('public-media', $media->disk);
        $this->get('/admin/media/'.$media->id.'/edit?picker=1')->assertOk()->assertSee('Choose image')->assertSee('data-media-choose', false)->assertSee(e($media->url()), false);
        $this->put('/admin/media/'.$media->id, ['picker' => '1', 'alt_text' => ['en' => 'New local alt']])->assertRedirect(route('admin.media.edit', ['media' => $media, 'picker' => '1']))->assertSessionHasNoErrors();
        $this->assertSame('যাচাইকৃত পরীক্ষার ছবি', $media->fresh()->alt_text['bn']);
        $image = $this->createImage('Owner local image', ['media_asset_id' => $media->id, 'alt_text' => ['en' => '', 'bn' => '']]);
        $this->get('/gallery?locale=bn')->assertSee(e($media->url()), false)->assertSee('যাচাইকৃত পরীক্ষার ছবি')->assertDontSee('/storage/');
        $this->delete('/admin/media/'.$media->id, ['confirm_delete' => 1])->assertSessionHasErrors('media');
        $this->delete('/admin/gallery/'.$image->id, ['confirm_delete' => 1])->assertSessionHasNoErrors();
        $this->assertTrue($disk->exists($media->path));
        $this->assertNotNull($media->fresh());
        $other = $this->createImage('Another reference', ['media_asset_id' => $media->id]);
        $replacement = MediaAsset::whereNotNull('source_url')->firstOrFail();
        $this->put('/admin/media/'.$media->id.'/references', ['replacement_id' => $replacement->id, 'confirm_replace' => 1])->assertSessionHasNoErrors();
        $this->assertSame($replacement->id, $other->fresh()->media_asset_id);
        $this->assertTrue($disk->exists($media->path));
        $this->assertNotNull($media->fresh());
        $this->get('/admin/media/create?picker=untrusted')->assertSessionHasErrors('picker');
        $this->post('/admin/media', ['picker' => 'https://bad.example', 'image' => UploadedFile::fake()->image('bad.png'), 'title' => ['en' => 'Invalid context']])->assertSessionHasErrors('picker');
        $this->assertSame(10, MediaAsset::count());
    }

    public function test_unapproved_fields_languages_category_media_and_order_inputs_are_rejected(): void
    {
        $image = GalleryImage::firstOrFail();
        $articleOnly = Category::create(['slug' => 'article-only', 'label' => ['en' => 'Article only', 'bn' => 'শুধু নিবন্ধ'], 'sort_order' => 4, 'for_blog' => true, 'for_gallery' => false]);
        foreach ([['caption' => ['en' => '']], ['caption' => ['fr' => 'Bad']], ['caption' => ['bn' => ['nested']]], ['category_id' => $articleOnly->id], ['category_id' => 999999], ['media_asset_id' => 999999], ['media_asset_id' => null], ['key' => 'overwrite-source'], ['sort_order' => 0], ['source_url' => 'javascript:bad'], ['is_visible' => 'invalid']] as $input) {
            $this->put('/admin/gallery/'.$image->id, ['item' => $input])->assertSessionHasErrors();
        }
        $this->assertSame('Healthcare environment', $image->fresh()->caption['en']);
        $this->post('/admin/gallery', ['item' => array_replace($this->input(), ['category_id' => $articleOnly->id])])->assertSessionHasErrors('item.category_id');
        $this->patch('/admin/gallery/'.$image->id.'/move', ['direction' => 'drag'])->assertSessionHasErrors('direction');
        $this->patch('/admin/gallery/'.$image->id.'/visibility', ['state' => 0, 'confirm_disable' => 0])->assertSessionHasErrors('confirm_disable');
        try {
            app(CareCollectionManager::class)->update(config('gallery.editor'), $image, ['category_id' => $articleOnly->id], true);
            $this->fail('Manager accepted an inapplicable category');
        } catch (ValidationException $error) {
            $this->assertArrayHasKey('item.category_id', $error->errors());
        }
        $this->assertSame('facility', $image->fresh()->category->slug);
    }

    public function test_empty_categories_and_gallery_use_localized_copy_and_ordinary_seed_preserves_deletions(): void
    {
        $category = Category::create(['slug' => 'empty-gallery', 'label' => ['en' => 'Empty gallery', 'bn' => 'খালি গ্যালারি'], 'sort_order' => 0, 'for_blog' => false, 'for_gallery' => true]);
        $this->get('/gallery?category=empty-gallery&locale=bn')->assertOk()->assertSee('খালি গ্যালারি')->assertSee(e(app(WebsiteContent::class)->text('ui.empty_filter_results')), false)->assertSee(e(app(WebsiteContent::class)->setting('stock_notice')), false);
        foreach (GalleryImage::all() as $image) {
            $this->delete('/admin/gallery/'.$image->id, ['confirm_delete' => 1])->assertSessionHasNoErrors();
        }
        $this->assertSame(0, GalleryImage::count());
        $this->assertSame(9, MediaAsset::count());
        $this->assertTrue(app(InitialWebsiteImporter::class)->import()['skipped']);
        $this->assertSame(0, GalleryImage::count());
        $this->get('/gallery?locale=bn')->assertOk()->assertSee(e(app(WebsiteContent::class)->text('ui.empty_filter_results')), false);
        $this->get('/admin/gallery')->assertSee('No gallery entries found');
        $created = $this->createImage('First in empty gallery', ['category_id' => $category->id]);
        $this->assertSame(0, $created->sort_order);
    }

    public function test_every_gallery_endpoint_denies_guests_and_non_admins(): void
    {
        $image = GalleryImage::firstOrFail();
        $actions = [['get', '/admin/gallery', []], ['get', '/admin/gallery/create', []], ['get', '/admin/gallery/'.$image->id.'/edit', []], ['post', '/admin/gallery', ['item' => $this->input()]], ['put', '/admin/gallery/'.$image->id, ['item' => ['on_homepage' => 0]]], ['patch', '/admin/gallery/'.$image->id.'/move', ['direction' => 'down']], ['patch', '/admin/gallery/'.$image->id.'/visibility', ['state' => 0, 'confirm_disable' => 1]], ['delete', '/admin/gallery/'.$image->id, ['confirm_delete' => 1]]];
        auth()->logout();
        $this->flushSession();
        foreach ($actions as [$method,$url,$data]) {
            $this->$method($url, $data)->assertRedirect(route('admin.login'));
        }
        $user = User::create(['name' => 'Test non-admin', 'email' => Str::uuid().'@example.test', 'password' => bin2hex(random_bytes(24))]);
        $this->actingAs($user);
        foreach ($actions as [$method,$url,$data]) {
            $this->$method($url, $data)->assertForbidden();
        }
        $this->assertSame(8, GalleryImage::count());
        $this->assertSame('Healthcare environment', $image->fresh()->caption['en']);
    }
}
