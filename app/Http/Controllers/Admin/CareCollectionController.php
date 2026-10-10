<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CareCollectionRequest;
use App\Models\CmsModel;
use App\Services\CareCollectionManager;
use App\Services\CmsEditor;
use App\Services\ContentRegistry;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

final class CareCollectionController extends AdminController
{
    private function definition(Request $request): array
    {
        $key = $request->route('collection'); // Code-owned route default; never a model name from input.
        abort_unless(isset(config('care_collections')[$key]), 404);

        return [...config('care_collections')[$key], 'key' => $key, 'prefix' => 'admin.'.$key];
    }

    private function record(Request $request, array $definition): CmsModel
    {
        return $definition['model']::findOrFail($request->route('item'));
    }

    public function index(Request $request)
    {
        $definition = $this->definition($request);

        return view('admin.care.index', ['definition' => $definition, 'records' => $definition['model']::orderBy('sort_order')->orderBy('id')->get()]);
    }

    public function create(Request $request)
    {
        $definition = $this->definition($request);
        $model = $definition['model'];

        return $this->form($definition, new $model([$definition['state'] => true]), true);
    }

    public function edit(Request $request)
    {
        $definition = $this->definition($request);

        return $this->form($definition, $this->record($request, $definition), false);
    }

    private function form(array $definition, CmsModel $record, bool $creating)
    {
        return view('admin.care.form', ['definition' => $definition, 'record' => $record, 'creating' => $creating, 'registry' => app(ContentRegistry::class)->fields($definition['owner']), 'editor' => app(CmsEditor::class)]);
    }

    public function store(CareCollectionRequest $request, CareCollectionManager $manager)
    {
        $definition = $this->definition($request);
        try {
            $record = $manager->create($definition, $request->validated('item'));
        } catch (ValidationException $error) {
            throw $error->redirectTo(route($definition['prefix'].'.create'));
        } catch (\Throwable) {
            return redirect()->route($definition['prefix'].'.create')->withErrors(['item' => 'The record could not be created. No changes were committed; please retry.'])->withInput();
        }

        return redirect()->route($definition['prefix'].'.edit', $record->id)->with('status', ucfirst($definition['singular']).' created at the end of its own list.');
    }

    public function update(CareCollectionRequest $request, CareCollectionManager $manager)
    {
        $definition = $this->definition($request);
        $record = $this->record($request, $definition);
        try {
            $manager->update($definition, $record, $request->validated('item', []), $request->boolean('confirm_disable'));
        } catch (ValidationException $error) {
            throw $error->redirectTo(route($definition['prefix'].'.edit', $record->id));
        } catch (ModelNotFoundException $error) {
            throw $error;
        } catch (\Throwable) {
            return redirect()->route($definition['prefix'].'.edit', $record->id)->withErrors(['item' => 'Changes could not be saved. No changes were committed; please retry.'])->withInput();
        }

        return redirect()->route($definition['prefix'].'.edit', $record->id)->with('status', ucfirst($definition['singular']).' saved.');
    }

    public function visibility(Request $request, CareCollectionManager $manager)
    {
        $definition = $this->definition($request);
        $record = $this->record($request, $definition);
        try {
            $data = $request->validate(['state' => ['required', 'boolean'], 'confirm_disable' => ['sometimes', 'accepted']]);
            $manager->update($definition, $record, [$definition['state'] => $data['state']], $request->boolean('confirm_disable'));
        } catch (ValidationException $error) {
            throw $error->redirectTo(route($definition['prefix'].'.index'));
        } catch (ModelNotFoundException $error) {
            throw $error;
        } catch (\Throwable) {
            return redirect()->route($definition['prefix'].'.index')->withErrors(['item' => 'Visibility could not be changed. Please retry.']);
        }

        return redirect()->route($definition['prefix'].'.index')->with('status', ucfirst($definition['singular']).($data['state'] ? ' enabled.' : ' disabled.'));
    }

    public function move(Request $request, CareCollectionManager $manager)
    {
        $definition = $this->definition($request);
        $record = $this->record($request, $definition);
        try {
            $data = $request->validate(['direction' => ['required', 'in:up,down']]);
            $changed = $manager->move($definition, $record, $data['direction']);
        } catch (ValidationException $error) {
            throw $error->redirectTo(route($definition['prefix'].'.index'));
        } catch (ModelNotFoundException $error) {
            throw $error;
        } catch (\Throwable) {
            return redirect()->route($definition['prefix'].'.index')->withErrors(['item' => 'Order could not be changed. No changes were committed; please retry.']);
        }

        return redirect()->route($definition['prefix'].'.index')->with('status', $changed ? 'Display order saved.' : 'This record is already at that end of the list.');
    }

    public function destroy(Request $request, CareCollectionManager $manager)
    {
        $definition = $this->definition($request);
        $record = $this->record($request, $definition);
        try {
            $request->validate(['confirm_delete' => ['accepted']]);
            $manager->delete($definition, $record);
        } catch (ValidationException $error) {
            throw $error->redirectTo(route($definition['prefix'].'.edit', $record->id));
        } catch (ModelNotFoundException $error) {
            throw $error;
        } catch (\Throwable) {
            return redirect()->route($definition['prefix'].'.edit', $record->id)->withErrors(['item' => 'The record could not be deleted. No changes were committed; please retry.']);
        }

        return redirect()->route($definition['prefix'].'.index')->with('status', ucfirst($definition['singular']).' deleted.'.($definition['owner'] === 'ConsultationOption' ? ' Existing inquiry snapshots are retained.' : ' Other content collections are unchanged.'));
    }
}
