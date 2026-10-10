<?php

namespace App\Http\Requests\Admin;

final class NavigationRequest extends ContentEditorRequest
{
    protected function getRedirectUrl(): string
    {
        return $this->isMethod('POST') ? route('admin.navigation.create') : route('admin.navigation.edit', $this->route('navigationItem'));
    }

    protected function prepareForValidation(): void
    {
        $item = $this->input('item');
        if (is_array($item) && isset($item['target'])) {
            $item['target'] = $this->normalizeLinks($item['target']);
            $this->merge(['item' => $item]);
        }
    }

    public function rules(): array
    {
        $rules = $this->ownerRules('NavigationItem', 'item', ['label', 'target', 'placement', 'sort_order', 'is_visible']);
        if ($this->isMethod('POST')) {
            foreach ($rules as &$fieldRules) {
                $fieldRules = array_values(array_filter($fieldRules, fn ($rule) => $rule !== 'sometimes'));
            }
            $rules['item'][] = 'required';
        }
        $record = $this->route('navigationItem');
        if ($record && ! str_starts_with($record->key, 'custom:')) {
            $rules['item.placement'][] = 'in:'.$record->placement;
        }

        return $rules;
    }
}
