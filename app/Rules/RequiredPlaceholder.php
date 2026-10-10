<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

final class RequiredPlaceholder implements ValidationRule
{
    public function __construct(private string $token) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (is_string($value) && trim($value) !== '' && ! str_contains($value, '{'.$this->token.'}')) {
            $fail('Include {'.$this->token.'} so the current value is generated automatically.');
        }
    }
}
