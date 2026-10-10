<?php

namespace Tests\Feature;

use App\Models\MediaAsset;
use App\Models\Post;
use App\Models\User;
use App\Services\InitialWebsiteImporter;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Mockery;
use Tests\TestCase;

class MediaManagerTest extends TestCase
{
    private array $temporaryFiles = [];

    protected function setUp(): void
    {
        parent::setUp();
        if (DB::connection()->getDatabaseName() !== 'doctor_portfolio_testing') {
            throw new \RuntimeException('Use doctor_portfolio_testing.');
        }
        DB::beginTransaction();
        $this->beforeApplicationDestroyed(fn () => DB::rollBack());
        $this->beforeApplicationDestroyed(function () {
            foreach ($this->temporaryFiles as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
        });
        $this->fakeMediaDisk();
        app(InitialWebsiteImporter::class)->import();
        $user = User::create(['name' => 'Test editor', 'email' => Str::uuid().'@example.test', 'password' => bin2hex(random_bytes(24))]);
        $user->forceFill(['is_admin' => true])->save();
        $this->actingAs($user);
    }

    private function image(string $format = 'png', ?string $name = null, int $width = 12, int $height = 8): UploadedFile
    {
        $path = tempnam(storage_path('framework/testing'), 'raster-');
        $this->temporaryFiles[] = $path;
        $image = imagecreatetruecolor($width, $height);
        match ($format) {
            'jpg' => imagejpeg($image, $path), 'webp' => imagewebp($image, $path), default => imagepng($image, $path)
        };
        imagedestroy($image);

        return new UploadedFile($path, $name ?? 'test.'.$format, null, null, true);
    }

    private function fakeMediaDisk(string $url = 'https://example.test/uploads'): void
    {
        // Windows may retain files during cleanup of a reused fake directory.
        // A unique Laravel test disk keeps each test independent, even across reruns.
        $disk = Storage::fake('media-test-'.bin2hex(random_bytes(12)), ['url' => $url, 'throw' => true]);
        Storage::set('public-media', $disk);
        $this->beforeApplicationDestroyed(fn () => $disk->delete($disk->allFiles()));
    }

    private function text(): array
    {
        return ['title' => ['en' => ' Test image ', 'bn' => ' পরীক্ষার ছবি '], 'alt_text' => ['en' => ' Plain image ', 'bn' => ' সাধারণ ছবি '], 'caption' => ['en' => ' Caption ', 'bn' => ' ক্যাপশন '], 'provenance_note' => ['en' => ' Test fixture ', 'bn' => ' পরীক্ষার নমুনা ']];
    }

    private function upload(): MediaAsset
    {
        $this->post(route('admin.media.store'), [...$this->text(), 'image' => $this->image()])->assertSessionHasNoErrors();

        return MediaAsset::whereNotNull('path')->latest('id')->firstOrFail();
    }

    public function test_permitted_rasters_are_saved_with_random_paths_metadata_and_local_urls(): void
    {
        foreach (['jpg', 'png', 'webp'] as $format) {
            $this->post(route('admin.media.store'), [...$this->text(), 'image' => $this->image($format, '../../owner-image.'.$format)])->assertRedirect()->assertSessionHasNoErrors();
            $asset = MediaAsset::whereNotNull('path')->latest('id')->firstOrFail();
            $this->assertMatchesRegularExpression('~^images/\d{4}/\d{2}/[a-f0-9]{32}\.(jpg|png|webp)$~', $asset->path);
            $this->assertSame('public-media', $asset->disk);
            $this->assertSame('https://example.test/uploads/'.$asset->path, $asset->url());
            $this->assertSame('Test image', $asset->title['en']);
            $this->assertSame('পরীক্ষার ছবি', $asset->title['bn']);
            $this->assertSame('ক্যাপশন', $asset->caption['bn']);
            $this->assertSame([12, 8], [$asset->width, $asset->height]);
            $this->assertNull($asset->source_url);
            $this->assertStringNotContainsString('..', $asset->path);
            Storage::disk('public-media')->assertExists($asset->path);
        }
        Storage::disk('public-media')->assertExists('.htaccess');
        $this->assertSame(12, MediaAsset::count());
        $this->get(route('admin.media.index'))->assertOk()->assertSee('Local')->assertSee('External');
        $this->get(route('admin.media.edit', $asset))->assertOk()->assertSee($asset->url(), false)->assertSee('Copy URL');
    }

    public function test_svg_executables_html_mismatched_extensions_and_broken_signatures_are_rejected(): void
    {
        $bad = [UploadedFile::fake()->createWithContent('image.svg', '<svg></svg>'), UploadedFile::fake()->createWithContent('image.php', '<?php echo 1;'), UploadedFile::fake()->createWithContent('image.html', '<html>bad</html>'), UploadedFile::fake()->createWithContent('image.jpg', '<?php echo 1;'), UploadedFile::fake()->createWithContent('image.png', "\x89PNG\r\n\x1A\nnot an image"), $this->image('png', 'disguised.jpg'), $this->image('png', 'double.php.png.php'), $this->image('png', 'image.gif')];
        foreach ($bad as $file) {
            $this->post(route('admin.media.store'), [...$this->text(), 'image' => $file])->assertSessionHasErrors('image');
        }
        $this->assertSame(9, MediaAsset::count());
        $this->assertSame([], Storage::disk('public-media')->allFiles());
    }

    public function test_size_dimension_language_and_server_owned_path_limits_are_enforced(): void
    {
        $oversize = $this->image();
        file_put_contents($oversize->getPathname(), str_repeat('0', 5242881));
        $this->post(route('admin.media.store'), [...$this->text(), 'image' => $oversize])->assertSessionHasErrors('image');
        $this->post(route('admin.media.store'), [...$this->text(), 'image' => $this->image('png', null, 6001, 1)])->assertSessionHasErrors('image');
        $this->post(route('admin.media.store'), [...$this->text(), 'image' => $this->image(), 'path' => '../escape.php', 'disk' => 'local'])->assertSessionHasErrors(['path', 'disk']);
        $this->post(route('admin.media.store'), ['title' => ['en' => 'A', 'fr' => 'unsafe'], 'image' => $this->image()])->assertSessionHasErrors('title');
        $this->post(route('admin.media.store'), ['title' => ['bn' => 'শুধু বাংলা'], 'image' => $this->image()])->assertSessionHasErrors('title.en');
        $this->assertSame(9, MediaAsset::count());
        foreach (['../file.png', 'images/2026/10/../file.png', 'images/2026/10/file.svg', 'images/2026/10/'.str_repeat('a', 32).'.php', 'images/2026/13/'.str_repeat('a', 32).'.png', 'C:/private/file.png'] as $path) {
            $asset = new MediaAsset(['disk' => 'public-media', 'path' => $path]);
            $this->assertSame('', $asset->url());
        }
        $this->fakeMediaDisk('javascript:unsafe');
        $asset = new MediaAsset(['disk' => 'public-media', 'path' => 'images/2026/10/'.str_repeat('a', 32).'.png']);
        $this->assertSame('', $asset->url());
    }

    public function test_all_media_endpoints_deny_guests_and_non_admins(): void
    {
        $asset = MediaAsset::first();
        $user = auth()->user();
        auth()->logout();
        foreach (['get' => ['/admin/media', '/admin/media/create', '/admin/media/'.$asset->id.'/edit'], 'post' => ['/admin/media'], 'put' => ['/admin/media/'.$asset->id, '/admin/media/'.$asset->id.'/references'], 'delete' => ['/admin/media/'.$asset->id]] as $method => $urls) {
            foreach ($urls as $url) {
                $this->$method($url)->assertRedirect('/admin/login');
            }
        }
        $user->forceFill(['is_admin' => false])->save();
        $this->actingAs($user);
        foreach (['get' => ['/admin/media', '/admin/media/create', '/admin/media/'.$asset->id.'/edit'], 'post' => ['/admin/media'], 'put' => ['/admin/media/'.$asset->id, '/admin/media/'.$asset->id.'/references'], 'delete' => ['/admin/media/'.$asset->id]] as $method => $urls) {
            foreach ($urls as $url) {
                $this->$method($url)->assertForbidden();
            }
        }
    }

    public function test_details_preserve_omitted_bangla_and_cannot_change_file_identity(): void
    {
        $asset = $this->upload();
        $path = $asset->path;
        $this->put(route('admin.media.update', $asset), ['title' => ['en' => 'Updated'], 'caption' => ['en' => 'New caption']])->assertSessionHasNoErrors();
        $this->assertSame(['en' => 'Updated', 'bn' => 'পরীক্ষার ছবি'], $asset->fresh()->title);
        $this->assertSame('ক্যাপশন', $asset->fresh()->caption['bn']);
        $this->put(route('admin.media.update', $asset), ['path' => '../escape.php'])->assertSessionHasErrors('path');
        $this->put(route('admin.media.update', $asset), ['title' => ['en' => ' ']])->assertSessionHasErrors('title.en');
        $this->assertSame($path, $asset->fresh()->path);
        $this->assertSame('Updated', $asset->fresh()->title['en']);
    }

    public function test_usage_blocks_deletion_and_replacement_updates_every_owner_atomically_without_deleting_files(): void
    {
        $source = MediaAsset::whereHas('settings')->firstOrFail();
        $source->load(['posts', 'galleryImages', 'contentFields', 'settings']);
        $expected = $source->posts->count() + $source->galleryImages->count() + $source->contentFields->count() + $source->settings->count();
        $this->assertGreaterThan(0, $expected);
        $this->delete(route('admin.media.destroy', $source), ['confirm_delete' => 1])->assertSessionHasErrors('media');
        $target = $this->upload();
        $this->put(route('admin.media.replace', $source), ['replacement_id' => $source->id, 'confirm_replace' => 1])->assertSessionHasErrors('replacement_id');
        $this->put(route('admin.media.replace', $source), ['replacement_id' => $target->id])->assertSessionHasErrors('confirm_replace');
        $this->put(route('admin.media.replace', $source), ['replacement_id' => $target->id, 'confirm_replace' => 1])->assertSessionHasNoErrors();
        $this->assertFalse($source->fresh()->inUse());
        $this->assertTrue($target->fresh()->inUse());
        $this->assertNotNull($source->fresh()->source_url);
        Storage::disk('public-media')->assertExists($target->path);
        $this->get('/?locale=bn')->assertOk()->assertSee($target->url(), false)->assertDontSee('/storage/', false);
        $this->delete(route('admin.media.destroy', $target), ['confirm_delete' => 1])->assertSessionHasErrors('media');
        $this->delete(route('admin.media.destroy', $source), ['confirm_delete' => 1])->assertRedirect(route('admin.media.index'));
        Storage::disk('public-media')->assertExists($target->path);
    }

    public function test_shared_article_gallery_and_page_references_use_the_same_local_url_including_lightbox(): void
    {
        $target = $this->upload();
        $source = MediaAsset::whereHas('posts')->whereHas('galleryImages')->firstOrFail();
        $this->put(route('admin.media.replace', $source), ['replacement_id' => $target->id, 'confirm_replace' => 1])->assertSessionHasNoErrors();
        $post = Post::where('media_asset_id', $target->id)->firstOrFail();
        $this->get('/blog/'.$post->slug.'?locale=bn')->assertOk()->assertSee($target->url(), false);
        $this->get('/gallery?locale=bn')->assertOk()->assertSee($target->url(), false);
        $post->update(['media_asset_id' => null]);
        Storage::disk('public-media')->assertExists($target->path);
        $this->assertTrue($target->fresh()->inUse());
        $this->get(route('admin.media.edit', $target))->assertOk()->assertSee('Gallery')->assertSee('Deletion is blocked');
    }

    public function test_unused_local_delete_requires_consent_and_removes_only_its_own_file(): void
    {
        $asset = $this->upload();
        $other = $this->upload();
        $this->delete(route('admin.media.destroy', $asset))->assertSessionHasErrors('confirm_delete');
        $this->delete(route('admin.media.destroy', $asset), ['confirm_delete' => 1])->assertSessionHasNoErrors()->assertRedirect(route('admin.media.index'));
        $this->assertNull($asset->fresh());
        Storage::disk('public-media')->assertMissing($asset->path);
        Storage::disk('public-media')->assertExists($other->path);
        $this->assertSame([], Storage::disk('public-media')->allFiles('.deleting'));
    }

    public function test_database_failure_cleans_up_uploaded_bytes_and_reports_a_friendly_error(): void
    {
        MediaAsset::creating(function ($asset) {
            if (str_starts_with($asset->key, 'upload:')) {
                throw new \RuntimeException('Simulated database failure');
            }
        });
        $this->post(route('admin.media.store'), [...$this->text(), 'image' => $this->image()])->assertSessionHasErrors('image');
        $this->assertSame(9, MediaAsset::count());
        $this->assertSame(['.htaccess'], Storage::disk('public-media')->allFiles());
    }

    public function test_paginated_search_source_filters_picker_and_external_records_remain_truthful(): void
    {
        config(['media.per_page' => 3]);
        $this->get('/admin/media?kind=external&picker=1')->assertOk()->assertSee('Choose image')->assertSee('href="http://127.0.0.1:8000/admin/media?kind=external&amp;picker=1&amp;page=2"', false);
        $this->get('/admin/media?kind=local')->assertOk()->assertSee('No images found');
        $this->get('/admin/media?q=unlikely-no-match')->assertOk()->assertSee('No images found');
        $this->get('/admin/media?kind=unsafe')->assertSessionHasErrors('kind');
        $asset = MediaAsset::first();
        $this->get(route('admin.media.edit', $asset))->assertOk()->assertSee('not a downloaded local file');
        $this->assertSame(9, MediaAsset::whereNotNull('source_url')->whereNull('path')->count());
        $this->assertSame([], Storage::disk('public-media')->allFiles());
    }

    public function test_filesystem_write_failure_leaves_no_database_record(): void
    {
        $disk = Storage::disk('public-media');
        $mock = Mockery::mock($disk)->makePartial();
        $mock->shouldReceive('put')->andReturn(false);
        Storage::set('public-media', $mock);
        $this->post(route('admin.media.store'), [...$this->text(), 'image' => $this->image()])->assertSessionHasErrors('image');
        $this->assertSame(9, MediaAsset::count());
        $this->assertSame([], $disk->allFiles());
    }

    public function test_delete_move_failure_keeps_the_record_and_file(): void
    {
        $asset = $this->upload();
        $disk = Storage::disk('public-media');
        $mock = Mockery::mock($disk)->makePartial();
        $mock->shouldReceive('move')->andReturn(false);
        Storage::set('public-media', $mock);
        $this->delete(route('admin.media.destroy', $asset), ['confirm_delete' => 1])->assertSessionHasErrors('media');
        $this->assertNotNull($asset->fresh());
        $disk->assertExists($asset->path);
    }

    public function test_database_delete_failure_restores_the_staged_file(): void
    {
        $asset = $this->upload();
        MediaAsset::deleting(fn () => throw new \RuntimeException('Simulated failure'));
        $this->delete(route('admin.media.destroy', $asset), ['confirm_delete' => 1])->assertSessionHasErrors('media');
        $this->assertNotNull($asset->fresh());
        Storage::disk('public-media')->assertExists($asset->path);
        $this->assertSame([], Storage::disk('public-media')->allFiles('.deleting'));
    }

    public function test_quarantine_cleanup_failure_is_reported_truthfully(): void
    {
        $asset = $this->upload();
        $disk = Storage::disk('public-media');
        $mock = Mockery::mock($disk)->makePartial();
        $mock->shouldReceive('delete')->andReturn(false);
        Storage::set('public-media', $mock);
        $this->delete(route('admin.media.destroy', $asset), ['confirm_delete' => 1])->assertRedirect(route('admin.media.index'))->assertSessionHas('status', fn ($message) => str_contains($message, 'temporary image file'));
        $this->assertNull($asset->fresh());
        $this->assertCount(1, $disk->allFiles('.deleting'));
    }

    public function test_reference_failure_rolls_back_earlier_owner_updates(): void
    {
        $source = MediaAsset::whereHas('posts')->whereHas('galleryImages')->firstOrFail();
        $target = $this->upload();
        $ids = $source->posts()->pluck('id')->all();
        DB::listen(function ($query) {
            if (str_starts_with($query->sql, 'update `gallery_images`')) {
                throw new \RuntimeException('Simulated failure');
            }
        });
        $this->put(route('admin.media.replace', $source), ['replacement_id' => $target->id, 'confirm_replace' => 1])->assertSessionHasErrors('media');
        $this->assertSame(count($ids), Post::whereIn('id', $ids)->where('media_asset_id', $source->id)->count());
        $this->assertFalse($target->fresh()->inUse());
        Storage::disk('public-media')->assertExists($target->path);
    }
}
