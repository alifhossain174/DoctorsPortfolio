<?php

namespace App\Http\Requests\Admin;

use App\Rules\PasswordBytes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\Password;

final class PasswordRequest extends FormRequest
{
    protected $errorBag = 'password';

    protected $redirectRoute = 'admin.profile.edit';

    public function authorize(): bool
    {
        return Gate::allows('access-admin');
    }

    public function rules(): array
    {
        return [
            'current_password' => ['required', 'string', 'max:1024', 'current_password:web'],
            'password' => ['required', 'string', 'confirmed', 'different:current_password', new PasswordBytes, Password::min(12)->mixedCase()->numbers()->symbols()],
        ];
    }
}
