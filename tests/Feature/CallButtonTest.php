<?php

namespace Tests\Feature;

use App\Models\SiteSetting;
use App\Models\User;
use App\Services\InitialWebsiteImporter;
use Database\Seeders\CallButtonSettingsSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class CallButtonTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        DB::beginTransaction();
        $this->beforeApplicationDestroyed(fn () => DB::rollBack());
        app(InitialWebsiteImporter::class)->import();
        $this->seed(CallButtonSettingsSeeder::class);
        $admin = User::create(['name' => 'Call button editor', 'email' => Str::uuid().'@example.test', 'password' => bin2hex(random_bytes(24))]);
        $admin->forceFill(['is_admin' => true])->save();
        $this->actingAs($admin);
    }

    public function test_admin_number_is_shared_by_home_and_both_contact_modes_and_can_be_cleared(): void
    {
        $this->get('/admin/settings?group=clinic')->assertOk()->assertSee('Call phone number')->assertSee('name="settings[call_phone_number]"', false)->assertSee('name="settings[call_button_label][bn]"', false);
        $this->get('/')->assertOk()->assertDontSee('class="call-button"', false);
        $this->put('/admin/settings/clinic', ['settings' => ['call_phone_number' => '+1 (202) 555-0123']])->assertSessionHasNoErrors();
        foreach (['/', '/contact?kind=contact', '/contact?kind=appointment'] as $path) {
            $response = $this->get($path)->assertOk()->assertSee('class="call-button"', false)->assertSee('href="tel:+12025550123"', false)->assertSee('aria-label="Call now"', false)->assertSee('class="footer-phone"', false)->assertSee('+1 (202) 555-0123');
            $this->assertSame($path === '/' ? 2 : 3, substr_count($response->getContent(), 'href="tel:+12025550123"'));
            if ($path !== '/') {
                $response->assertSee('class="btn contact-call"', false);
            }
        }
        $this->get('/about')->assertOk()->assertDontSee('class="call-button"', false)->assertSee('class="footer-phone"', false)->assertSee('href="tel:+12025550123"', false);
        $this->put('/admin/settings/clinic', ['settings' => ['call_phone_number' => '+12025550124']])->assertSessionHasNoErrors();
        $updated = $this->get('/contact')->assertSee('href="tel:+12025550124"', false)->assertDontSee('tel:+12025550123', false);
        $this->assertSame(3, substr_count($updated->getContent(), 'href="tel:+12025550124"'));
        $this->put('/admin/settings/clinic', ['settings' => ['call_phone_number' => '']])->assertSessionHasNoErrors();
        $this->get('/')->assertDontSee('class="call-button"', false)->assertDontSee('class="footer-phone"', false);
        $this->get('/contact')->assertDontSee('class="call-button"', false)->assertDontSee('class="btn contact-call"', false)->assertDontSee('class="footer-phone"', false);
    }

    public function test_labels_and_bangla_digits_use_shared_localization_and_safe_tel_links(): void
    {
        $this->put('/admin/settings/clinic', ['settings' => ['call_phone_number' => '+১ (২০২) ৫৫৫-০১২৩', 'call_button_label' => ['en' => 'Phone the clinic', 'bn' => 'ফোন করুন']]])->assertSessionHasNoErrors();
        $this->get('/?locale=en')->assertSee('aria-label="Phone the clinic"', false);
        $this->get('/contact?locale=bn&kind=appointment')->assertSee('aria-label="ফোন করুন"', false)->assertSee('href="tel:+12025550123"', false);
        $this->put('/admin/settings/clinic', ['settings' => ['call_button_label' => ['bn' => '']]])->assertSessionHasNoErrors();
        $this->get('/?locale=bn')->assertSee('aria-label="Phone the clinic"', false);
        $this->seed(CallButtonSettingsSeeder::class);
        $this->assertSame('Phone the clinic', SiteSetting::firstOrFail()->call_button_label['en']);
        $this->assertSame('+১ (২০২) ৫৫৫-০১২৩', SiteSetting::firstOrFail()->call_phone_number);
    }

    public function test_number_validation_and_private_editing_preserve_the_previous_value(): void
    {
        $this->put('/admin/settings/clinic', ['settings' => ['call_phone_number' => '+12025550123']])->assertSessionHasNoErrors();
        foreach (['javascript:alert(1)', 'tel:+12025550123', '+12;ext=4567', '12+3456789', '123', str_repeat('1', 16), ['en' => '+12025550123']] as $number) {
            $this->put('/admin/settings/clinic', ['settings' => ['call_phone_number' => $number]])->assertSessionHasErrors('settings.call_phone_number');
        }
        $this->assertSame('+12025550123', SiteSetting::firstOrFail()->call_phone_number);
        $this->put('/admin/settings/clinic', ['settings' => ['call_button_label' => ['en' => '<script>label</script>']]])->assertSessionHasNoErrors();
        $this->get('/')->assertDontSee('<script>label</script>', false)->assertSee('aria-label="&lt;script&gt;label&lt;/script&gt;"', false);
        auth()->user()->forceFill(['is_admin' => false])->save();
        $this->put('/admin/settings/clinic', ['settings' => ['call_phone_number' => '+12025550124']])->assertForbidden();
        auth()->logout();
        $this->put('/admin/settings/clinic', ['settings' => ['call_phone_number' => '+12025550124']])->assertRedirect('/admin/login');
        $this->assertSame('+12025550123', SiteSetting::firstOrFail()->call_phone_number);
    }
}
