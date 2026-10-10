<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;

final class GalleryRequest extends ContentEditorRequest
{
    protected function getRedirectUrl(): string
    {
        return $this->isMethod('POST') ? route('admin.gallery.create') : route('admin.gallery.edit', $this->route('image'));
    }

    public function rules(): array
    {
        $definition = config('gallery.editor');
        $rules = $this->ownerRules('GalleryImage', 'item', [...$definition['fields'], $definition['state']]);
        if ($this->isMethod('POST')) {
            foreach ($rules as &$fieldRules) {
                $fieldRules = array_values(array_filter($fieldRules, fn ($rule) => $rule !== 'sometimes'));
            }
            unset($fieldRules);
            $rules['item'][] = 'required';
        }
        $rules['item.category_id'][] = Rule::exists('categories', 'id')->where('for_gallery', true);
        $rules['confirm_disable'] = ['sometimes', 'accepted'];

        return $rules;
    }
}
