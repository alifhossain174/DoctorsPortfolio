<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

final class ProfileRequest extends FormRequest
{
    protected $errorBag = 'profile';

    protected $redirectRoute = 'admin.profile.edit';

    public function authorize(): bool
    {
        return Gate::allows('access-admin');
    }

    protected function prepareForValidation(): void
    {
        foreach (['name', 'email'] as $field) {
            if (is_string($this->input($field))) {
                $this->merge([$field => $field === 'email' ? mb_strtolower(trim($this->input($field))) : trim($this->input($field))]);
            }
        }
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->user()->id)],
            'current_password' => ['required', 'string', 'max:1024', 'current_password:web'],
        ];
    }
}
