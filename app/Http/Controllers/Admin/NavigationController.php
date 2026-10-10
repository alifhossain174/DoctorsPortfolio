<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\NavigationRequest;
use App\Models\NavigationItem;
use App\Rules\PublicTarget;
use App\Services\CmsEditorSaver;
use App\Services\ContentRegistry;
use App\Services\WebsiteContent;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

final class NavigationController extends AdminController
{
    public function index(Request $request)
    {
        $filter = $request->validate(['placement' => ['nullable', Rule::in(['header', 'footer'])], 'page' => ['nullable', 'integer', 'min:1']]);
        $placement = $filter['placement'] ?? 'header';

        return view('admin.navigation.index', ['placement' => $placement, 'items' => NavigationItem::where('placement', $placement)->orderBy('sort_order')->orderBy('id')->paginate(25)->appends(['placement' => $placement])]);
    }

    public function create(Request $request)
    {
        $placement = in_array($request->query('placement'), ['header', 'footer'], true) ? $request->query('placement') : 'header';

        return $this->form(new NavigationItem(['placement' => $placement, 'sort_order' => 0, 'is_visible' => true]), true);
    }

    public function edit(NavigationItem $navigationItem)
    {
        return $this->form($navigationItem, false);
    }

    private function form(NavigationItem $item, bool $creating)
    {
        $target = $item->localized('target', 'en');
        $preview = in_array($target, PublicTarget::ROUTES, true) ? route($target, ['locale' => 'en']) : route('home', ['locale' => 'en']);

        return view('admin.navigation.form', ['item' => $item, 'creating' => $creating, 'definitions' => app(ContentRegistry::class)->fields('NavigationItem'), 'previewUrl' => $preview]);
    }

    public function store(NavigationRequest $request)
    {
        try {
            $item = NavigationItem::create(['key' => 'custom:'.bin2hex(random_bytes(16)), ...$request->validated('item')]);
        } catch (ValidationException $error) {
            throw $error->redirectTo(route('admin.navigation.create'));
        } catch (\Throwable) {
            return redirect()->route('admin.navigation.create')->withErrors(['item' => 'The navigation item could not be saved. Please retry.'])->withInput();
        }
        app()->forgetInstance(WebsiteContent::class);

        return redirect()->route('admin.navigation.edit', $item)->with('status', 'Navigation item created.');
    }

    public function update(NavigationRequest $request, NavigationItem $navigationItem, CmsEditorSaver $saver)
    {
        try {
            $saver->update($navigationItem, $request->validated('item', []), 'item');
        } catch (ValidationException $error) {
            throw $error->redirectTo(route('admin.navigation.edit', $navigationItem));
        } catch (\Throwable) {
            return redirect()->route('admin.navigation.edit', $navigationItem)->withErrors(['item' => 'The navigation item could not be saved. Please retry.'])->withInput();
        }

        return redirect()->route('admin.navigation.edit', $navigationItem)->with('status', 'Navigation item saved.');
    }

    public function destroy(Request $request, NavigationItem $navigationItem)
    {
        try {
            $request->validate(['confirm_delete' => ['accepted']]);
            if (! str_starts_with($navigationItem->key, 'custom:')) {
                throw ValidationException::withMessages(['item' => 'Original menu entries may be hidden, but their identity/placement is retained for shared label references.']);
            }
            $placement = $navigationItem->placement;
            $navigationItem->delete();
        } catch (ValidationException $error) {
            throw $error->redirectTo(route('admin.navigation.edit', $navigationItem));
        } catch (\Throwable) {
            return redirect()->route('admin.navigation.edit', $navigationItem)->withErrors(['item' => 'The navigation item could not be deleted. Please retry.']);
        }

        return redirect()->route('admin.navigation.index', ['placement' => $placement])->with('status', 'Custom navigation item deleted.');
    }
}
