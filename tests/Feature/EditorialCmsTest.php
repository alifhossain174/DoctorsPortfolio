<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\ContentField;
use App\Models\GalleryImage;
use App\Models\MediaAsset;
use App\Models\Post;
use App\Models\PostSlugRedirect;
use App\Models\User;
use App\Services\InitialWebsiteImporter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class EditorialCmsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        if (DB::connection()->getDatabaseName() !== 'doctor_portfolio_test') {
            throw new \RuntimeException('Use the isolated doctor_portfolio_test database.');
        }
        DB::beginTransaction();
        $this->beforeApplicationDestroyed(fn () => DB::rollBack());
        app(InitialWebsiteImporter::class)->import();
        $admin = User::create(['name' => 'Test article editor', 'email' => Str::uuid().'@example.test', 'password' => bin2hex(random_bytes(24))]);
        $admin->forceFill(['is_admin' => true])->save();
        $this->actingAs($admin);
    }

    private function postInput(string $slug = 'owner-article'): array
    {
        return ['slug' => $slug, 'title' => ['en' => 'Owner title '.$slug, 'bn' => 'মালিকের শিরোনাম '.$slug], 'excerpt' => ['en' => 'Plain summary', 'bn' => 'সাধারণ সারাংশ'],
            'body' => ['en' => ['First English paragraph', 'Second English paragraph'], 'bn' => ['প্রথম বাংলা অনুচ্ছেদ']],
            'category_id' => Category::where('slug', 'clinical')->firstOrFail()->id, 'editorial_badge' => ['en' => 'TOPIC', 'bn' => 'বিষয়'],
            'media_asset_id' => MediaAsset::firstOrFail()->id, 'image_alt' => ['en' => 'Source photograph', 'bn' => 'মূল ছবি'], 'status' => 'draft', 'on_homepage' => '0', 'sort_order' => 20,
            'meta_title' => ['en' => 'Owner meta title', 'bn' => 'মালিকের মেটা শিরোনাম'], 'meta_description' => ['en' => 'Owner description', 'bn' => 'মালিকের বিবরণ'], 'og_media_asset_id' => null];
    }

    private function createPost(string $slug = 'owner-article', array $changes = []): Post
    {
        $this->post('/admin/articles', ['post' => array_replace($this->postInput($slug), $changes)])->assertSessionHasNoErrors();

        return Post::where('slug', $slug)->firstOrFail();
    }

    private function cardSlugs(string $html): array
    {
        $dom = new \DOMDocument;
        @$dom->loadHTML('<?xml encoding="UTF-8">'.$html);
        $result = [];
        foreach ((new \DOMXPath($dom))->query('//article[contains(@class,"article-card")]//h3/a') as $link) {
            $result[] = basename(parse_url($link->getAttribute('href'), PHP_URL_PATH));
        }

        return $result;
    }

    public function test_original_articles_categories_and_all_editor_fields_remain_available(): void
    {
        $this->assertSame(['pregnancy-care', 'pcos', 'fertility-first-visit', 'menopause'], Post::orderBy('sort_order')->pluck('slug')->all());
        $this->assertSame(['clinical', 'wellness', 'facility'], Category::orderBy('sort_order')->pluck('slug')->all());
        foreach (['clinical', 'wellness'] as $slug) {
            $category = Category::where('slug', $slug)->firstOrFail();
            $this->assertTrue($category->for_blog);
            $this->assertTrue($category->for_gallery);
        }
        $facility = Category::where('slug', 'facility')->firstOrFail();
        $this->assertFalse($facility->for_blog);
        $this->assertTrue($facility->for_gallery);
        $this->get('/admin/categories')->assertOk()->assertSee('clinical');
        $this->get('/admin/categories/create')->assertOk()->assertSee('category[label][bn]', false);
        $this->get('/admin/articles')->assertOk()->assertSee('Private preview');
        $this->get('/admin/articles/create')->assertOk()->assertSee('name="post[body][en][]"', false)->assertSee('name="post[body][bn][]"', false)->assertSee('data-paragraph-up', false);
        foreach (Post::with(['category', 'mediaAsset'])->get() as $post) {
            $response = $this->get('/admin/articles/'.$post->id.'/edit')->assertOk()->assertSee('confirm_delete');
            foreach ($post->getFillable() as $field) {
                $response->assertSee('name="post['.$field.']', false);
            }
            $this->assertCount(2, $post->body['en']);
            $this->assertCount(2, $post->body['bn']);
            foreach (['en', 'bn'] as $locale) {
                $this->get('/blog/'.$post->slug.'?locale='.$locale)->assertOk()->assertSee($post->title[$locale])->assertSee($post->editorial_badge[$locale])->assertSee($post->body[$locale][0]);
            }
        }
        $this->assertSame(0, PostSlugRedirect::count());
    }

    public function test_category_crud_stable_slug_bilingual_updates_order_and_applicability(): void
    {
        $input = ['slug' => 'owner-category', 'label' => ['en' => 'Owner category', 'bn' => 'মালিকের বিভাগ'], 'sort_order' => 0, 'for_blog' => '1', 'for_gallery' => '0'];
        $this->post('/admin/categories', ['category' => $input])->assertSessionHasNoErrors();
        $category = Category::where('slug', 'owner-category')->firstOrFail();
        $this->assertFalse($category->for_gallery);
        $this->get('/blog?locale=bn')->assertSee('মালিকের বিভাগ');
        $this->get('/gallery?locale=bn')->assertDontSee('মালিকের বিভাগ');
        $this->post('/admin/categories', ['category' => $input])->assertSessionHasErrors('category.slug');
        $this->put('/admin/categories/'.$category->id, ['category' => ['slug' => 'changed']])->assertSessionHasErrors('category');
        $this->put('/admin/categories/'.$category->id, ['category' => ['label' => ['en' => 'English-only category'], 'sort_order' => 7]])->assertSessionHasNoErrors();
        $this->assertSame('মালিকের বিভাগ', $category->fresh()->label['bn']);
        $this->assertSame(7, $category->fresh()->sort_order);
        $this->put('/admin/categories/'.$category->id, ['category' => ['label' => ['bn' => '']]])->assertSessionHasNoErrors();
        $this->get('/blog?locale=bn')->assertSee('English-only category');
        $this->put('/admin/categories/'.$category->id, ['category' => ['for_blog' => '0', 'for_gallery' => '0']])->assertSessionHasErrors('category.for_blog');
        $this->put('/admin/categories/'.$category->id, ['category' => ['label' => ['fr' => 'No']]])->assertSessionHasErrors('category.label');
        $this->delete('/admin/categories/'.$category->id)->assertSessionHasErrors('confirm_delete');
        $this->delete('/admin/categories/'.$category->id, ['confirm_delete' => '1'])->assertSessionHasNoErrors();
        $this->assertNull($category->fresh());
    }

    public function test_category_deletion_and_applicability_require_explicit_atomic_reassignment(): void
    {
        $clinical = Category::where('slug', 'clinical')->firstOrFail();
        $wellness = Category::where('slug', 'wellness')->firstOrFail();
        $facility = Category::where('slug', 'facility')->firstOrFail();
        $postIds = $clinical->posts()->pluck('id')->all();
        $galleryIds = $clinical->galleryImages()->pluck('id')->all();
        $this->delete('/admin/categories/'.$clinical->id, ['confirm_delete' => '1'])->assertSessionHasErrors('category');
        $this->put('/admin/categories/'.$clinical->id, ['category' => ['for_blog' => '0']])->assertSessionHasErrors('category');
        $this->put('/admin/categories/'.$clinical->id, ['category' => ['for_gallery' => '0']])->assertSessionHasErrors('category');
        $this->put('/admin/categories/'.$clinical->id.'/reassign', ['blog_category_id' => $wellness->id, 'gallery_category_id' => $clinical->id, 'confirm_reassign' => '1'])->assertSessionHasErrors('gallery_category_id');
        $this->assertSame(count($postIds), Post::whereIn('id', $postIds)->where('category_id', $clinical->id)->count());
        $this->assertSame(count($galleryIds), GalleryImage::whereIn('id', $galleryIds)->where('category_id', $clinical->id)->count());
        $this->put('/admin/categories/'.$clinical->id.'/reassign', ['blog_category_id' => $wellness->id, 'gallery_category_id' => $facility->id])->assertSessionHasErrors('confirm_reassign');
        $this->put('/admin/categories/'.$clinical->id.'/reassign', ['blog_category_id' => $wellness->id, 'gallery_category_id' => $facility->id, 'confirm_reassign' => '1'])->assertSessionHasNoErrors();
        $this->assertSame(count($postIds), Post::whereIn('id', $postIds)->where('category_id', $wellness->id)->count());
        $this->assertSame(count($galleryIds), GalleryImage::whereIn('id', $galleryIds)->where('category_id', $facility->id)->count());
        $this->assertNotNull($clinical->fresh());
        $this->delete('/admin/categories/'.$clinical->id, ['confirm_delete' => '1'])->assertSessionHasNoErrors();
        $this->assertSame(4, Post::count());
        $this->assertSame(8, GalleryImage::count());
        $this->assertTrue(app(InitialWebsiteImporter::class)->import()['skipped']);
        $this->assertNull(Category::where('slug', 'clinical')->first());
    }

    public function test_article_authoring_uses_independent_plain_paragraph_arrays_and_escaped_output(): void
    {
        $post = $this->createPost('bilingual-authoring', ['body' => ['en' => ['  First paragraph  ', '', '<script>alert("text")</script>'], 'bn' => ['প্রথম অনুচ্ছেদ', 'দ্বিতীয় অনুচ্ছেদ', 'তৃতীয় অনুচ্ছেদ']]]);
        $this->assertSame(['First paragraph', '<script>alert("text")</script>'], $post->body['en']);
        $this->assertCount(3, $post->body['bn']);
        $bn = $post->body['bn'];
        $this->put('/admin/articles/'.$post->id, ['post' => ['body' => ['en' => ['<img src=x onerror=evil()>', 'Changed English']]]])->assertSessionHasNoErrors();
        $this->assertSame($bn, $post->fresh()->body['bn']);
        $this->get('/admin/articles/'.$post->id.'/preview?locale=en')->assertOk()->assertSee('&lt;img src=x onerror=evil()&gt;', false)->assertDontSee('<img src=x onerror=evil()>', false)->assertSee('<p>Changed English</p>', false);
        $this->put('/admin/articles/'.$post->id, ['post' => ['body' => ['bn' => ['']]]])->assertSessionHasNoErrors();
        $this->assertNull($post->fresh()->body['bn']);
        $this->get('/admin/articles/'.$post->id.'/preview?locale=bn')->assertSee('Changed English');
        $this->get('/admin/articles/'.$post->id.'/edit')->assertSee('Bangla missing')->assertSee('name="post[body][bn][]"', false);
        $this->put('/admin/articles/'.$post->id, ['post' => ['body' => ['bn' => ['পুনরুদ্ধার করা অনুচ্ছেদ']]]])->assertSessionHasNoErrors();
        $this->put('/admin/articles/'.$post->id, ['post' => ['title' => ['en' => 'Changed title']]])->assertSessionHasNoErrors();
        $this->assertSame('bilingual-authoring', $post->fresh()->slug);
        $this->assertSame(['পুনরুদ্ধার করা অনুচ্ছেদ'], $post->fresh()->body['bn']);
        $this->put('/admin/articles/'.$post->id, ['post' => ['body' => ['en' => []]]])->assertSessionHasErrors('post.body.en');
        $this->put('/admin/articles/'.$post->id, ['post' => ['body' => ['en' => [['html' => 'bad']]]]])->assertSessionHasErrors('post.body.en.0');
        $this->put('/admin/articles/'.$post->id, ['post' => ['body' => ['fr' => ['No']]]])->assertSessionHasErrors('post.body');
        $this->put('/admin/articles/'.$post->id, ['post' => ['body' => ['en' => array_fill(0, 101, 'Too many')]]])->assertSessionHasErrors('post.body.en');
    }

    public function test_published_slug_changes_keep_direct_redirects_and_reserve_other_article_history(): void
    {
        $post = Post::where('slug', 'pcos')->firstOrFail();
        $this->put('/admin/articles/'.$post->id, ['post' => ['slug' => 'pcos-new']])->assertSessionHasErrors('confirm_slug_change');
        $this->assertSame('pcos', $post->fresh()->slug);
        $this->put('/admin/articles/'.$post->id, ['post' => ['slug' => 'pcos-new'], 'confirm_slug_change' => '1'])->assertSessionHasNoErrors();
        $this->assertSame($post->id, PostSlugRedirect::where('slug', 'pcos')->firstOrFail()->post_id);
        $this->get('/blog/pcos?locale=bn&redirect=https://example.test')->assertStatus(301)->assertRedirect('/blog/pcos-new?locale=bn');
        $this->get('/article.html?id=pcos&locale=en')->assertStatus(301)->assertRedirect('/blog/pcos-new?locale=en');
        $this->put('/admin/articles/'.$post->id, ['post' => ['slug' => 'pcos-final'], 'confirm_slug_change' => '1'])->assertSessionHasNoErrors();
        $this->get('/blog/pcos?locale=en')->assertRedirect('/blog/pcos-final?locale=en');
        $this->get('/blog/pcos-new?locale=en')->assertRedirect('/blog/pcos-final?locale=en');
        $repair = app(InitialWebsiteImporter::class)->import(true);
        $this->assertSame(0, $repair['created']['posts']);
        $this->assertSame(4, Post::count());
        $this->assertSame('pcos-final', $post->fresh()->slug);
        $this->post('/admin/articles', ['post' => $this->postInput('pcos')])->assertSessionHasErrors('post.slug');
        $this->put('/admin/articles/'.Post::where('slug', 'menopause')->firstOrFail()->id, ['post' => ['slug' => 'pcos'], 'confirm_slug_change' => '1'])->assertSessionHasErrors('post.slug');
        $this->put('/admin/articles/'.$post->id, ['post' => ['slug' => 'pcos'], 'confirm_slug_change' => '1'])->assertSessionHasNoErrors();
        $this->assertNull(PostSlugRedirect::where('slug', 'pcos')->first());
        $this->get('/blog/pcos')->assertOk();
        $this->get('/blog/pcos-final?locale=bn')->assertRedirect('/blog/pcos?locale=bn');
        $this->patch('/admin/articles/'.$post->id.'/publication', ['status' => 'draft', 'confirm_publication' => '1'])->assertSessionHasNoErrors();
        $this->get('/blog/pcos')->assertNotFound();
        $this->get('/blog/pcos-final')->assertNotFound();
        $this->get('/article.html?id=pcos-final')->assertNotFound();
    }

    public function test_draft_private_preview_guest_denial_and_request_time_future_publication(): void
    {
        $post = $this->createPost('private-draft');
        $this->get('/blog/private-draft')->assertNotFound();
        $this->get('/blog')->assertDontSee('Owner title private-draft');
        $preview = $this->get('/admin/articles/'.$post->id.'/preview?locale=bn')->assertOk()->assertSee('lang="bn"', false)->assertSee('Private admin preview')->assertSee('noindex, nofollow, nosnippet')->assertHeader('X-Robots-Tag', 'noindex, nofollow, nosnippet')->assertSee('/admin/articles/'.$post->id.'/preview?locale=en', false);
        $this->assertStringContainsString('no-store', $preview->headers->get('Cache-Control'));
        $this->app['auth']->guard()->logout();
        $this->get('/admin/articles/'.$post->id.'/preview')->assertRedirect('/admin/login')->assertDontSee('Owner title private-draft');
        $this->flushSession();
        $user = User::create(['name' => 'Non-admin preview', 'email' => Str::uuid().'@example.test', 'password' => bin2hex(random_bytes(24))]);
        $this->actingAs($user);
        $this->get('/admin/articles/'.$post->id.'/preview')->assertForbidden();
        $admin = User::where('is_admin', true)->firstOrFail();
        $this->flushSession();
        $this->actingAs($admin);
        $future = now()->addHour()->startOfSecond();
        $this->put('/admin/articles/'.$post->id, ['post' => ['status' => 'published', 'published_at' => $future->format('Y-m-d\TH:i:s'), 'on_homepage' => '1']])->assertRedirect('/admin/articles/'.$post->id.'/edit')->assertSessionHasNoErrors();
        $this->get('/blog/private-draft')->assertNotFound();
        $this->get('/')->assertDontSee('Owner title private-draft');
        $this->get('/admin/articles?status=future')->assertSee('Owner title private-draft');
        $this->travelTo($future->addSecond());
        $this->get('/blog/private-draft?locale=bn')->assertOk()->assertSee('মালিকের শিরোনাম private-draft')->assertSee('বিষয়');
        $this->travelBack();
    }

    public function test_publication_controls_require_confirmation_and_preserve_the_badge(): void
    {
        $post = $this->createPost('publish-now', ['editorial_badge' => ['en' => 'PREGNANCY', 'bn' => 'গর্ভাবস্থা']]);
        $this->assertNull($post->published_at);
        $this->patch('/admin/articles/'.$post->id.'/publication', ['status' => 'published'])->assertSessionHasErrors('confirm_publication');
        $this->patch('/admin/articles/'.$post->id.'/publication', ['status' => 'published', 'confirm_publication' => '1'])->assertSessionHasNoErrors();
        $this->assertTrue($post->fresh()->published_at->lte(now()));
        $this->get('/blog/publish-now?locale=en')->assertSee('<span class="eyebrow">PREGNANCY</span>', false)->assertDontSee($post->fresh()->published_at->format('Y-m-d'));
        $publishedAt = $post->fresh()->published_at;
        $this->patch('/admin/articles/'.$post->id.'/publication', ['status' => 'draft', 'confirm_publication' => '1'])->assertSessionHasNoErrors();
        $this->assertTrue($post->fresh()->published_at->equalTo($publishedAt));
        $this->get('/blog/publish-now')->assertNotFound();
    }

    public function test_blog_filters_run_before_pagination_and_preserve_only_valid_locale_queries(): void
    {
        config(['editorial.blog_per_page' => 2]);
        $first = $this->createPost('clinical-extra-one', ['status' => 'published', 'sort_order' => 10]);
        $second = $this->createPost('clinical-extra-two', ['status' => 'published', 'sort_order' => 11]);
        $last = $this->createPost('clinical-extra-three', ['status' => 'published', 'sort_order' => 12]);
        $this->createPost('clinical-hidden', ['sort_order' => 12]);
        $this->createPost('clinical-future', ['status' => 'published', 'published_at' => now()->addDay()->format('Y-m-d H:i:s')]);
        $one = $this->get('/blog?category=clinical&locale=bn&evil=discard')->assertOk();
        $this->assertSame(['pcos', 'fertility-first-visit'], $this->cardSlugs($one->getContent()));
        $one->assertSee('page=2', false)->assertSee('category=clinical', false)->assertSee('locale=bn', false)->assertDontSee('evil=discard')->assertDontSee('admin-D', false)->assertDontSee('data-category="wellness"', false);
        $two = $this->get('/blog?category=clinical&locale=bn&page=2')->assertOk()->assertSee($first->title['bn'])->assertSee('/blog?category=clinical&amp;page=2&amp;locale=en', false);
        $this->assertSame([$first->slug, $second->slug], $this->cardSlugs($two->getContent()));
        $three = $this->get('/blog?category=clinical&locale=bn&page=3')->assertOk();
        $this->assertSame([$last->slug], $this->cardSlugs($three->getContent()));
        $three->assertDontSee('clinical-hidden')->assertDontSee('clinical-future');
        $this->get('/blog?category=clinical&page=99')->assertOk()->assertDontSee('class="card article-card"', false);
        foreach (['facility', 'unknown', '', 'javascript:bad'] as $category) {
            $this->get('/blog?category='.urlencode($category))->assertNotFound();
        }
        $this->get('/blog?category[]=clinical')->assertNotFound();
        $this->get('/blog.html?category=facility')->assertNotFound();
        $this->get('/blog?category=wellness&locale=en')->assertSee('data-category="wellness"', false)->assertDontSee('data-category="clinical"', false);
    }

    public function test_homepage_selection_order_visibility_and_configured_count_remain_database_owned(): void
    {
        $this->assertSame(['pregnancy-care', 'pcos', 'fertility-first-visit'], $this->cardSlugs($this->get('/')->getContent()));
        $post = $this->createPost('home-first', ['status' => 'published', 'sort_order' => 0, 'on_homepage' => '1']);
        $pcos = Post::where('slug', 'pcos')->firstOrFail();
        $this->put('/admin/articles/'.$pcos->id, ['post' => ['on_homepage' => '0']])->assertSessionHasNoErrors();
        $this->assertSame(['pregnancy-care', 'home-first', 'fertility-first-visit'], $this->cardSlugs($this->get('/')->getContent()));
        $count = ContentField::where('key', 'home.article_count')->firstOrFail();
        $this->put('/admin/pages/home', ['fields' => [$count->id => ['value' => 1]]])->assertSessionHasNoErrors();
        $this->assertSame(['pregnancy-care'], $this->cardSlugs($this->get('/')->getContent()));
        $this->put('/admin/articles/'.$post->id, ['post' => ['sort_order' => 9]])->assertSessionHasNoErrors();
        $this->assertSame(9, $post->fresh()->sort_order);
        $this->get('/blog')->assertSee('Owner title home-first')->assertSee($pcos->title['en']);
    }

    public function test_metadata_image_alt_and_og_reference_use_existing_media_protection(): void
    {
        $post = $this->createPost('meta-check', ['status' => 'published']);
        $asset = MediaAsset::where('id', '!=', $post->media_asset_id)->firstOrFail();
        $other = MediaAsset::where('id', '!=', $asset->id)->firstOrFail();
        $this->put('/admin/articles/'.$post->id, ['post' => ['og_media_asset_id' => $asset->id, 'image_alt' => ['en' => 'Changed source alt', 'bn' => 'ছবির পরিবর্তিত বিবরণ']]])->assertSessionHasNoErrors();
        $this->assertSame($asset->id, $post->fresh()->ogImage->id);
        $this->assertTrue($asset->ogPosts()->whereKey($post->id)->exists());
        $this->get('/blog/meta-check?locale=bn')->assertSee('<title>মালিকের মেটা শিরোনাম</title>', false)->assertSee('content="মালিকের বিবরণ"', false)->assertSee('alt="ছবির পরিবর্তিত বিবরণ"', false);
        $this->get('/admin/media/'.$asset->id.'/edit')->assertSee('Article OG image');
        $this->delete('/admin/media/'.$asset->id, ['confirm_delete' => '1'])->assertSessionHasErrors('media');
        $this->put('/admin/media/'.$asset->id.'/references', ['replacement_id' => $other->id, 'confirm_replace' => '1'])->assertSessionHasNoErrors();
        $this->assertSame($other->id, $post->fresh()->og_media_asset_id);
        $this->put('/admin/articles/'.$post->id, ['post' => ['meta_title' => ['en' => str_repeat('x', 201)]]])->assertSessionHasErrors('post.meta_title.en');
        $this->put('/admin/articles/'.$post->id, ['post' => ['meta_description' => ['en' => str_repeat('x', 501)]]])->assertSessionHasErrors('post.meta_description.en');
        $this->put('/admin/articles/'.$post->id, ['post' => ['meta_title' => ['bn' => '']]])->assertSessionHasNoErrors();
        $this->get('/blog/meta-check?locale=bn')->assertSee('<title>Owner meta title</title>', false);
        $this->put('/admin/articles/'.$post->id, ['post' => ['meta_title' => ['en' => '', 'bn' => '']]])->assertSessionHasNoErrors();
        $this->get('/blog/meta-check?locale=bn')->assertSee($post->title['bn']);
    }

    public function test_article_validation_and_failed_slug_history_write_are_atomic(): void
    {
        $post = Post::where('slug', 'pcos')->firstOrFail();
        foreach (['../bad', 'Bad Slug', 'javascript:bad'] as $slug) {
            $this->put('/admin/articles/'.$post->id, ['post' => ['slug' => $slug], 'confirm_slug_change' => '1'])->assertSessionHasErrors('post.slug');
        }
        $facility = Category::where('slug', 'facility')->firstOrFail();
        $this->put('/admin/articles/'.$post->id, ['post' => ['category_id' => $facility->id]])->assertSessionHasErrors('post.category_id');
        $this->put('/admin/articles/'.$post->id, ['post' => ['og_media_asset_id' => 999999999]])->assertSessionHasErrors('post.og_media_asset_id');
        $this->put('/admin/articles/'.$post->id, ['post' => ['title' => ['fr' => 'No']]])->assertSessionHasErrors('post.title');
        $this->put('/admin/articles/'.$post->id, ['post' => ['is_admin' => '1']])->assertSessionHasErrors('post');
        $this->put('/admin/articles/'.$post->id, ['post' => ['published_at' => 'not-a-date']])->assertSessionHasErrors('post.published_at');
        PostSlugRedirect::creating(fn () => throw new \RuntimeException('Simulated redirect failure'));
        $this->put('/admin/articles/'.$post->id, ['post' => ['slug' => 'must-roll-back', 'title' => ['en' => 'Must also roll back']], 'confirm_slug_change' => '1'])->assertSessionHasErrors('post');
        $this->assertSame('pcos', $post->fresh()->slug);
        $this->assertSame($post->title, $post->fresh()->title);
        $this->assertSame(0, PostSlugRedirect::count());
    }

    public function test_admin_lists_paginate_search_and_filter_with_plain_tailwind_views(): void
    {
        config(['editorial.admin_per_page' => 2]);
        $this->createPost('list-draft');
        $this->createPost('list-future', ['status' => 'published', 'published_at' => now()->addDay()->format('Y-m-d H:i:s')]);
        $this->createPost('list-public', ['status' => 'published']);
        $this->get('/admin/articles')->assertOk()->assertSee('page=2', false)->assertSee('Pagination Navigation', false);
        $this->get('/admin/articles?q=list-draft&status=draft')->assertSee('Owner title list-draft')->assertDontSee('Owner title list-future');
        $clinical = Category::where('slug', 'clinical')->firstOrFail();
        $this->get('/admin/articles?category='.$clinical->id.'&status=published')->assertSee('page=2', false)->assertSee('status=published', false)->assertDontSee('Owner title list-future');
        $this->get('/admin/categories?scope=gallery')->assertSee('page=2', false)->assertSee('scope=gallery', false);
        $this->get('/admin/categories?q=facility')->assertSee('facility')->assertDontSee('clinical</p>', false);
    }

    public function test_delete_preserves_shared_media_and_ordinary_import_does_not_restore_originals(): void
    {
        $post = Post::where('slug', 'pcos')->firstOrFail();
        $mediaId = $post->media_asset_id;
        $this->put('/admin/articles/'.$post->id, ['post' => ['slug' => 'deleted-article'], 'confirm_slug_change' => '1'])->assertSessionHasNoErrors();
        $this->delete('/admin/articles/'.$post->id)->assertSessionHasErrors('confirm_delete');
        $this->delete('/admin/articles/'.$post->id, ['confirm_delete' => '1'])->assertSessionHasNoErrors();
        $this->assertNull($post->fresh());
        $this->assertNotNull(MediaAsset::find($mediaId));
        $this->assertSame(9, MediaAsset::count());
        $this->assertSame(0, PostSlugRedirect::count());
        $this->get('/blog/pcos')->assertNotFound();
        $this->get('/blog/deleted-article')->assertNotFound();
        $this->assertTrue(app(InitialWebsiteImporter::class)->import()['skipped']);
        $this->assertSame(3, Post::count());
    }

    public function test_all_editor_actions_are_private_and_preview_cannot_be_used_by_non_admins(): void
    {
        $post = Post::firstOrFail();
        $category = Category::firstOrFail();
        $actions = [['get', '/admin/articles'], ['get', '/admin/articles/create'], ['get', '/admin/articles/'.$post->id.'/edit'], ['get', '/admin/articles/'.$post->id.'/preview'], ['post', '/admin/articles'], ['put', '/admin/articles/'.$post->id], ['patch', '/admin/articles/'.$post->id.'/publication'], ['delete', '/admin/articles/'.$post->id], ['get', '/admin/categories'], ['get', '/admin/categories/create'], ['get', '/admin/categories/'.$category->id.'/edit'], ['post', '/admin/categories'], ['put', '/admin/categories/'.$category->id], ['put', '/admin/categories/'.$category->id.'/reassign'], ['delete', '/admin/categories/'.$category->id]];
        $this->app['auth']->guard()->logout();
        foreach ($actions as [$method, $url]) {
            $this->{$method}($url)->assertRedirect('/admin/login');
        }
        $this->flushSession();
        $user = User::create(['name' => 'Non-admin', 'email' => Str::uuid().'@example.test', 'password' => bin2hex(random_bytes(24))]);
        $this->actingAs($user);
        foreach ($actions as [$method, $url]) {
            $this->{$method}($url)->assertForbidden();
        }
    }
}
