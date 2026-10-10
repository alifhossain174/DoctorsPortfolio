<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;

final class CareCollectionRequest extends ContentEditorRequest
{
    protected function prepareForValidation(): void
    {
        $item = $this->input('item');
        if (is_array($item) && isset($item['target'])) {
            $item['target'] = $this->normalizeLinks($item['target']);
            $this->merge(['item' => $item]);
        }
    }

    protected function getRedirectUrl(): string
    {
        $prefix = 'admin.'.$this->route('collection');

        return $this->isMethod('POST') ? route($prefix.'.create') : route($prefix.'.edit', $this->route('item'));
    }

    public function rules(): array
    {
        $definition = config('care_collections')[$this->route('collection')];
        $creating = $this->isMethod('POST');
        $fields = [...$definition['fields'], $definition['state']];
        if ($creating && $definition['owner'] === 'Service') {
            $fields[] = 'slug';
        }
        if ($creating && $definition['owner'] === 'ConsultationOption') {
            $fields[] = 'key';
        }
        $rules = $this->ownerRules($definition['owner'], 'item', $fields);
        if ($creating) {
            foreach ($rules as &$fieldRules) {
                $fieldRules = array_values(array_filter($fieldRules, fn ($rule) => $rule !== 'sometimes'));
            }
            unset($fieldRules);
            $rules['item'][] = 'required';
        }
        if ($definition['owner'] === 'CareCard') {
            $rules['item.target.en'][] = 'in:services,diagnostics';
            $rules['item.target.bn'][] = 'in:services,diagnostics';
        }
        if ($creating && $definition['owner'] === 'Service') {
            $rules['item.slug'][] = Rule::unique('services', 'slug');
        }
        if ($creating && $definition['owner'] === 'ConsultationOption') {
            $rules['item.key'][] = 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/';
            $rules['item.key'][] = Rule::unique('consultation_options', 'key');
        }
        $rules['confirm_disable'] = ['sometimes', 'accepted'];

        return $rules;
    }
}
