<?php

namespace Tests\Feature;

use App\Models\ContentField;
use App\Models\Post;
use App\Models\User;
use App\Services\AdminContentWriter;
use App\Services\InitialWebsiteImporter;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\ViewErrorBag;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class AdminContentControlsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        if (DB::connection()->getDatabaseName() !== 'doctor_portfolio_test') {
            throw new \RuntimeException('Use doctor_portfolio_test.');
        }
        DB::beginTransaction();
        $this->beforeApplicationDestroyed(fn () => DB::rollBack());
        app(InitialWebsiteImporter::class)->import();
        $user = User::create(['name' => 'Test editor', 'email' => Str::uuid().'@example.test', 'password' => bin2hex(random_bytes(24))]);
        $user->forceFill(['is_admin' => true])->save();
        $this->actingAs($user);
    }

    public function test_registry_writer_saves_languages_together_trims_and_preserves_omitted_bangla(): void
    {
        $writer = app(AdminContentWriter::class);
        $post = Post::where('slug', 'pcos')->firstOrFail();
        $bangla = $post->title['bn'];
        $writer->update($post, ['title' => ['en' => ' Owner title ']]);
        $this->assertSame(['en' => 'Owner title', 'bn' => $bangla], $post->fresh()->title);
        $writer->update($post, ['title' => ['en' => ' Updated ', 'bn' => ' বাংলা '], 'body' => ['en' => [' First ', '', ' Second '], 'bn' => [' ', ' বাংলা অনুচ্ছেদ ']]]);
        $this->assertSame(['en' => 'Updated', 'bn' => 'বাংলা'], $post->fresh()->title);
        $this->assertSame(['en' => ['First', 'Second'], 'bn' => ['বাংলা অনুচ্ছেদ']], $post->fresh()->body);
        $field = ContentField::where('key', 'home.herotitle')->firstOrFail();
        $bangla = $field->value['bn'];
        $writer->update($field, ['value' => ['en' => ' Changed heading ']]);
        $this->assertSame($bangla, $field->fresh()->value['bn']);
    }

    public function test_dashboard_counts_follow_database_publication_and_visibility(): void
    {
        $this->get('/admin')->assertOk()->assertViewHas('publishedPosts', 4)->assertViewHas('visibleImages', 8)->assertViewHas('newAppointments', 0)->assertViewHas('newMessages', 0)->assertDontSee('assets/style.css');
        Post::where('slug', 'pcos')->firstOrFail()->update(['status' => 'draft']);
        $this->get('/admin')->assertViewHas('publishedPosts', 3);
    }

    public function test_unknown_keys_bad_paragraphs_and_missing_english_are_rejected_atomically(): void
    {
        $post = Post::where('slug', 'pcos')->firstOrFail();
        $title = $post->title;
        foreach ([['title' => ['en' => 'Changed', 'fr' => 'No']], ['title' => ['en' => 'Changed'], 'is_admin' => true], ['title' => ['en' => 'Changed'], 'body' => ['en' => ['']]], ['body' => ['en' => [['unsafe' => 'nested']]]], ['key' => 'new-key']] as $input) {
            try {
                app(AdminContentWriter::class)->update($post, $input);
                $this->fail('Invalid input was accepted.');
            } catch (ValidationException $exception) {
                $this->assertNotEmpty($exception->errors());
            }
            $this->assertSame($title, $post->fresh()->title);
        }
    }

    public function test_localized_controls_render_both_values_and_password_components_never_echo_values(): void
    {
        view()->share('errors', new ViewErrorBag);
        $secret = bin2hex(random_bytes(24));
        $html = Blade::render('<x-admin.localized-tabs name="title" label="Title" :value="$value"/><x-admin.input name="password" label="Password" type="password" :value="$secret"/>', ['value' => ['en' => 'English unsaved', 'bn' => 'বাংলা unsaved'], 'secret' => $secret]);
        $this->assertStringContainsString('name="title[en]"', $html);
        $this->assertStringContainsString('name="title[bn]"', $html);
        $this->assertStringContainsString('বাংলা unsaved', $html);
        $this->assertStringContainsString('English unsaved', $html);
        $this->assertStringNotContainsString('disabled', $html);
        $this->assertStringNotContainsString($secret, $html);
        $this->assertStringContainsString('Both translations are saved together.', $html);
    }
}
