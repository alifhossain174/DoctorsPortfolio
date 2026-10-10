<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

final class ContentFormat implements ValidationRule
{
    public const TOKENS = ['doctor_name', 'credential', 'credential_lower', 'department', 'department_full', 'institution', 'clinic_name', 'clinic_name_sentence', 'address', 'address_short', 'consultation_hours', 'friday_note', 'year', 'stock_notice', 'page_label'];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        preg_match_all('/\{([^{}]+)\}/u', (string) $value, $matches);
        if (array_diff($matches[1], self::TOKENS)) {
            $fail('This text contains an unsupported placeholder.');
        }
    }
}
