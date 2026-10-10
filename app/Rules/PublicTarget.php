<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

final class PublicTarget implements ValidationRule
{
    public const ROUTES = ['home', 'about', 'services', 'diagnostics', 'blog', 'gallery', 'contact'];

    public function __construct(private bool $externalOnly = false) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $this->externalOnly && in_array($value, self::ROUTES, true)) {
            return;
        }
        if (! self::safeExternal($value)) {
            $fail('Use an approved internal page key or an HTTP(S) URL without credentials.');
        }
    }

    public static function safeExternal(mixed $value): bool
    {
        return is_string($value) && filter_var($value, FILTER_VALIDATE_URL)
            && in_array(strtolower(parse_url($value, PHP_URL_SCHEME) ?? ''), ['http', 'https'], true)
            && parse_url($value, PHP_URL_USER) === null && parse_url($value, PHP_URL_PASS) === null;
    }
}
