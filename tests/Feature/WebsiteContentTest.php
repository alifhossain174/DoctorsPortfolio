<?php

namespace Tests\Feature;

use App\Models\CareCard;
use App\Models\Category;
use App\Models\ConsultationOption;
use App\Models\GalleryImage;
use App\Models\Inquiry;
use App\Models\NavigationItem;
use App\Models\Post;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\User;
use App\Services\ContentRegistry;
use App\Services\InitialWebsiteImporter;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class WebsiteContentTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        if (DB::connection()->getDatabaseName() !== 'doctor_portfolio_test') {
            throw new \RuntimeException('These tests may run only in doctor_portfolio_test.');
        }
        // Migrate the dedicated test database once outside tests; every test rolls back DML.
        DB::beginTransaction();
        $this->beforeApplicationDestroyed(fn () => DB::rollBack());
        app(InitialWebsiteImporter::class)->import();
    }

    public function test_exact_initial_inventory_bilingual_bodies_slugs_and_relationships(): void
    {
        $this->assertSame(4, Post::published()->count());
        $this->assertSame(8, GalleryImage::visible()->count());
        $this->assertSame(5, Service::count());
        $this->assertSame(4, CareCard::count());
        $this->assertSame(4, ConsultationOption::count());
        $this->assertSame(0, Inquiry::count());
        $this->assertSame(0, User::count());
        $this->assertSame(['pregnancy-care', 'pcos', 'fertility-first-visit', 'menopause'], Post::orderBy('sort_order')->pluck('slug')->all());
        foreach (Post::all() as $post) {
            $this->assertCount(2, $post->body['en']);
            $this->assertCount(2, $post->body['bn']);
            $this->assertNotEmpty($post->editorial_badge['bn']);
            $this->assertNotNull($post->category);
            $this->assertTrue($post->mediaAsset->posts->contains($post));
        }
        $this->assertSame(3, Category::where('slug', 'facility')->firstOrFail()->galleryImages()->count());
        $this->assertSame(3, Post::where('on_homepage', true)->count());
        $this->assertSame(3, GalleryImage::where('on_homepage', true)->count());
    }

    public function test_repeat_seed_preserves_edits_drafts_visibility_passwords_and_deletions(): void
    {
        $post = Post::where('slug', 'pcos')->firstOrFail();
        $post->update(['title' => ['en' => 'Owner edit', 'bn' => ''], 'status' => 'draft', 'on_homepage' => false]);
        SiteSetting::firstOrFail()->update(['address' => ['en' => 'Edited address', 'bn' => null]]);
        NavigationItem::where('key', 'header:about')->firstOrFail()->update(['is_visible' => false]);
        GalleryImage::where('key', 'demo-gallery-1')->delete();
        $user = User::factory()->create();
        $password = $user->password;
        $this->seed(DatabaseSeeder::class);
        $this->assertSame('Owner edit', $post->fresh()->title['en']);
        $this->assertSame('draft', $post->fresh()->status);
        $this->assertSame('Edited address', SiteSetting::firstOrFail()->address['en']);
        $this->assertFalse(NavigationItem::where('key', 'header:about')->firstOrFail()->is_visible);
        $this->assertSame(7, GalleryImage::count());
        $this->assertSame($password, $user->fresh()->password);
        $result = app(InitialWebsiteImporter::class)->import(repairMissing: true);
        $this->assertSame(1, array_sum($result['created']));
        $this->assertSame(8, GalleryImage::count());
        $this->assertSame('draft', $post->fresh()->status);
        $this->assertSame('Owner edit', $post->fresh()->title['en']);
    }

    public function test_every_public_page_and_article_is_readable_in_both_languages(): void
    {
        foreach (['en', 'bn'] as $locale) {
            foreach (['/', '/about', '/services', '/diagnostics', '/blog', '/gallery', '/contact'] as $url) {
                $this->get($url.'?locale='.$locale)->assertOk()->assertSee('lang="'.$locale.'"', false)->assertDontSee('assets/app.js')->assertDontSee('/build/');
            }
            foreach (Post::all() as $post) {
                $this->get('/blog/'.$post->slug.'?locale='.$locale)->assertOk()->assertSee($post->title[$locale])->assertSee($post->body[$locale][0]);
            }
        }
        $this->get('/blog/missing-slug')->assertNotFound();
        Post::where('slug', 'pcos')->firstOrFail()->update(['status' => 'draft']);
        $this->get('/blog/pcos')->assertNotFound();
    }

    public function test_model_validation_rejects_unknown_fields_bad_targets_and_missing_english(): void
    {
        $registry = app(ContentRegistry::class);
        foreach ([['key' => 'invented', 'value' => ['en' => 'Text']], ['key' => 'home.eyebrow', 'value' => ['en' => '', 'bn' => 'বাংলা']], ['key' => 'home.primary_target', 'value' => ['en' => 'javascript:alert(1)', 'bn' => null]], ['key' => 'home.eyebrow', 'value' => ['en' => 'Text'], 'unknown' => 'bad']] as $data) {
            try {
                $registry->validate('ContentField', $data);
                $this->fail('Invalid content was accepted.');
            } catch (ValidationException $exception) {
                $this->assertNotEmpty($exception->errors());
            }
        }
        $this->expectException(QueryException::class);
        $copy = Post::firstOrFail()->replicate();
        $copy->save(); // Database-enforced unique slug.
    }

    public function test_inquiries_save_before_success_with_separate_modes_and_snapshots(): void
    {
        $option = ConsultationOption::where('key', 'obstetrics')->firstOrFail();
        $this->post('/inquiries', ['locale' => 'en', 'kind' => 'appointment', 'name' => 'Test patient', 'phone' => 'Test phone', 'consultation_option_id' => $option->id, 'message' => 'Test message'])->assertRedirect('/contact?locale=en&mode=appointment')->assertSessionHas('inquiry_success');
        $inquiry = Inquiry::firstOrFail();
        $this->assertSame($option->label, $inquiry->consultation_label);
        $this->assertTrue($option->inquiries->contains($inquiry));
        $this->assertSame('new', $inquiry->status);
        $this->assertArrayNotHasKey('name', $inquiry->toArray());
        $this->post('/inquiries', ['locale' => 'en', 'kind' => 'contact', 'name' => 'Test contact', 'phone' => 'Test phone', 'consultation_option_id' => $option->id])->assertRedirect('/contact?locale=en&mode=contact');
        $this->assertNull(Inquiry::where('kind', 'contact')->firstOrFail()->consultation_option_id);
        $this->post('/inquiries', ['kind' => 'appointment', 'name' => ''])->assertSessionHasErrors();
        $this->get('/contact?mode=contact&locale=bn')->assertOk()->assertDontSee('name="consultation_option_id"', false);
        $this->get('/gallery?category=facility')->assertOk();
        $this->assertSame(2, Inquiry::count());
        $this->assertNotSame($inquiry->public_reference, Inquiry::where('kind', 'contact')->firstOrFail()->public_reference);
    }

    public function test_admin_authorization_is_explicit_and_mass_assignment_cannot_elevate_users(): void
    {
        $user = User::factory()->create();
        $this->assertFalse(Gate::forUser($user)->allows('access-admin'));
        $user->forceFill(['is_admin' => true])->save();
        $this->assertTrue(Gate::forUser($user)->allows('access-admin'));
    }
}
