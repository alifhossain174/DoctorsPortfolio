<?php

namespace App\Http\Requests\Admin;

use App\Services\CmsEditor;

final class SettingsEditorRequest extends ContentEditorRequest
{
    protected function getRedirectUrl(): string
    {
        return route('admin.settings.edit', ['group' => $this->route('group')]);
    }

    protected function prepareForValidation(): void
    {
        $editor = app(CmsEditor::class);
        abort_unless(isset($editor->groups()[$this->route('group')]), 404);
        $settings = $this->input('settings');
        if (is_array($settings) && isset($settings['cta_target'])) {
            $settings['cta_target'] = $this->normalizeLinks($settings['cta_target']);
            $this->merge(['settings' => $settings]);
        }
        $this->prepareFields($editor->groupFields($this->route('group')));
    }

    public function rules(): array
    {
        $editor = app(CmsEditor::class);
        $group = $this->route('group');

        return [...$this->ownerRules('SiteSetting', 'settings', $editor->groups()[$group]['settings']), ...$this->fieldRules($editor->groupFields($group))];
    }
}
