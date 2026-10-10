<?php

namespace Tests\Feature;

use App\Models\ConsultationOption;
use App\Models\GalleryImage;
use App\Models\Inquiry;
use App\Models\InquiryStatusChange;
use App\Models\Post;
use App\Models\User;
use App\Services\InitialWebsiteImporter;
use App\Services\InquiryManager;
use Database\Seeders\InquiryFormContentSeeder;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Tests\TestCase;

class InquiryInboxTest extends TestCase
{
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        if (DB::connection()->getDatabaseName() !== 'doctor_portfolio_test') {
            throw new \RuntimeException('Inbox verification requires doctor_portfolio_test.');
        }
        DB::beginTransaction();
        $this->beforeApplicationDestroyed(fn () => DB::rollBack());
        app(InitialWebsiteImporter::class)->import();
        $this->seed(InquiryFormContentSeeder::class);
        $this->admin = User::create(['name' => 'Disposable staff member', 'email' => Str::uuid().'@example.test', 'password' => 'Aa9!'.bin2hex(random_bytes(20))]);
        $this->admin->forceFill(['is_admin' => true])->save();
    }

    private function inquiry(string $kind = 'appointment', string $name = 'Disposable request'): Inquiry
    {
        return Inquiry::create(['kind' => $kind, 'name' => $name, 'phone' => '+880 0123 4567', 'locale' => 'en', 'message' => 'Test-only visitor text', 'consultation_option_id' => ConsultationOption::firstOrFail()->id]);
    }

    private function path(Inquiry $inquiry): string
    {
        return '/admin/'.($inquiry->kind === 'appointment' ? 'appointments' : 'contact-messages').'/'.$inquiry->id;
    }

    private function payload(Inquiry $inquiry, array $changes = []): array
    {
        $inquiry->refresh();

        return [...['expected_version' => app(InquiryManager::class)->version($inquiry), 'status' => $inquiry->status, 'internal_notes' => $inquiry->internal_notes], ...$changes];
    }

    public function test_public_english_and_bangla_submissions_appear_in_their_actual_kind_scoped_details(): void
    {
        foreach (['en', 'bn'] as $locale) {
            foreach (['appointment', 'contact'] as $kind) {
                $html = $this->get('/contact?kind='.$kind.'&locale='.$locale)->assertOk()->getContent();
                preg_match('/name="submission_token" value="([a-f0-9]{64})"/', $html, $token);
                $name = $locale === 'bn' ? 'শুধু পরীক্ষার নাম' : 'Only test visitor';
                $phone = $locale === 'bn' ? '+৮৮০ ০১৭১২ ৩৪৫৬৭৮' : '+880 0123 4567';
                $message = $locale === 'bn' ? "পরীক্ষার বার্তা\nদ্বিতীয় লাইন <script>plain</script>" : "Test message\nSecond line <script>plain</script>";
                $before = Inquiry::count();
                $this->post('/inquiries', ['submission_token' => $token[1], 'locale' => $locale, 'kind' => $kind, 'name' => $name, 'phone' => $phone, 'message' => $message, 'consultation_option_id' => ConsultationOption::firstOrFail()->id])->assertRedirect('/contact?locale='.$locale.'&kind='.$kind)->assertSessionHasNoErrors();
                $this->assertSame($before + 1, Inquiry::count());
                $inquiry = Inquiry::latest('id')->firstOrFail();
                $this->assertSame([$name, $phone, $message, $locale], [$inquiry->name, $inquiry->phone, $inquiry->message, $inquiry->locale]);
                $this->actingAs($this->admin);
                $detail = $this->get($this->path($inquiry))->assertOk()->assertSee($name)->assertSee($phone)->assertSee(e($message), false)->assertSee($inquiry->public_reference)->assertDontSee('<script>plain</script>', false)->assertHeader('X-Robots-Tag', 'noindex, nofollow, nosnippet');
                $detail->assertSee($locale === 'bn' ? 'বাংলা (bn)' : 'English (en)');
                if ($kind === 'appointment') {
                    foreach ($inquiry->consultation_label as $label) {
                        $detail->assertSee($label);
                    }
                    $detail->assertSee($inquiry->consultation_key);
                } else {
                    $detail->assertDontSee('name="confirmed_visit_at"', false)->assertDontSee('value="confirmed"', false);
                    $this->assertNull($inquiry->consultation_label);
                }
                $base = dirname($this->path($inquiry));
                $this->get($base)->assertSee($inquiry->public_reference)->assertSee($name)->assertSee($phone);
                $wrong = $kind === 'appointment' ? 'contact-messages' : 'appointments';
                $this->get('/admin/'.$wrong.'/'.$inquiry->id)->assertNotFound();
                $this->app['auth']->guard()->logout();
            }
        }
        $this->assertSame(4, Inquiry::count());
    }

    public function test_guest_and_non_admin_denial_covers_every_inbox_action(): void
    {
        foreach (['appointment', 'contact'] as $kind) {
            $inquiry = $this->inquiry($kind);
            $path = $this->path($inquiry);
            foreach (['guest', 'non-admin'] as $state) {
                if ($state === 'non-admin') {
                    $user = User::factory()->create();
                    $this->actingAs($user);
                } else {
                    $this->app['auth']->guard()->logout();
                }
                foreach ([['GET', dirname($path)], ['GET', $path], ['POST', dirname($path).'/filter'], ['PATCH', $path], ['PATCH', $path.'/read'], ['DELETE', $path]] as [$method, $url]) {
                    $response = $this->call($method, $url, []);
                    $state === 'guest' ? $response->assertRedirect('/admin/login') : $response->assertForbidden();
                    $response->assertHeader('X-Robots-Tag', 'noindex, nofollow, nosnippet');
                    $this->assertStringContainsString('no-store', $response->headers->get('Cache-Control'));
                }
            }
        }
        $this->assertSame(2, Inquiry::count());
        $this->assertSame(0, InquiryStatusChange::count());
    }

    public function test_cross_kind_details_updates_read_and_delete_are_all_not_found(): void
    {
        $this->actingAs($this->admin);
        foreach (['appointment', 'contact'] as $kind) {
            $inquiry = $this->inquiry($kind);
            $wrong = '/admin/'.($kind === 'appointment' ? 'contact-messages' : 'appointments').'/'.$inquiry->id;
            foreach ([['GET', $wrong], ['PATCH', $wrong], ['PATCH', $wrong.'/read'], ['DELETE', $wrong]] as [$method, $path]) {
                $this->call($method, $path, $this->payload($inquiry, ['status' => 'read', 'confirm_delete' => '1']))->assertNotFound()->assertDontSee($inquiry->name);
            }
        }
        $this->assertSame(2, Inquiry::where('status', 'new')->count());
        $this->get('/admin/appointments/999999999')->assertNotFound();
        $this->get('/admin/contact-messages/not-numeric')->assertNotFound();
    }

    public function test_get_is_read_only_and_explicit_read_and_notes_are_persisted_and_attributed(): void
    {
        $this->actingAs($this->admin);
        $inquiry = $this->inquiry();
        $initial = $inquiry->fresh()->getAttributes();
        $this->get($this->path($inquiry))->assertOk();
        $this->get('/admin/appointments')->assertOk();
        $this->get('/admin')->assertOk();
        $this->assertSame($initial, $inquiry->fresh()->getAttributes());
        $this->assertSame(0, InquiryStatusChange::count());
        $this->patch($this->path($inquiry).'/read', $this->payload($inquiry))->assertSessionHasNoErrors();
        $this->assertSame('read', $inquiry->fresh()->status);
        $this->assertSame($this->admin->id, $inquiry->fresh()->handled_by);
        $this->assertNotNull($inquiry->fresh()->handled_at);
        $change = $inquiry->statusChanges()->firstOrFail();
        $this->assertSame(['new', 'read', $this->admin->id], [$change->from_status, $change->to_status, $change->actor_id]);
        $this->assertTrue($change->inquiry->is($inquiry));
        $this->assertTrue($change->actor->is($this->admin));
        $notes = "Private test note\nবাংলা নোট <script>plain</script>";
        $this->patch($this->path($inquiry), $this->payload($inquiry, ['internal_notes' => $notes]))->assertSessionHasNoErrors();
        $this->assertSame($notes, $inquiry->fresh()->internal_notes);
        $this->get($this->path($inquiry))->assertSee(e($notes), false)->assertDontSee('<script>plain</script>', false)->assertSee($this->admin->name);
        $this->assertSame(1, $inquiry->statusChanges()->count());
        $this->patch($this->path($inquiry), $this->payload($inquiry, ['internal_notes' => '']))->assertSessionHasNoErrors();
        $this->assertNull($inquiry->fresh()->internal_notes);
    }

    public function test_appointment_transitions_confirmation_and_optional_time_rules(): void
    {
        $this->actingAs($this->admin);
        $inquiry = $this->inquiry();
        $path = $this->path($inquiry);
        $this->patch($path, $this->payload($inquiry, ['status' => 'completed']))->assertSessionHasErrors('status');
        $this->patch($path, $this->payload($inquiry, ['status' => 'confirmed']))->assertSessionHasErrors('confirm_status');
        $this->patch($path, $this->payload($inquiry, ['status' => 'confirmed', 'confirm_status' => '1']))->assertSessionHasNoErrors();
        $this->assertSame('confirmed', $inquiry->fresh()->status);
        $this->assertNull($inquiry->fresh()->confirmed_visit_at);
        $time = now()->addDay()->startOfMinute()->format('Y-m-d\TH:i');
        $this->patch($path, $this->payload($inquiry, ['confirmed_visit_at' => $time]))->assertSessionHasNoErrors();
        $this->assertSame($time, $inquiry->fresh()->confirmed_visit_at->format('Y-m-d\TH:i'));
        $this->get($path)->assertSee('value="'.$time.'"', false);
        foreach (['2026-02-30T10:00', 'garbage', now()->subDay()->format('Y-m-d\TH:i'), '2101-01-01T10:00'] as $invalid) {
            $this->patch($path, $this->payload($inquiry, ['confirmed_visit_at' => $invalid]))->assertSessionHasErrors('confirmed_visit_at');
            $this->assertSame($time, $inquiry->fresh()->confirmed_visit_at->format('Y-m-d\TH:i'));
        }
        $this->patch($path, $this->payload($inquiry, ['status' => 'read']))->assertSessionHasErrors('confirmed_visit_at');
        $this->patch($path, $this->payload($inquiry, ['status' => 'cancelled', 'confirm_status' => '1']))->assertSessionHasNoErrors();
        $this->patch($path, $this->payload($inquiry, ['status' => 'read', 'confirmed_visit_at' => '']))->assertSessionHasErrors('confirm_status');
        $this->patch($path, $this->payload($inquiry, ['status' => 'read', 'confirmed_visit_at' => '', 'confirm_status' => '1']))->assertSessionHasNoErrors();
        $this->assertNull($inquiry->fresh()->confirmed_visit_at);
        $this->assertSame(3, InquiryStatusChange::count());
    }

    public function test_general_contact_has_only_relevant_statuses_and_no_confirmation_time(): void
    {
        $this->actingAs($this->admin);
        $inquiry = $this->inquiry('contact');
        $this->get($this->path($inquiry))->assertDontSee('name="confirmed_visit_at"', false)->assertDontSee('value="confirmed"', false)->assertDontSee('value="cancelled"', false);
        foreach (['confirmed', 'cancelled', 'unknown'] as $status) {
            $this->patch($this->path($inquiry), $this->payload($inquiry, ['status' => $status]))->assertSessionHasErrors('status');
        }
        $this->patch($this->path($inquiry), $this->payload($inquiry, ['confirmed_visit_at' => now()->addDay()->format('Y-m-d\TH:i')]))->assertSessionHasErrors('confirmed_visit_at');
        $this->patch($this->path($inquiry), $this->payload($inquiry, ['status' => 'completed']))->assertSessionHasNoErrors();
        $this->patch($this->path($inquiry), $this->payload($inquiry, ['status' => 'spam']))->assertSessionHasErrors('confirm_status');
        $this->patch($this->path($inquiry), $this->payload($inquiry, ['status' => 'spam', 'confirm_status' => '1']))->assertSessionHasNoErrors();
        $this->assertSame('spam', $inquiry->fresh()->status);
        $this->assertNull($inquiry->fresh()->confirmed_visit_at);
    }

    public function test_stale_save_and_delete_cannot_overwrite_another_staff_action(): void
    {
        $this->actingAs($this->admin);
        $inquiry = $this->inquiry('contact');
        $stale = $this->payload($inquiry);
        $this->patch($this->path($inquiry), [...$stale, 'internal_notes' => 'First staff update'])->assertSessionHasNoErrors();
        $this->patch($this->path($inquiry), [...$stale, 'internal_notes' => 'Stale update'])->assertSessionHasErrors('expected_version');
        $this->delete($this->path($inquiry), [...$stale, 'confirm_delete' => '1'])->assertSessionHasErrors('expected_version');
        $this->assertSame('First staff update', $inquiry->fresh()->internal_notes);
        $this->assertSame(1, Inquiry::count());
    }

    public function test_search_and_date_status_filters_are_private_kind_scoped_and_paginated(): void
    {
        $this->actingAs($this->admin);
        config(['inquiries.per_page' => 2]);
        $old = $this->inquiry('appointment', 'Old excluded');
        $old->forceFill(['created_at' => now()->subDays(3)])->save();
        foreach (['Search patient 1', 'Search patient 2', 'Search patient 3'] as $name) {
            $this->inquiry('appointment', $name);
        }
        $this->inquiry('contact', 'Search patient wrong kind');
        $this->post('/admin/appointments/filter', ['q' => 'Search patient', 'status' => 'new', 'from' => now()->toDateString(), 'to' => now()->toDateString()])->assertRedirect('/admin/appointments');
        $page = $this->get('/admin/appointments')->assertOk()->assertViewHas('inquiries', fn ($rows) => $rows->total() === 3 && $rows->count() === 2)->assertDontSee('Search patient wrong kind')->assertDontSee('Old excluded');
        $page->assertSee('/admin/appointments?page=2', false);
        $this->assertDoesNotMatchRegularExpression('/(?:href|action)="[^"]*(?:Search|q=|0123)/', $page->getContent());
        $this->get('/admin/appointments?page=2')->assertViewHas('inquiries', fn ($rows) => $rows->total() === 3 && $rows->count() === 1);
        $target = Inquiry::where('name', 'Search patient 2')->firstOrFail();
        foreach ([$target->public_reference, '+880 0123', 'patient 2'] as $term) {
            $this->post('/admin/appointments/filter', ['q' => $term])->assertSessionHasNoErrors();
            $this->get('/admin/appointments')->assertSee($target->public_reference);
        }
        $old->forceFill(['phone' => '+888 888', 'public_reference' => str_repeat('Z', 26)])->save();
        $this->post('/admin/appointments/filter', ['q' => '0'])->assertSessionHasNoErrors();
        $this->get('/admin/appointments')->assertViewHas('inquiries', fn ($rows) => $rows->total() === 3)->assertDontSee('Old excluded');
        $this->post('/admin/appointments/filter', ['q' => '%'])->assertSessionHasNoErrors();
        $this->get('/admin/appointments')->assertViewHas('inquiries', fn ($rows) => $rows->total() === 0)->assertSee('No Appointments found');
        $this->post('/admin/appointments/filter', ['from' => 'bad', 'status' => 'invalid'])->assertSessionHasErrors(['from', 'status']);
        $this->post('/admin/appointments/filter', ['from' => '2026-10-10', 'to' => '2026-10-09'])->assertSessionHasErrors('to');
        $this->post('/admin/contact-messages/filter', ['status' => 'confirmed'])->assertSessionHasErrors('status');
        $this->post('/admin/appointments/filter', ['reset' => '1'])->assertRedirect('/admin/appointments');
        $this->get('/admin/appointments')->assertViewHas('inquiries', fn ($rows) => $rows->total() === 4);
        $this->get('/admin/contact-messages')->assertViewHas('inquiries', fn ($rows) => $rows->total() === 1);
        $this->get('/admin/appointments?q=unwanted-private-input')->assertRedirect('/admin/appointments');
    }

    public function test_delete_requires_confirmation_and_removes_only_local_record_and_history(): void
    {
        $this->actingAs($this->admin);
        $inquiry = $this->inquiry();
        $other = $this->inquiry('contact');
        $this->patch($this->path($inquiry).'/read', $this->payload($inquiry))->assertSessionHasNoErrors();
        $option = $inquiry->consultationOption;
        $this->delete($this->path($inquiry), $this->payload($inquiry))->assertSessionHasErrors('confirm_delete');
        $this->delete($this->path($inquiry), $this->payload($inquiry, ['confirm_delete' => '0']))->assertSessionHasErrors('confirm_delete');
        $this->assertSame(2, Inquiry::count());
        $this->delete($this->path($inquiry), $this->payload($inquiry, ['confirm_delete' => '1']))->assertRedirect('/admin/appointments')->assertSessionHas('status', 'The active database record and its local status history were deleted. Existing backups were not changed.');
        $this->assertNull(Inquiry::find($inquiry->id));
        $this->assertSame(0, InquiryStatusChange::count());
        $this->assertNotNull($other->fresh());
        $this->assertNotNull($option->fresh());
        $this->assertNotNull($this->admin->fresh());
        $this->assertSame(4, Post::count());
        $this->assertSame(8, GalleryImage::count());
    }

    public function test_dashboard_counts_recent_links_and_empty_state_are_real(): void
    {
        $this->actingAs($this->admin);
        $this->get('/admin')->assertSee('No inquiries received yet')->assertViewHas('newAppointments', 0)->assertViewHas('newMessages', 0);
        $appointment = $this->inquiry();
        $message = $this->inquiry('contact', 'Recent test message');
        $this->get('/admin')->assertViewHas('newAppointments', 1)->assertViewHas('newMessages', 1)->assertViewHas('publishedPosts', 4)->assertViewHas('visibleImages', 8)->assertSee($this->path($appointment))->assertSee($this->path($message))->assertSee('Recent test message');
        $this->patch($this->path($appointment).'/read', $this->payload($appointment))->assertSessionHasNoErrors();
        Post::firstOrFail()->update(['published_at' => now()->addDay()]);
        GalleryImage::firstOrFail()->update(['is_visible' => false]);
        $this->get('/admin')->assertViewHas('newAppointments', 0)->assertViewHas('newMessages', 1)->assertViewHas('publishedPosts', 3)->assertViewHas('visibleImages', 7)->assertSee('Recent inquiries')->assertSee('Quick links')->assertDontSee('Coming soon');
    }

    public function test_private_responses_errors_and_logs_do_not_disclose_submitted_data(): void
    {
        $this->actingAs($this->admin);
        config(['app.debug' => true]);
        Route::get('/admin/test-private-failure', function () {
            throw new \RuntimeException('Private test patient must not be output');
        })->middleware(['web', 'auth', 'admin']);
        Log::spy();
        $error = $this->get('/admin/test-private-failure')->assertStatus(500)->assertDontSee('Private test patient')->assertHeader('X-Robots-Tag', 'noindex, nofollow, nosnippet');
        $this->assertStringContainsString('no-store', $error->headers->get('Cache-Control'));
        Log::shouldHaveReceived('error')->with('Private request failed.', ['exception_type' => \RuntimeException::class])->once();
        $this->get('/admin/unknown-private-route')->assertNotFound()->assertHeader('X-Robots-Tag', 'noindex, nofollow, nosnippet');
        $inquiry = $this->inquiry();
        $this->get($this->path($inquiry))->assertDontSee('submission_token_hash')->assertDontSee($inquiry->submission_fingerprint ?? 'never-here-secret');
        $this->get('/?locale=en')->assertDontSee($inquiry->public_reference)->assertDontSee($inquiry->name);
        $this->get('/api/inquiries')->assertNotFound();
        $this->get('/appointments')->assertNotFound();
    }

    public function test_csrf_and_field_whitelists_protect_private_writes(): void
    {
        $this->actingAs($this->admin);
        $inquiry = $this->inquiry('contact');
        $this->app['env'] = 'local';
        try {
            $this->patch($this->path($inquiry), $this->payload($inquiry))->assertStatus(419)->assertHeader('X-Robots-Tag', 'noindex, nofollow, nosnippet');
            $this->post('/admin/contact-messages/filter', ['q' => 'Test'])->assertStatus(419);
            $this->delete($this->path($inquiry), $this->payload($inquiry, ['confirm_delete' => '1']))->assertStatus(419);
        } finally {
            $this->app['env'] = 'testing';
        }
        $this->patch($this->path($inquiry), $this->payload($inquiry, ['name' => 'Forged', 'phone' => 'Forged', 'kind' => 'appointment', 'handled_by' => $this->admin->id]))->assertSessionHasErrors(['name', 'phone', 'kind', 'handled_by']);
        $this->patch($this->path($inquiry), $this->payload($inquiry, ['internal_notes' => str_repeat('n', 15001)]))->assertSessionHasErrors('internal_notes');
        $this->assertSame('Disposable request', $inquiry->fresh()->name);
        $this->assertSame('contact', $inquiry->fresh()->kind);
        $this->assertNull($inquiry->fresh()->handled_by);
    }

    public function test_failed_history_insert_rolls_back_status_notes_and_attribution(): void
    {
        $this->actingAs($this->admin);
        $inquiry = $this->inquiry();
        new InquiryStatusChange;
        $dispatcher = Inquiry::getEventDispatcher();
        $isolated = clone $dispatcher;
        Inquiry::setEventDispatcher($isolated);
        $isolated->listen('eloquent.creating: '.InquiryStatusChange::class, fn () => throw new QueryException('mysql', 'test-only history failure', [], new \RuntimeException('Controlled failure')));
        try {
            $this->patch($this->path($inquiry), $this->payload($inquiry, ['status' => 'read', 'internal_notes' => 'Should roll back']))->assertSessionHasErrors('save');
        } finally {
            Inquiry::setEventDispatcher($dispatcher);
        }
        $this->assertSame('new', $inquiry->fresh()->status);
        $this->assertNull($inquiry->fresh()->internal_notes);
        $this->assertNull($inquiry->fresh()->handled_by);
        $this->assertSame(0, InquiryStatusChange::count());
    }

    public function test_deleted_public_submission_is_not_resurrected_by_its_used_form_token(): void
    {
        $html = $this->get('/contact?kind=contact&locale=en')->getContent();
        preg_match('/name="submission_token" value="([a-f0-9]{64})"/', $html, $token);
        $data = ['submission_token' => $token[1], 'locale' => 'en', 'kind' => 'contact', 'name' => 'Disposable form visitor', 'phone' => '+880 0123', 'message' => 'Test-only message'];
        $this->post('/inquiries', $data)->assertSessionHasNoErrors();
        $inquiry = Inquiry::firstOrFail();
        $this->actingAs($this->admin);
        $this->delete($this->path($inquiry), $this->payload($inquiry, ['confirm_delete' => '1']))->assertSessionHasNoErrors();
        $this->app['auth']->guard()->logout();
        $this->post('/inquiries', $data)->assertSessionHasErrors('submission_token')->assertSessionMissing('inquiry_success');
        $this->assertSame(0, Inquiry::count());
    }
}
