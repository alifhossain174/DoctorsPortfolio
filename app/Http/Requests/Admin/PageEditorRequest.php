<?php

namespace App\Http\Requests\Admin;

use App\Services\CmsEditor;
use App\Services\ContentRegistry;

final class PageEditorRequest extends ContentEditorRequest
{
    protected function getRedirectUrl(): string
    {
        return route('admin.pages.edit', $this->route('page'));
    }

    protected function prepareForValidation(): void
    {
        $this->prepareFields(app(CmsEditor::class)->pageFields($this->route('page')));
    }

    public function rules(): array
    {
        $page = $this->route('page');
        $editable = array_keys(array_filter(app(ContentRegistry::class)->fields('Page'), fn ($definition) => $definition['editable'] ?? true));
        $rules = [...$this->ownerRules('Page', 'page', $editable), ...$this->fieldRules(app(CmsEditor::class)->pageFields($page))];
        $sections = $page->sections()->get();
        $rules['sections'] = $sections->isNotEmpty() ? ['sometimes', 'array:'.implode(',', $sections->pluck('id')->all())] : ['prohibited'];
        foreach ($sections as $section) {
            $rules['sections.'.$section->id] = ['sometimes', 'array:is_visible'];
            $rules['sections.'.$section->id.'.is_visible'] = ['sometimes', 'required', 'boolean'];
            if (! config('page_sections')[$section->key]['optional']) {
                $rules['sections.'.$section->id.'.is_visible'][] = 'accepted';
            }
        }

        return $rules;
    }
}
