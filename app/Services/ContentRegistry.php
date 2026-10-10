<?php

namespace App\Services;

use App\Rules\ContentFormat;
use App\Rules\PublicTarget;
use App\Rules\RequiredPlaceholder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

final class ContentRegistry
{
    public const ICONS = ['🤰', '✿', '♡', '🔬', '✚', '✳'];

    public function fields(string $owner): array
    {
        $fields = config('cms_models.'.$owner, config('private_content_fields.'.$owner, []));
        foreach ($fields as $name => &$definition) {
            $rules = [];
            $this->addRules($rules, $name, $definition);
            $definition['rules'] = $rules;
        }

        return $fields;
    }

    public function definition(string $key): array
    {
        // Dot-containing keys are literal keys, not nested configuration paths.
        $definition = config('content_fields')[$key] ?? null;
        if ($definition === null) {
            throw ValidationException::withMessages(['key' => 'Unknown content field.']);
        }

        $definition['control'] = $definition['localized'] ? 'English / বাংলা' : $definition['type'];
        $definition['display_label'] = str_replace(['.', '_'], ' ', $key);
        $rules = [];
        $this->addRules($rules, 'value', $definition);
        $definition['rules'] = $rules;

        return $definition;
    }

    public function validate(string $owner, array $attributes): array
    {
        $fields = $this->fields($owner);
        if ($extra = array_diff(array_keys($attributes), array_keys($fields))) {
            throw ValidationException::withMessages([reset($extra) => 'This field is not editable.']);
        }
        $rules = [];
        foreach ($fields as $name => $definition) {
            $this->addRules($rules, $name, $definition);
        }
        if ($owner === 'ContentField') {
            $rules['key'] = ['required', 'string', 'max:100'];
            $definition = $this->definition($attributes['key'] ?? '');
            $this->addRules($rules, 'value', $definition);
            if ($attributes['key'] === 'footer.copyright_format') {
                $rules['value.en'][] = new RequiredPlaceholder('year');
                $rules['value.bn'][] = new RequiredPlaceholder('year');
            }
            $rules['media_asset_id'] = $definition['type'] === 'media' ? ['nullable', 'integer', 'exists:media_assets,id'] : ['prohibited'];
            if ($definition['type'] === 'media') {
                $rules['value'] = ['prohibited'];
            }
            if (in_array($attributes['key'], ['home.primary_target', 'diagnostics.contact_target'], true)) {
                $rules['value.en'][] = 'in:contact';
                $rules['value.bn'][] = 'in:contact';
            }
        }
        if ($owner === 'SiteSetting') {
            $rules['cta_target.en'][] = 'in:contact';
            $rules['cta_target.bn'][] = 'in:contact';
        }
        if ($owner === 'CareCard') {
            $rules['target.en'][] = 'in:services,diagnostics';
            $rules['target.bn'][] = 'in:services,diagnostics';
        }
        if ($owner === 'Post' && ($attributes['status'] ?? null) === 'published') {
            $rules['published_at'] = ['required', 'date'];
        }
        if ($owner === 'PageSection') {
            $section = config('page_sections')[$attributes['key'] ?? ''] ?? null;
            if ($section === null) {
                throw ValidationException::withMessages(['key' => 'Unknown fixed section.']);
            }
            $rules['sort_order'][] = 'in:'.$section['position'];
            if (! $section['optional']) {
                $rules['is_visible'][] = 'accepted';
            }
        }

        $validated = Validator::make($attributes, $rules)->validate();
        if ($owner === 'MediaAsset') {
            $local = ! empty($attributes['path']);
            $external = ! empty($attributes['source_url']);
            if ($local === $external || ($local && ($attributes['disk'] ?? null) !== 'public-media') || ($external && ! empty($attributes['disk']))) {
                throw ValidationException::withMessages(['path' => 'Choose a public-media local image or an external URL, exclusively.']);
            }
            if ($local) {
                $extension = pathinfo($attributes['path'], PATHINFO_EXTENSION);
                $mime = ['jpg' => 'image/jpeg', 'png' => 'image/png', 'webp' => 'image/webp'][$extension] ?? null;
                if (! $mime || ($attributes['mime_type'] ?? null) !== $mime || empty($attributes['size']) || empty($attributes['width']) || empty($attributes['height']) || config('media.max_pixels') < $attributes['width'] * $attributes['height']) {
                    throw ValidationException::withMessages(['path' => 'A local image requires matching MIME, byte size and valid dimensions.']);
                }
            }
        }

        return $validated;
    }

    private function addRules(array &$rules, string $name, array $definition): void
    {
        $type = $definition['type'];
        $required = ($definition['required'] ?? false) ? 'required' : 'nullable';
        if ($definition['localized'] ?? false) {
            $rules[$name] = [$required, 'array:en,bn'];
            $rules[$name.'.en'] = [$required, ...$this->valueRules($type, $definition)];
            $rules[$name.'.bn'] = ['nullable', ...$this->valueRules($type, $definition)];
            if ($type === 'paragraphs') {
                $rules[$name.'.en.*'] = ['required', 'string', 'max:15000'];
                $rules[$name.'.bn.*'] = ['required', 'string', 'max:15000'];
            }
        } else {
            $rules[$name] = [$required, ...($definition['validation'] ?? $this->valueRules($type, $definition))];
        }
    }

    private function valueRules(string $type, array $definition): array
    {
        return match ($type) {
            'paragraphs' => ['array', 'min:1', 'max:100'],
            'icon' => ['string', 'in:'.implode(',', self::ICONS)],
            'target' => ['string', 'max:2048', new PublicTarget],
            'url' => ['string', 'max:4096', new PublicTarget(externalOnly: true)],
            'format' => ['string', 'max:'.($definition['max_length'] ?? 15000), new ContentFormat],
            'integer' => ['integer', 'min:0', 'max:24'],
            'boolean' => ['boolean'],
            'media' => [],
            default => ['string', 'max:'.($definition['max_length'] ?? ($type === 'textarea' ? 15000 : 500))],
        };
    }
}
