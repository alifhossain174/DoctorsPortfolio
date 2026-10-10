<?php

namespace App\Services;

use App\Models\ContentField;
use App\Models\SiteSetting;
use App\Rules\ContentFormat;
use App\Rules\PublicTarget;
use Illuminate\Support\Collection;

/** Request-local database content; no seed/source reads or static copy fallback. */
final class WebsiteContent
{
    public readonly ?SiteSetting $settings;

    private Collection $fields;

    public function __construct()
    {
        $this->settings = SiteSetting::with('portrait')->where('key', 'primary')->first();
        $this->fields = ContentField::with('mediaAsset')->get()->keyBy('key');
    }

    public function setting(string $field): string
    {
        return $this->settings?->localized($field) ?? '';
    }

    public function text(string $key, array $context = []): string
    {
        $value = $this->fields->get($key)?->value;

        return $this->format(app(LocalizedContent::class)->resolve(is_array($value) ? $value : null), $context);
    }

    public function count(string $key): int
    {
        return max(0, min(24, (int) ($this->fields->get($key)?->value ?? 0)));
    }

    public function image(string $key): string
    {
        return $this->fields->get($key)?->mediaAsset?->url() ?? '';
    }

    public function target(string $target, array $parameters = []): string
    {
        return in_array($target, PublicTarget::ROUTES, true) ? app(PublicUrl::class)->route($target, $target === 'contact' ? array_intersect_key($parameters, ['kind' => true]) : []) : $this->external($target);
    }

    public function external(string $url): string
    {
        return PublicTarget::safeExternal($url) ? $url : '';
    }

    public function format(string $text, array $context = []): string
    {
        $facts = [];
        foreach (['doctor_name', 'credential', 'department', 'institution', 'clinic_name', 'address', 'consultation_hours', 'friday_note', 'stock_notice'] as $key) {
            $facts[$key] = $this->setting($key);
        }
        $facts['year'] = now()->format('Y');
        $facts['credential_lower'] = app()->getLocale() === 'en' ? lcfirst($facts['credential']) : $facts['credential'];
        $departmentFormat = app(LocalizedContent::class)->resolve($this->fields->get('shared.department_format')?->value);
        $facts['department_full'] = str_replace('{department}', $facts['department'], $departmentFormat);
        $words = preg_split('/\s+/u', trim($facts['clinic_name']), -1, PREG_SPLIT_NO_EMPTY);
        $facts['clinic_name_sentence'] = app()->getLocale() === 'en'
            ? implode(' ', array_map(fn ($word, $index) => $index > 0 && $index < count($words) - 1 && mb_strlen($word) <= 4 && $word === mb_strtoupper($word) ? $word : mb_convert_case($word, MB_CASE_TITLE, 'UTF-8'), $words, array_keys($words)))
            : $facts['clinic_name'];
        $facts['address_short'] = preg_replace('/,\s*[^,]+$/u', '', $facts['address']);
        $facts = array_merge($facts, array_intersect_key($context, array_flip(ContentFormat::TOKENS)));

        return preg_replace_callback('/\{([^{}]+)\}/u', fn ($match) => $facts[$match[1]] ?? '', $text);
    }
}
