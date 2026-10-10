<?php

namespace Tests\Feature;

use App\Models\ConsultationOption;
use App\Models\ContentField;
use App\Models\Inquiry;
use App\Services\InitialWebsiteImporter;
use App\Services\WebsiteContent;
use Database\Seeders\InquiryFormContentSeeder;
use Illuminate\Cache\RateLimiter;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class InquirySubmissionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        if (DB::connection()->getDatabaseName() !== 'doctor_portfolio_testing') {
            throw new \RuntimeException('Inquiry tests require doctor_portfolio_testing.');
        }
        DB::beginTransaction();
        $this->beforeApplicationDestroyed(fn () => DB::rollBack());
        app(InitialWebsiteImporter::class)->import();
        $this->seed(InquiryFormContentSeeder::class);
        $this->withServerVariables(['REMOTE_ADDR' => '192.0.2.'.random_int(1, 250)]);
    }

    private function form(string $kind = 'appointment', string $locale = 'en'): array
    {
        $html = $this->get('/contact?kind='.$kind.'&locale='.$locale)->assertOk()->getContent();
        preg_match('/name="submission_token" value="([a-f0-9]{64})"/', $html, $token);
        preg_match('/name="_token" value="([^"]+)"/', $html, $csrf);
        $this->assertNotEmpty($token[1]);

        return ['_token' => $csrf[1], 'submission_token' => $token[1], 'kind' => $kind, 'locale' => $locale, 'name' => 'Test submitter', 'phone' => '+880 0123 4567', 'message' => 'Test-only message', 'consultation_option_id' => ConsultationOption::where('is_active', true)->firstOrFail()->id, 'company_website' => ''];
    }

    public function test_real_web_post_saves_each_kind_once_before_internal_prg_feedback(): void
    {
        $this->app['env'] = 'local'; // Enable real CSRF middleware, normally bypassed by Laravel tests.
        try {
            foreach (['appointment', 'contact'] as $kind) {
                $data = $this->form($kind);
                if ($kind === 'appointment') {
                    unset($data['message']);
                }
                $before = Inquiry::count();
                $response = $this->post('/inquiries', $data)->assertRedirect('/contact?locale=en&kind='.$kind)->assertSessionHas('inquiry_success');
                $this->assertSame($before + 1, Inquiry::count());
                $inquiry = Inquiry::latest('id')->firstOrFail();
                $this->assertSame('new', $inquiry->status);
                $this->assertSame($kind, $inquiry->kind);
                $this->assertSame('+880 0123 4567', $inquiry->phone);
                $this->assertMatchesRegularExpression('/^[0-9A-HJKMNP-TV-Z]{26}$/', $inquiry->public_reference);
                $response->assertSessionHas('inquiry_reference', $inquiry->public_reference);
                $this->post('/inquiries', $data)->assertSessionHas('inquiry_success');
                $this->assertSame($before + 1, Inquiry::count());
                if ($kind === 'appointment') {
                    $this->assertSame($inquiry->consultationOption->label, $inquiry->consultation_label);
                    $this->assertNull($inquiry->message);
                } else {
                    $this->assertNull($inquiry->consultation_option_id);
                    $this->assertNull($inquiry->consultation_key);
                    $this->assertNull($inquiry->consultation_label);
                }
                $this->get($response->headers->get('Location'))->assertOk()->assertSee($inquiry->public_reference)->assertSee(app(WebsiteContent::class)->text('contact.success_'.$kind));
            }
        } finally {
            $this->app['env'] = 'testing';
        }
    }

    public function test_invalid_input_preserves_bangla_and_mode_without_saving_or_external_redirect(): void
    {
        $data = $this->form('contact', 'bn');
        $data['name'] = 'নাম';
        $data['phone'] = '০১৭১২ ৩৪৫৬৭৮';
        $data['message'] = '';
        $data['redirect'] = 'https://example.test/';
        $response = $this->withHeader('Referer', 'https://example.test/')->post('/inquiries', $data)->assertRedirect('/contact?locale=bn&kind=contact')->assertSessionHasErrors('message')->assertSessionMissing('inquiry_success');
        $this->get($response->headers->get('Location'))->assertSee('lang="bn"', false)->assertSee('value="নাম"', false)->assertSee('value="০১৭১২ ৩৪৫৬৭৮"', false)->assertSee('value="contact" selected', false)->assertSee(app(WebsiteContent::class)->text('contact.submit_contact'));
        $this->assertSame(0, Inquiry::count());
    }

    public function test_bangla_messages_stay_single_plain_values_and_option_snapshot_survives_changes(): void
    {
        $data = $this->form('appointment', 'bn');
        $data['name'] = 'পরীক্ষার নাম';
        $data['phone'] = '+৮৮০ ০১৭১২-৩৪৫৬৭৮';
        $data['message'] = "প্রথম লাইন\nদ্বিতীয় লাইন <script>plain</script>";
        $this->post('/inquiries', $data)->assertSessionHasNoErrors();
        $inquiry = Inquiry::firstOrFail();
        $this->assertSame($data['name'], $inquiry->name);
        $this->assertSame($data['phone'], $inquiry->phone);
        $this->assertSame($data['message'], $inquiry->message);
        $this->assertSame('bn', $inquiry->locale);
        $snapshot = $inquiry->consultation_label;
        $option = $inquiry->consultationOption;
        $option->update(['label' => ['en' => 'Renamed option', 'bn' => null], 'is_active' => false]);
        $option->delete();
        $this->assertNull($inquiry->fresh()->consultation_option_id);
        $this->assertSame($snapshot, $inquiry->fresh()->consultation_label);
        $this->assertNotNull($inquiry->fresh()->consultation_key);
        $this->assertArrayNotHasKey('message', $inquiry->toArray());
        $this->assertArrayNotHasKey('submission_token_hash', $inquiry->toArray());
        $this->get('/inquiries/'.$inquiry->public_reference)->assertNotFound();
        $this->get('/contact')->assertDontSee('<script>plain</script>', false);
    }

    public function test_validation_limits_kinds_phones_hidden_fields_and_inactive_options(): void
    {
        $cases = [['name' => ''], ['name' => str_repeat('n', 81)], ['phone' => ''], ['phone' => str_repeat('1', 41)], ['phone' => 'letters'], ['phone' => '12+34'], ['message' => str_repeat('ম', 501)], ['locale' => 'de'], ['kind' => 'unknown'], ['kind' => ['appointment']], ['name' => ['bad']], ['consultation_option_id' => 999999], ['submission_token' => ['bad']]];
        foreach ($cases as $changes) {
            // Distinct test IPs avoid conflating validation with the separate rate limit test.
            $this->withServerVariables(['REMOTE_ADDR' => '198.51.100.'.random_int(1, 250)]);
            $data = array_replace($this->form(), $changes);
            $this->post('/inquiries', $data)->assertSessionHasErrors(array_key_first($changes))->assertSessionMissing('inquiry_success');
        }
        $data = $this->form();
        ConsultationOption::findOrFail($data['consultation_option_id'])->update(['is_active' => false]);
        $this->post('/inquiries', $data)->assertSessionHasErrors('consultation_option_id');
        $this->assertSame(0, Inquiry::count());
    }

    public function test_token_is_session_bound_expiring_and_changed_payload_cannot_claim_success(): void
    {
        $data = $this->form('contact');
        $this->post('/inquiries', $data)->assertSessionHas('inquiry_success');
        $data['message'] = 'Different message';
        $this->post('/inquiries', $data)->assertSessionHasErrors('submission_token')->assertSessionMissing('inquiry_success');
        $this->withSession(['inquiry_submission_tokens' => []])->post('/inquiries', $data)->assertSessionHasErrors('submission_token');
        $this->withSession(['inquiry_submission_tokens' => [hash('sha256', $data['submission_token']) => time() - 7201]])->post('/inquiries', $data)->assertSessionHasErrors('submission_token');
        $this->assertSame(1, Inquiry::count());
        $this->expectException(QueryException::class);
        $copy = Inquiry::firstOrFail()->replicate();
        $copy->save(); // Database uniqueness independently rejects the reused token hash.
    }

    public function test_honeypot_rejects_without_fake_success_and_private_fields_are_not_assignable(): void
    {
        $data = $this->form('contact');
        $data['company_website'] = 'https://spam.example/';
        $this->post('/inquiries', $data)->assertSessionHasErrors('company_website')->assertSessionMissing('inquiry_success');
        $this->assertSame(0, Inquiry::count());
        $data['company_website'] = '';
        $data['status'] = 'confirmed';
        $data['public_reference'] = 'forged';
        $data['email'] = 'not-shown@example.test';
        $data['internal_notes'] = 'forged';
        $this->post('/inquiries', $data)->assertSessionHasNoErrors();
        $inquiry = Inquiry::firstOrFail();
        $this->assertSame('new', $inquiry->status);
        $this->assertNotSame('forged', $inquiry->public_reference);
        $this->assertNull($inquiry->internal_notes);
        $this->assertNull($inquiry->email);
    }

    public function test_csrf_missing_or_wrong_token_redirects_honestly_and_creates_no_record(): void
    {
        $this->app['env'] = 'local';
        try {
            $data = $this->form('contact', 'bn');
            $data['_token'] = 'wrong';
            $this->post('/inquiries', $data)->assertRedirect('/contact?locale=bn&kind=contact')->assertSessionHasErrors('submission')->assertSessionMissing('inquiry_success');
            unset($data['_token']);
            $this->post('/inquiries', $data)->assertRedirect('/contact?locale=bn&kind=contact')->assertSessionHasErrors();
            unset($data['locale']);
            $this->post('/inquiries', $data)->assertRedirect('/contact?locale=bn&kind=contact')->assertSessionHasErrors();
            $this->assertSame(0, Inquiry::count());
        } finally {
            $this->app['env'] = 'testing';
        }
    }

    public function test_insert_failure_rolls_back_and_retains_values_with_database_failure_copy(): void
    {
        $data = $this->form('contact', 'bn');
        new Inquiry; // Boot model callbacks on the original dispatcher before isolating failure.
        $dispatcher = Inquiry::getEventDispatcher();
        $isolated = clone $dispatcher;
        Inquiry::setEventDispatcher($isolated);
        $fail = true;
        $isolated->listen('eloquent.created: '.Inquiry::class, function () use (&$fail) {
            if ($fail) {
                throw new QueryException('mysql', 'test insert failure', [], new \RuntimeException('Controlled failure'));
            }
        });
        try {
            $response = $this->post('/inquiries', $data)->assertRedirect('/contact?locale=bn&kind=contact')->assertSessionHasErrors('submission')->assertSessionMissing('inquiry_success');
            $this->assertSame(0, Inquiry::count());
            $this->get($response->headers->get('Location'))->assertSee(app(WebsiteContent::class)->text('contact.save_failure'))->assertSee('value="Test submitter"', false);
        } finally {
            $fail = false;
            Inquiry::setEventDispatcher($dispatcher);
        }
        $this->post('/inquiries', $data)->assertSessionHas('inquiry_success');
        $this->assertSame(1, Inquiry::count());
    }

    public function test_file_cache_rate_limit_redirects_internally_without_saving(): void
    {
        $path = base_path('.local-artifacts/inquiry-rate-'.bin2hex(random_bytes(6)));
        config(['cache.stores.inquiry_test' => ['driver' => 'file', 'path' => $path]]);
        $limiter = app(RateLimiter::class);
        $property = new \ReflectionProperty($limiter, 'cache');
        $original = $property->getValue($limiter);
        $property->setValue($limiter, Cache::store('inquiry_test'));
        try {
            $data = $this->form('contact', 'bn');
            $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.11']);
            $data['message'] = '';
            for ($i = 0; $i < 10; $i++) {
                $this->post('/inquiries', $data)->assertSessionHasErrors('message');
            }
            $data['message'] = 'Valid message';
            $response = $this->post('/inquiries', $data)->assertRedirect('/contact?locale=bn&kind=contact')->assertSessionHasErrors('submission')->assertSessionMissing('inquiry_success');
            $response->assertHeader('Retry-After');
            $this->get($response->headers->get('Location'))->assertSee(app(WebsiteContent::class)->text('contact.rate_limited'));
            $this->assertSame(0, Inquiry::count());
            $this->assertDirectoryExists($path);
        } finally {
            $property->setValue($limiter, $original);
            if (str_starts_with($path, base_path('.local-artifacts').DIRECTORY_SEPARATOR)) {
                File::deleteDirectory($path);
            }
        }
    }

    public function test_no_js_modes_ctas_and_seeded_feedback_are_database_owned_and_repeat_safe(): void
    {
        $html = $this->get('/contact?kind=contact&locale=bn')->getContent();
        $this->assertSame(1, substr_count($html, '<form'));
        $this->assertStringContainsString('name="kind"', $html);
        $this->assertStringContainsString('value="contact" selected', $html);
        $this->assertStringNotContainsString('id="topic" name="consultation_option_id" required', $html);
        $data = $this->form('appointment');
        $data['kind'] = 'contact'; // The selector works directly without executing public JavaScript.
        $data['consultation_option_id'] = 'invalid ignored appointment field';
        $this->post('/inquiries', $data)->assertSessionHasNoErrors();
        $this->assertNull(Inquiry::firstOrFail()->consultation_option_id);
        $this->get('/contact?kind[]=invalid&locale=bn')->assertSee('value="appointment" selected', false);
        $this->get('/contact?kind=invalid&mode=contact')->assertSee('value="appointment" selected', false);
        $this->get('/contact?mode=contact&locale=bn')->assertSee('/contact?kind=contact&amp;locale=en', false);
        foreach (['/', '/about', '/services', '/diagnostics'] as $url) {
            $this->get($url.'?locale=bn')->assertSee('/contact?locale=bn&amp;kind=appointment', false);
        }
        $field = ContentField::where('key', 'contact.submission_expired')->firstOrFail();
        $field->update(['value' => ['en' => 'Owner feedback', 'bn' => 'মালিকের বার্তা']]);
        ContentField::where('key', 'contact.reference_label')->delete();
        $this->seed(InquiryFormContentSeeder::class);
        $this->assertSame('মালিকের বার্তা', $field->fresh()->value['bn']);
        $this->assertFalse(ContentField::where('key', 'contact.reference_label')->exists());
        $this->app->forgetInstance(WebsiteContent::class); // Feature requests reuse one container; real requests are scoped.
        $bad = $this->form('contact', 'bn');
        $bad['submission_token'] = 'bad';
        $response = $this->post('/inquiries', $bad)->assertSessionHasErrors('submission_token');
        $this->get($response->headers->get('Location'))->assertSee('মালিকের বার্তা');
        $this->assertSame(1, Inquiry::count());
    }
}
