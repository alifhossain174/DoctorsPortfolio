<?php

namespace App\Services;

use App\Models\CmsModel;
use App\Models\ContentField;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

/** Reusable update boundary for future registry-backed editors; no public write endpoint. */
final class AdminContentWriter
{
    public function update(CmsModel $record, array $input): CmsModel
    {
        Gate::authorize('access-admin');

        return DB::transaction(function () use ($record, $input) {
            $record = $record->newQuery()->lockForUpdate()->findOrFail($record->getKey());
            $registry = app(ContentRegistry::class);
            $fields = $registry->fields(class_basename($record));
            foreach ($input as $field => $value) {
                $definition = $fields[$field] ?? null;
                if (! $definition || ! ($definition['editable'] ?? true)) {
                    throw ValidationException::withMessages([$field => 'This field is not editable.']);
                }
                if ($record instanceof ContentField && $field === 'value') {
                    $definition = $registry->definition($record->key);
                }
                if ($definition['localized'] ?? false) {
                    if (! is_array($value) || array_diff(array_keys($value), ['en', 'bn'])) {
                        throw ValidationException::withMessages([$field => 'Use only English and Bangla language fields.']);
                    }
                    $current = $record->getAttribute($field) ?? [];
                    foreach ($value as $language => $translation) {
                        if (($definition['type'] ?? '') === 'paragraphs' && is_array($translation)) {
                            // Reject nested/non-string paragraphs; do not silently drop invalid data.
                            foreach ($translation as $paragraph) {
                                if (! is_string($paragraph)) {
                                    throw ValidationException::withMessages([$field.'.'.$language => 'Paragraphs must be plain text.']);
                                }
                            }
                            $translation = array_values(array_filter(array_map('trim', $translation), fn ($paragraph) => $paragraph !== ''));
                            if ($language === 'bn' && $translation === []) {
                                $translation = null;
                            }
                        } elseif (is_string($translation)) {
                            $translation = trim($translation);
                        }
                        $current[$language] = $translation;
                    }
                    $value = $current; // Omitted language stays unchanged, including deliberate empty values.
                } elseif (is_string($value)) {
                    $value = trim($value);
                }
                $record->setAttribute($field, $value);
            }
            // CmsModel's saving hook validates the complete merged record via the registry.
            $record->save();

            return $record;
        });
    }
}
