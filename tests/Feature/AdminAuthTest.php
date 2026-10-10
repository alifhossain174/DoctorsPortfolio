<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\AdminController;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        if (DB::connection()->getDatabaseName() !== 'doctor_portfolio_test') {
            throw new \RuntimeException('Admin tests require doctor_portfolio_test.');
        }
        DB::beginTransaction();
        $this->beforeApplicationDestroyed(fn () => DB::rollBack());
    }

    private function password(): string
    {
        // Random disposable credentials only; never a fixed/default owner account.
        return 'Aa9!'.bin2hex(random_bytes(18));
    }

    private function account(string $password, bool $admin = true): User
    {
        $user = User::create(['name' => 'Test administrator', 'email' => Str::uuid().'@example.test', 'password' => $password]);
        $user->forceFill(['is_admin' => $admin])->save();

        return $user;
    }

    public function test_guests_use_the_admin_login_route_and_future_controllers_are_guarded(): void
    {
        foreach (['/admin', '/admin/profile'] as $path) {
            $this->get($path)->assertRedirect('/admin/login');
        }
        $this->patch('/admin/profile', [])->assertRedirect('/admin/login');
        $this->put('/admin/profile/password', [])->assertRedirect('/admin/login');
        $this->post('/admin/logout')->assertRedirect('/admin/login');
        $this->get('/admin/login')->assertOk()->assertSee('Welcome back')->assertSee('/build/assets/admin-', false);
        $this->get('/login')->assertNotFound();
        $this->get('/register')->assertNotFound();
        $this->get('/admin/register')->assertNotFound();
        Route::get('/future-admin-controller', FutureGuardedController::class);
        $this->get('/future-admin-controller')->assertRedirect('/admin/login');
    }

    public function test_non_admin_is_denied_every_private_action_and_cannot_sign_in(): void
    {
        $password = $this->password();
        $user = $this->account($password, false);
        $this->post('/admin/login', ['email' => $user->email, 'password' => $password])->assertSessionHasErrors(['email' => 'These credentials could not be verified.']);
        $this->assertGuest();
        $this->actingAs($user);
        foreach (['/admin', '/admin/profile'] as $path) {
            $this->get($path)->assertForbidden();
        }
        $this->patch('/admin/profile', [])->assertForbidden();
        $this->put('/admin/profile/password', [])->assertForbidden();
        $this->post('/admin/logout')->assertForbidden();
        Route::get('/future-admin-controller', FutureGuardedController::class);
        $this->get('/future-admin-controller')->assertForbidden();
    }

    public function test_successful_login_regenerates_session_and_dashboard_has_only_real_links(): void
    {
        $password = $this->password();
        $user = $this->account($password);
        $this->get('/admin/login');
        $before = session()->getId();
        $this->withSession(['url.intended' => 'https://example.org/untrusted'])->post('/admin/login', ['email' => mb_strtoupper($user->email), 'password' => $password])->assertRedirect('/admin');
        $this->assertAuthenticatedAs($user);
        $this->assertNotSame($before, session()->getId());
        $this->assertFalse(session()->has('url.intended'));
        $this->get('/admin')->assertOk()->assertSee('Your website, at a glance')->assertSee('No inquiries received yet')->assertSee('/admin/appointments')->assertSee('/admin/contact-messages')->assertSee('href="http://127.0.0.1:8000/admin/articles"', false)->assertSee('href="http://127.0.0.1:8000/admin/gallery"', false);
        $this->get('/admin/profile')->assertOk()->assertSee($user->email);
        $this->get('/admin/login')->assertRedirect('/admin');
    }

    public function test_failed_login_is_generic_and_password_is_never_flashed(): void
    {
        $user = $this->account($this->password());
        foreach ([$user->email, Str::uuid().'@example.test'] as $email) {
            $this->withHeader('Referer', 'https://example.org/untrusted')->post('/admin/login', ['email' => $email, 'password' => $this->password()])->assertRedirect('/admin/login')->assertSessionHasErrors(['email' => 'These credentials could not be verified.'])->assertSessionMissing('_old_input.password');
            $this->assertGuest();
        }
    }

    public function test_malformed_login_input_can_be_redisplayed_safely(): void
    {
        $this->post('/admin/login', ['email' => ['unexpected' => 'array'], 'password' => $this->password()])->assertRedirect('/admin/login')->assertSessionHasErrors('email');
        $this->get('/admin/login')->assertOk();
        $this->assertGuest();
    }

    public function test_login_throttles_identity_and_ip_then_allows_retry_after_expiry(): void
    {
        $password = $this->password();
        $user = $this->account($password);
        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->post('/admin/login', ['email' => $user->email, 'password' => $this->password()])->assertSessionHasErrors('email');
        }
        $this->post('/admin/login', ['email' => $user->email, 'password' => $password])->assertSessionHasErrors('email');
        $this->assertGuest();
        $this->get('/admin/login')->assertSee('Too many sign-in attempts.');
        $this->travel(61)->seconds();
        $this->post('/admin/login', ['email' => $user->email, 'password' => $password])->assertRedirect('/admin');
        $this->assertAuthenticatedAs($user);
    }

    public function test_ip_throttling_covers_rotating_email_attempts(): void
    {
        for ($attempt = 0; $attempt < 20; $attempt++) {
            $this->post('/admin/login', ['email' => Str::uuid().'@example.test', 'password' => $this->password()])->assertSessionHasErrors('email');
        }
        $password = $this->password();
        $user = $this->account($password);
        $this->post('/admin/login', ['email' => $user->email, 'password' => $password])->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_logout_invalidates_session_and_regenerates_csrf(): void
    {
        $this->actingAs($this->account($this->password()));
        $this->get('/admin');
        $before = session()->getId();
        $token = session()->token();
        $this->withSession(['private-test-marker' => 'discard'])->post('/admin/logout')->assertRedirect('/admin/login')->assertSessionMissing('private-test-marker');
        $this->assertGuest();
        $this->assertNotSame($before, session()->getId());
        $this->assertNotSame($token, session()->token());
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_profile_requires_current_password_and_whitelists_fields(): void
    {
        $password = $this->password();
        $user = $this->account($password);
        $hash = $user->password;
        $this->actingAs($user)->from('/admin/profile')->patch('/admin/profile', ['name' => 'Changed name', 'email' => $user->email, 'current_password' => $this->password()])->assertSessionHasErrorsIn('profile', 'current_password');
        $this->assertSame('Test administrator', $user->fresh()->name);
        $email = Str::uuid().'@example.test';
        $this->patch('/admin/profile', ['name' => ' Changed name ', 'email' => mb_strtoupper($email), 'current_password' => $password, 'is_admin' => false, 'password' => $this->password()])->assertRedirect('/admin/profile');
        $this->assertSame('Changed name', $user->fresh()->name);
        $this->assertSame($email, $user->fresh()->email);
        $this->assertTrue($user->fresh()->is_admin);
        $this->assertSame($hash, $user->fresh()->password);
    }

    public function test_password_change_verifies_current_password_strength_hashing_and_stale_sessions(): void
    {
        $old = $this->password();
        $new = $this->password();
        $user = $this->account($old);
        $beforeHash = $user->password;
        $this->actingAs($user)->get('/admin');
        $this->from('/admin/profile')->put('/admin/profile/password', ['current_password' => $this->password(), 'password' => $new, 'password_confirmation' => $new])->assertSessionHasErrorsIn('password', 'current_password')->assertSessionMissing('_old_input.current_password')->assertSessionMissing('_old_input.password');
        $this->assertSame($beforeHash, $user->fresh()->password);
        $weak = bin2hex(random_bytes(6));
        $this->put('/admin/profile/password', ['current_password' => $old, 'password' => $weak, 'password_confirmation' => $weak])->assertSessionHasErrorsIn('password', 'password');
        $this->put('/admin/profile/password', ['current_password' => $old, 'password' => $new, 'password_confirmation' => $new])->assertRedirect('/admin/profile');
        $this->assertTrue(Hash::check($new, $user->fresh()->password));
        $this->assertFalse(Hash::check($old, $user->fresh()->password));
        $this->get('/admin')->assertOk();
        $this->withSession(['password_hash_web' => $beforeHash])->get('/admin')->assertRedirect('/admin/login');
        $this->assertGuest();
    }

    public function test_interactive_provisioning_is_hidden_validated_and_non_destructive(): void
    {
        $password = $this->password();
        $email = Str::uuid().'@example.test';
        $this->artisan('admin:create')->expectsQuestion('Name', ' Test owner ')->expectsQuestion('Email', mb_strtoupper($email))->expectsQuestion('Password', $password)->expectsQuestion('Confirm password', $password)->expectsOutput('Admin created. Sign in at /admin/login.')->assertSuccessful();
        $user = User::where('email', $email)->firstOrFail();
        $this->assertSame('Test owner', $user->name);
        $this->assertTrue($user->is_admin);
        $this->assertTrue(Hash::check($password, $user->password));
        $hash = $user->password;
        $this->artisan('admin:create')->expectsQuestion('Name', 'Another owner')->expectsQuestion('Email', $email)->expectsQuestion('Password', $password)->expectsQuestion('Confirm password', $password)->assertFailed();
        $this->assertSame($hash, $user->fresh()->password);
        $this->assertSame(1, User::count());
        $this->artisan('admin:create', ['--no-interaction' => true])->assertFailed();
        $this->assertSame(1, User::count());
    }

    public function test_web_login_requires_csrf(): void
    {
        $this->app['env'] = 'local';
        try {
            $this->post('/admin/login', [])->assertStatus(419);
        } finally {
            $this->app['env'] = 'testing';
        }
    }

    public function test_provisioning_rejects_weak_mismatched_and_overlong_passwords_without_creating_users(): void
    {
        $valid = $this->password();
        foreach ([[bin2hex(random_bytes(6)), null], [$valid, $this->password()], [$valid.str_repeat('a', 80), null]] as [$password, $confirmation]) {
            $this->artisan('admin:create')->expectsQuestion('Name', 'Test owner')->expectsQuestion('Email', Str::uuid().'@example.test')->expectsQuestion('Password', $password)->expectsQuestion('Confirm password', $confirmation ?? $password)->assertFailed();
        }
        $this->assertSame(0, User::count());
    }
}

class FutureGuardedController extends AdminController
{
    public function __invoke()
    {
        return response('Guarded');
    }
}
