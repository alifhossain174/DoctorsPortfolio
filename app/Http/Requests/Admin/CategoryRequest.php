<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;

final class CategoryRequest extends ContentEditorRequest
{
    protected function getRedirectUrl(): string
    {
        return $this->isMethod('POST') ? route('admin.categories.create') : route('admin.categories.edit', $this->route('category'));
    }

    public function rules(): array
    {
        $creating = $this->isMethod('POST');
        $rules = $this->ownerRules('Category', 'category', ['label', 'sort_order', 'for_blog', 'for_gallery', ...($creating ? ['slug'] : [])]);
        if ($creating) {
            foreach ($rules as $key => &$fieldRules) {
                if ($key !== 'category.sort_order') {
                    $fieldRules = array_values(array_filter($fieldRules, fn ($rule) => $rule !== 'sometimes'));
                }
            }
            unset($fieldRules);
            $rules['category'][] = 'required';
            $rules['category.slug'][] = Rule::unique('categories', 'slug');
        }

        return $rules;
    }
}
