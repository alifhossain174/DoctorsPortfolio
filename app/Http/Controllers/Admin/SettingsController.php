<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\SettingsEditorRequest;
use App\Models\SiteSetting;
use App\Services\CmsEditor;
use App\Services\CmsEditorSaver;
use App\Services\ContentRegistry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class SettingsController extends AdminController
{
    public function edit(Request $request, CmsEditor $editor)
    {
        $group = $request->query('group', 'doctor');
        abort_unless(is_string($group) && isset($editor->groups()[$group]), 404);
        $settings = SiteSetting::with('portrait')->where('key', 'primary')->firstOrFail();

        return view('admin.settings.edit', [
            'settings' => $settings, 'groups' => $editor->groups(), 'group' => $group, 'editor' => $editor,
            'settingDefinitions' => app(ContentRegistry::class)->fields('SiteSetting'),
            'fields' => $editor->groupFields($group), 'mediaChoices' => $editor->mediaChoices(),
        ]);
    }

    public function update(SettingsEditorRequest $request, CmsEditor $editor, CmsEditorSaver $saver, string $group)
    {
        $data = $request->validated();
        try {
            DB::transaction(function () use ($data, $editor, $saver, $group) {
                $settings = SiteSetting::where('key', 'primary')->firstOrFail();
                if (isset($data['settings'])) {
                    $saver->update($settings, $data['settings'], 'settings');
                }
                foreach ($editor->groupFields($group) as $field) {
                    if (isset($data['fields'][$field->id])) {
                        $saver->update($field, $data['fields'][$field->id], 'fields.'.$field->id);
                    }
                }
            });
        } catch (ValidationException $error) {
            throw $error->redirectTo(route('admin.settings.edit', ['group' => $group]));
        } catch (\Throwable) {
            return redirect()->route('admin.settings.edit', ['group' => $group])->withErrors(['settings' => 'Changes could not be saved. No changes were committed; please retry.'])->withInput();
        }

        return redirect()->route('admin.settings.edit', ['group' => $group])->with('status', 'Shared website settings saved. The next public request uses these values.');
    }
}
