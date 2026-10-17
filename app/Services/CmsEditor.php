<?php

namespace App\Services;

use App\Models\ContentField;
use App\Models\MediaAsset;
use App\Models\Page;
use App\Rules\ContentFormat;
use Illuminate\Support\Collection;

final class CmsEditor
{
    public function groups(): array
    {
        $groups = config('cms_editor.groups');
        foreach (array_keys(config('content_fields')) as $key) {
            if (str_starts_with($key, 'ui.')) {
                $groups['ui']['fields'][] = $key;
            }
            if ((str_starts_with($key, 'shared.') && $key !== 'shared.brand_target') || $key === 'meta.title_format') {
                $groups['information']['fields'][] = $key;
            }
        }

        return $groups;
    }

    public function sharedKeys(): array
    {
        return array_merge(...array_column($this->groups(), 'fields'));
    }

    public function groupFields(string $group): Collection
    {
        return ContentField::with('mediaAsset')->whereIn('key', $this->groups()[$group]['fields'])->orderBy('id')->get();
    }

    public function pageKeys(string $page): array
    {
        return array_values(array_filter(array_keys(config('content_fields')), fn ($key) => str_starts_with($key, $page.'.') && ! in_array($key, $this->sharedKeys(), true)));
    }

    public function pageFields(Page $page): Collection
    {
        return ContentField::with('mediaAsset')->where('page_id', $page->id)->whereIn('key', $this->pageKeys($page->key))->orderBy('id')->get();
    }

    public function mediaChoices(): Collection
    {
        return MediaAsset::orderBy('id')->get(['id', 'title', 'alt_text', 'path', 'disk', 'source_url']);
    }

    public function label(string $key): string
    {
        return config('cms_editor.labels')[$key] ?? ucfirst(str_replace(['_', '.'], ' ', preg_replace('/([a-z])([A-Z])/', '$1 $2', preg_replace('/^[^.]+\./', '', $key))));
    }

    public function help(string $key, array $definition): string
    {
        if ($key === 'call_phone_number') {
            return 'Used by the footer phone link, the Contact / Appointment call button and round call buttons. Include the country code, for example +880…. Leave blank to hide all call links. One number serves both languages.';
        }
        if ($key === 'call_button_label') {
            return 'Visible Contact / Appointment call button text and the round button accessible/hover label. Empty Bangla uses English.';
        }
        if ($key === 'blog.blogdesc') {
            return 'Retained from the original bilingual demo, which did not render this description. It is not a runtime fallback.';
        }
        if ($key === 'footer.copyright_format') {
            return 'Required placeholder: {year}. Code supplies the current year, so never replace it with a fixed year.';
        }
        if (in_array($key, ['home.primary_target', 'diagnostics.contact_target', 'cta_target'], true)) {
            return 'Appointment inquiries stay on the contact page; an inquiry is not a confirmed booking.';
        }
        if ($definition['type'] === 'format') {
            return 'Plain text with optional approved fact placeholders: '.implode(', ', array_map(fn ($token) => '{'.$token.'}', ContentFormat::TOKENS)).'. No template code or HTML rendering.';
        }
        if ($definition['type'] === 'target') {
            return 'Choose a named public page or an HTTP(S) URL. Empty Bangla uses the English destination.';
        }
        if ($definition['type'] === 'url') {
            return 'HTTP(S) only, without embedded credentials. Empty Bangla uses the English URL.';
        }
        if ($definition['type'] === 'integer') {
            return '0–24 items. Initially 3, in existing source order; collection visibility/publication still applies.';
        }
        if (str_contains($key, 'alt')) {
            return 'Describe the actual visible image. Changing alternative text does not verify photo identity or rights.';
        }

        return 'Plain text; English is required where marked. Line breaks and Bangla UTF-8 are saved safely.';
    }

    public function sections(Collection $fields): Collection
    {
        return $fields->groupBy(function ($field) {
            $type = config('content_fields')[$field->key]['type'];
            if ($type === 'media' || str_contains($field->key, '_alt')) {
                return 'Images & alternative text';
            }
            if ($type === 'target') {
                return 'Button destinations';
            }
            if ($type === 'integer') {
                return 'Homepage preview counts';
            }

            return 'Headings & page copy';
        });
    }
}
