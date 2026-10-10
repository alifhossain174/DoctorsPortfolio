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
        $facts['department_full'] = app()->getLocale() === 'en' ? 'Department of '.$facts['department'] : $facts['department'];
        $facts['clinic_name_sentence'] = app()->getLocale() === 'en' ? str_replace('Iui', 'IUI', ucwords(strtolower($facts['clinic_name']))) : $facts['clinic_name'];
        $facts['address_short'] = preg_replace('/, Bangladesh$|, বাংলাদেশ$/u', '', $facts['address']);
        $facts = array_merge($facts, array_intersect_key($context, array_flip(ContentFormat::TOKENS)));

        return preg_replace_callback('/\{([^{}]+)\}/u', fn ($match) => $facts[$match[1]] ?? '', $text);
    }
}
