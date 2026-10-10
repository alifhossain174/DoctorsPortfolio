<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use App\Services\ContentRegistry;
use App\Services\EditorialManager;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

final class CategoryController extends AdminController
{
    public function index(Request $request)
    {
        $filter = $request->validate(['q' => ['nullable', 'string', 'max:100'], 'scope' => ['nullable', 'in:blog,gallery'], 'page' => ['nullable', 'integer', 'min:1', 'max:999999']]);
        $categories = Category::withCount(['posts', 'galleryImages'])
            ->when($filter['q'] ?? null, fn ($query, $q) => $query->where(fn ($query) => $query->where('label->en', 'like', '%'.$q.'%')->orWhere('label->bn', 'like', '%'.$q.'%')->orWhere('slug', 'like', '%'.$q.'%')))
            ->when($filter['scope'] ?? null, fn ($query, $scope) => $query->where($scope === 'blog' ? 'for_blog' : 'for_gallery', true))
            ->orderBy('sort_order')->orderBy('id')->paginate(config('editorial.admin_per_page'))->appends(array_intersect_key($filter, ['q' => true, 'scope' => true]));

        return view('admin.categories.index', compact('categories', 'filter'));
    }

    public function create()
    {
        return $this->form(new Category(['for_blog' => true, 'for_gallery' => false, 'sort_order' => (Category::max('sort_order') ?? -1) + 1]));
    }

    public function edit(Category $category)
    {
        return $this->form($category->loadCount(['posts', 'galleryImages']));
    }

    private function form(Category $category)
    {
        return view('admin.categories.form', ['category' => $category, 'definition' => app(ContentRegistry::class)->fields('Category'),
            'targets' => Category::whereKeyNot($category->id ?? 0)->orderBy('sort_order')->orderBy('id')->get()]);
    }

    public function store(CategoryRequest $request, EditorialManager $manager)
    {
        try {
            $category = $manager->saveCategory(null, $request->validated('category'));
        } catch (ValidationException $error) {
            throw $error->redirectTo(route('admin.categories.create'));
        } catch (\Throwable) {
            return redirect()->route('admin.categories.create')->withErrors(['category' => 'The category could not be created. Please retry.'])->withInput();
        }

        return redirect()->route('admin.categories.edit', $category)->with('status', 'Category created.');
    }

    public function update(CategoryRequest $request, Category $category, EditorialManager $manager)
    {
        try {
            $manager->saveCategory($category, $request->validated('category', []));
        } catch (ValidationException $error) {
            throw $error->redirectTo(route('admin.categories.edit', $category));
        } catch (\Throwable) {
            return redirect()->route('admin.categories.edit', $category)->withErrors(['category' => 'Changes could not be saved. No changes were committed; please retry.'])->withInput();
        }

        return redirect()->route('admin.categories.edit', $category)->with('status', 'Category saved.');
    }

    public function reassign(Request $request, Category $category, EditorialManager $manager)
    {
        try {
            $data = $request->validate(['blog_category_id' => ['nullable', 'integer', Rule::exists('categories', 'id')->where('for_blog', true)], 'gallery_category_id' => ['nullable', 'integer', Rule::exists('categories', 'id')->where('for_gallery', true)], 'confirm_reassign' => ['accepted']]);
            $manager->reassign($category, $data);
        } catch (ValidationException $error) {
            throw $error->redirectTo(route('admin.categories.edit', $category));
        } catch (\Throwable) {
            return redirect()->route('admin.categories.edit', $category)->withErrors(['category' => 'Reassignment failed. No references were changed; please retry.']);
        }

        return redirect()->route('admin.categories.edit', $category)->with('status', 'Content explicitly reassigned. The category is retained until you choose to delete it.');
    }

    public function destroy(Request $request, Category $category, EditorialManager $manager)
    {
        try {
            $request->validate(['confirm_delete' => ['accepted']]);
            $manager->deleteCategory($category);
        } catch (ValidationException $error) {
            throw $error->redirectTo(route('admin.categories.edit', $category));
        } catch (\Throwable) {
            return redirect()->route('admin.categories.edit', $category)->withErrors(['category' => 'The category could not be deleted. Please retry.']);
        }

        return redirect()->route('admin.categories.index')->with('status', 'Unused category deleted.');
    }
}
