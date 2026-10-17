<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

final class CallPhoneNumber implements ValidationRule
{
    public static function dialable(?string $value): string
    {
        $value = strtr(trim($value ?? ''), array_combine(mb_str_split('০১২৩৪৫৬৭৮৯'), str_split('0123456789')));
        if (! preg_match('/^\+?[0-9 ()-]+$/D', $value)) {
            return '';
        }
        $number = str_replace([' ', '(', ')', '-'], '', $value);

        return preg_match('/^\+?[0-9]{7,15}$/D', $number) ? $number : '';
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || self::dialable($value) === '') {
            $fail('Enter a phone number with 7–15 digits and an optional leading + country code. Spaces, parentheses and hyphens are allowed.');
        }
    }
}
