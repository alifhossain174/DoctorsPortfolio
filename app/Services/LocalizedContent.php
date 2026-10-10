<?php

namespace App\Services;

final class LocalizedContent
{
    /** Localized strings and structured paragraph bodies share this fallback. */
    public function resolve(?array $value, ?string $locale = null, mixed $empty = ''): mixed
    {
        $locale = in_array($locale ?? app()->getLocale(), ['en', 'bn'], true) ? ($locale ?? app()->getLocale()) : 'en';
        foreach (array_unique([$locale, 'en']) as $language) {
            $candidate = $value[$language] ?? null;
            if ($this->hasContent($candidate)) {
                return is_array($candidate) ? $this->clean($candidate) : $candidate;
            }
        }

        return $empty;
    }

    private function hasContent(mixed $value): bool
    {
        if (is_string($value)) {
            return trim($value) !== '';
        }
        if (is_array($value)) {
            return count(array_filter($value, fn ($item) => $this->hasContent($item))) > 0;
        }

        return false;
    }

    private function clean(array $value): array
    {
        $result = [];
        foreach ($value as $key => $item) {
            if ($this->hasContent($item)) {
                $result[$key] = is_array($item) ? $this->clean($item) : $item;
            }
        }

        return array_is_list($value) ? array_values($result) : $result;
    }
}
