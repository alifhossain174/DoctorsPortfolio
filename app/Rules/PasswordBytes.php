<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/** Prevent bcrypt's silent truncation and invalid null-byte input on PHP 8.2. */
final class PasswordBytes implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (is_string($value) && (strlen($value) > 72 || str_contains($value, "\0"))) {
            $fail('Use a shorter password (up to 72 UTF-8 bytes) without null characters.');
        }
    }
}
