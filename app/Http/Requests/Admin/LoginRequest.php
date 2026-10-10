<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

final class LoginRequest extends FormRequest
{
    protected $redirectRoute = 'admin.login';

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (is_string($this->input('email'))) {
            $this->merge(['email' => mb_strtolower(trim($this->input('email')))]);
        }
    }

    public function rules(): array
    {
        return ['email' => ['required', 'string', 'email', 'max:255'], 'password' => ['required', 'string', 'max:1024']];
    }

    public function authenticate(): void
    {
        $identity = 'admin-login:'.hash('sha256', $this->validated('email').'|'.$this->ip());
        $address = 'admin-login-ip:'.hash('sha256', $this->ip());
        if (RateLimiter::tooManyAttempts($identity, 5) || RateLimiter::tooManyAttempts($address, 20)) {
            $seconds = max(RateLimiter::availableIn($identity), RateLimiter::availableIn($address));
            throw ValidationException::withMessages(['email' => 'Too many sign-in attempts. Try again in '.$seconds.' seconds.'])->redirectTo(route('admin.login'));
        }

        if (! Auth::attempt(['email' => $this->validated('email'), 'password' => $this->validated('password'), 'is_admin' => true])) {
            RateLimiter::hit($identity, 60);
            RateLimiter::hit($address, 60);
            throw ValidationException::withMessages(['email' => 'These credentials could not be verified.'])->redirectTo(route('admin.login'));
        }

        RateLimiter::clear($identity);
        // Do not clear the IP bucket: other identities may be under attack.
        $this->session()->regenerate();
    }
}
