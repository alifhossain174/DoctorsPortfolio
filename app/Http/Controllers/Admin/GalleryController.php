<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\GalleryRequest;
use App\Models\Category;
use App\Models\GalleryImage;
use App\Models\MediaAsset;
use App\Services\CareCollectionManager;
use App\Services\ContentRegistry;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

final class GalleryController extends AdminController
{
    private function filters(Request $request): array
    {
        return $request->validate(['q' => ['nullable', 'string', 'max:100'], 'category' => ['nullable', 'integer', Rule::exists('categories', 'id')->where('for_gallery', true)], 'visibility' => ['nullable', 'in:visible,hidden'], 'page' => ['nullable', 'integer', 'min:1', 'max:999999']]);
    }

    public function index(Request $request)
    {
        $filter = $this->filters($request);
        $images = GalleryImage::with(['category', 'mediaAsset'])
            ->when($filter['q'] ?? null, fn ($query, $term) => $query->where(fn ($query) => $query->where('caption->en', 'like', '%'.$term.'%')->orWhere('caption->bn', 'like', '%'.$term.'%')))
            ->when($filter['category'] ?? null, fn ($query, $id) => $query->where('category_id', $id))
            ->when($filter['visibility'] ?? null, fn ($query, $visibility) => $query->where('is_visible', $visibility === 'visible'))
            ->orderBy('sort_order')->orderBy('id')->paginate(config('gallery.admin_per_page'))->appends(array_intersect_key($filter, array_flip(['q', 'category', 'visibility'])));

        return view('admin.gallery.index', [
            'images' => $images, 'filter' => $filter,
            'categories' => Category::where('for_gallery', true)->orderBy('sort_order')->orderBy('id')->get(),
            'firstId' => GalleryImage::orderBy('sort_order')->orderBy('id')->value('id'),
            'lastId' => GalleryImage::orderByDesc('sort_order')->orderByDesc('id')->value('id'),
        ]);
    }

    public function create()
    {
        return $this->form(new GalleryImage(['is_visible' => true, 'on_homepage' => false]));
    }

    public function edit(GalleryImage $image)
    {
        return $this->form($image->load(['category', 'mediaAsset']));
    }

    private function form(GalleryImage $image)
    {
        return view('admin.gallery.form', [
            'image' => $image, 'definition' => app(ContentRegistry::class)->fields('GalleryImage'),
            'categories' => Category::where('for_gallery', true)->orderBy('sort_order')->orderBy('id')->get(),
            'mediaChoices' => MediaAsset::orderBy('id')->get(['id', 'title', 'alt_text', 'path', 'disk', 'source_url']),
        ]);
    }

    public function store(GalleryRequest $request, CareCollectionManager $manager)
    {
        try {
            $image = $manager->create(config('gallery.editor'), $request->validated('item'));
        } catch (ValidationException $error) {
            throw $error->redirectTo(route('admin.gallery.create'));
        } catch (\Throwable) {
            return redirect()->route('admin.gallery.create')->withErrors(['item' => 'The gallery entry could not be created. No changes were committed; please retry.'])->withInput();
        }

        return redirect()->route('admin.gallery.edit', $image)->with('status', 'Gallery entry added at the end of the full collection.');
    }

    public function update(GalleryRequest $request, GalleryImage $image, CareCollectionManager $manager)
    {
        try {
            $manager->update(config('gallery.editor'), $image, $request->validated('item', []), $request->boolean('confirm_disable'));
        } catch (ValidationException $error) {
            throw $error->redirectTo(route('admin.gallery.edit', $image));
        } catch (ModelNotFoundException $error) {
            throw $error;
        } catch (\Throwable) {
            return redirect()->route('admin.gallery.edit', $image)->withErrors(['item' => 'Changes could not be saved. No changes were committed; please retry.'])->withInput();
        }

        return redirect()->route('admin.gallery.edit', $image)->with('status', 'Gallery entry saved. Shared media remains unchanged.');
    }

    public function move(Request $request, GalleryImage $image, CareCollectionManager $manager)
    {
        try {
            $filter = $this->filters($request);
            $data = $request->validate(['direction' => ['required', 'in:up,down']]);
            $changed = $manager->move(config('gallery.editor'), $image, $data['direction']);
        } catch (ValidationException $error) {
            throw $error->redirectTo(route('admin.gallery.index'));
        } catch (ModelNotFoundException $error) {
            throw $error;
        } catch (\Throwable) {
            return redirect()->route('admin.gallery.index')->withErrors(['item' => 'Order could not be saved. No changes were committed; please retry.']);
        }

        return redirect()->route('admin.gallery.index', $filter)->with('status', $changed ? 'Gallery order saved across the full collection.' : 'This entry is already at that end of the collection.');
    }

    public function visibility(Request $request, GalleryImage $image, CareCollectionManager $manager)
    {
        try {
            $filter = $this->filters($request);
            $data = $request->validate(['state' => ['required', 'boolean'], 'confirm_disable' => ['sometimes', 'accepted']]);
            $manager->update(config('gallery.editor'), $image, ['is_visible' => $data['state']], $request->boolean('confirm_disable'));
        } catch (ValidationException $error) {
            throw $error->redirectTo(route('admin.gallery.index'));
        } catch (ModelNotFoundException $error) {
            throw $error;
        } catch (\Throwable) {
            return redirect()->route('admin.gallery.index')->withErrors(['item' => 'Visibility could not be changed. Please retry.']);
        }

        return redirect()->route('admin.gallery.index', $filter)->with('status', $data['state'] ? 'Gallery entry is visible.' : 'Gallery entry is hidden; its homepage preference and media are retained.');
    }

    public function destroy(Request $request, GalleryImage $image, CareCollectionManager $manager)
    {
        try {
            $request->validate(['confirm_delete' => ['accepted']]);
            $manager->delete(config('gallery.editor'), $image);
        } catch (ValidationException $error) {
            throw $error->redirectTo(route('admin.gallery.edit', $image));
        } catch (ModelNotFoundException $error) {
            throw $error;
        } catch (\Throwable) {
            return redirect()->route('admin.gallery.edit', $image)->withErrors(['item' => 'The gallery entry could not be deleted. No changes were committed; please retry.']);
        }

        return redirect()->route('admin.gallery.index')->with('status', 'Gallery entry deleted. Shared image records and files were retained.');
    }
}
