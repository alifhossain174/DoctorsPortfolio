<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\PageEditorRequest;
use App\Models\Page;
use App\Models\Post;
use App\Services\CmsEditor;
use App\Services\CmsEditorSaver;
use App\Services\ContentRegistry;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class PageContentController extends AdminController
{
    public function index()
    {
        return view('admin.pages.index', ['pages' => Page::orderBy('id')->get()]);
    }

    public function edit(Page $page, CmsEditor $editor)
    {
        $page->load(['sections', 'ogImage']);
        $article = $page->key === 'article' ? Post::published()->orderBy('sort_order')->first() : null;

        return view('admin.pages.edit', [
            'page' => $page, 'fields' => $editor->pageFields($page), 'editor' => $editor, 'mediaChoices' => $editor->mediaChoices(),
            'definitions' => app(ContentRegistry::class)->fields('Page'),
            'previewUrl' => $page->key === 'article' ? ($article ? route('article', ['post' => $article->slug, 'locale' => 'en']) : null) : route($page->key, ['locale' => 'en']),
        ]);
    }

    public function update(PageEditorRequest $request, Page $page, CmsEditor $editor, CmsEditorSaver $saver)
    {
        $data = $request->validated();
        try {
            DB::transaction(function () use ($data, $page, $editor, $saver) {
                if (isset($data['page'])) {
                    $saver->update($page, $data['page'], 'page');
                }
                foreach ($editor->pageFields($page) as $field) {
                    if (isset($data['fields'][$field->id])) {
                        $saver->update($field, $data['fields'][$field->id], 'fields.'.$field->id);
                    }
                }
                foreach ($page->sections()->get() as $section) {
                    if (isset($data['sections'][$section->id])) {
                        $saver->update($section, $data['sections'][$section->id], 'sections.'.$section->id);
                    }
                }
            });
        } catch (ValidationException $error) {
            throw $error->redirectTo(route('admin.pages.edit', $page));
        } catch (\Throwable) {
            return redirect()->route('admin.pages.edit', $page)->withErrors(['page' => 'Changes could not be saved. No changes were committed; please retry.'])->withInput();
        }

        return redirect()->route('admin.pages.edit', $page)->with('status', 'Page content saved. The next public request uses these values.');
    }
}
