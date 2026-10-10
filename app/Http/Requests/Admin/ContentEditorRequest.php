<?php

namespace App\Http\Requests\Admin;

use App\Rules\PublicTarget;
use App\Services\ContentRegistry;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;

abstract class ContentEditorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('access-admin');
    }

    protected function normalizeLinks(mixed $translations): mixed
    {
        if (! is_array($translations)) {
            return $translations;
        }
        foreach ($translations as &$value) {
            if (is_array($value) && ! array_diff(array_keys($value), ['kind', 'route', 'url'])) {
                $kind = $value['kind'] ?? '';
                $selected = $value[$kind === 'internal' ? 'route' : 'url'] ?? '';
                $empty = $selected === '' || $selected === null;
                if ($kind === 'internal' && ($empty || (is_string($selected) && in_array($selected, PublicTarget::ROUTES, true)))) {
                    $value = $selected;
                } elseif ($kind === 'external' && ($empty || PublicTarget::safeExternal($selected))) {
                    $value = $selected;
                }
            }
            if (is_string($value)) {
                $value = trim($value);
            }
        }
        unset($value);

        return $translations;
    }

    protected function ownerRules(string $owner, string $prefix, array $allowed): array
    {
        $rules = [$prefix => $allowed ? ['sometimes', 'array:'.implode(',', $allowed)] : ['prohibited']];
        foreach (app(ContentRegistry::class)->fields($owner) as $field => $definition) {
            if (! in_array($field, $allowed, true)) {
                continue;
            }
            foreach ($definition['rules'] as $name => $fieldRules) {
                $rules[$prefix.'.'.$name] = ['sometimes', ...$fieldRules];
            }
        }

        return $rules;
    }

    protected function fieldRules(Collection $records): array
    {
        $rules = ['fields' => $records->isNotEmpty() ? ['sometimes', 'array:'.implode(',', $records->pluck('id')->all())] : ['prohibited']];
        foreach ($records as $field) {
            $definition = app(ContentRegistry::class)->definition($field->key);
            $prefix = 'fields.'.$field->id;
            if ($definition['type'] === 'media') {
                $rules[$prefix] = ['sometimes', 'array:media_asset_id'];
                $rules[$prefix.'.media_asset_id'] = ['sometimes', 'nullable', 'integer', 'exists:media_assets,id'];
            } else {
                $rules[$prefix] = ['sometimes', 'array:value'];
                foreach ($definition['rules'] as $key => $fieldRules) {
                    $rules[$prefix.'.'.$key] = ['sometimes', ...$fieldRules];
                }
            }
        }

        return $rules;
    }

    protected function prepareFields(Collection $records): void
    {
        $fields = $this->input('fields');
        if (! is_array($fields)) {
            return;
        }
        foreach ($records as $record) {
            if ((config('content_fields')[$record->key]['type'] ?? '') === 'target' && isset($fields[$record->id]['value'])) {
                $fields[$record->id]['value'] = $this->normalizeLinks($fields[$record->id]['value']);
            }
        }
        $this->merge(['fields' => $fields]);
    }
}
