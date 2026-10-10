<?php

namespace App\Rules;

use App\Services\WebsiteContent;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

final class InquiryPhone implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Preserve plus, leading zeros, separators and Unicode decimal digits.
        if (! is_string($value) || ! preg_match('/^\+?[\p{Nd}\p{Zs}().-]+$/uD', $value) || ! preg_match('/\p{Nd}/u', $value)) {
            $fail(app(WebsiteContent::class)->text('contact.validation_invalid'));
        }
    }
}
