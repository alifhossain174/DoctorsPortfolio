<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\PublicPageController;
use App\Http\Requests\Admin\ArticleRequest;
use App\Models\Category;
use App\Models\MediaAsset;
use App\Models\Post;
use App\Services\ContentRegistry;
use App\Services\EditorialManager;
use App\Services\WebsiteContent;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

final class ArticleController extends AdminController
{
    public function index(Request $request)
    {
        $filter = $request->validate(['q' => ['nullable', 'string', 'max:100'], 'status' => ['nullable', 'in:draft,published,future'], 'category' => ['nullable', 'integer', Rule::exists('categories', 'id')->where('for_blog', true)], 'page' => ['nullable', 'integer', 'min:1', 'max:999999']]);
        $posts = Post::with(['category', 'mediaAsset'])
            ->when($filter['q'] ?? null, fn ($query, $q) => $query->where(fn ($query) => $query->where('title->en', 'like', '%'.$q.'%')->orWhere('title->bn', 'like', '%'.$q.'%')->orWhere('slug', 'like', '%'.$q.'%')))
            ->when($filter['category'] ?? null, fn ($query, $id) => $query->where('category_id', $id))
            ->when(($filter['status'] ?? null) === 'draft', fn ($query) => $query->where('status', 'draft'))
            ->when(($filter['status'] ?? null) === 'published', fn ($query) => $query->published())
            ->when(($filter['status'] ?? null) === 'future', fn ($query) => $query->where('status', 'published')->where('published_at', '>', now()))
            ->orderBy('sort_order')->orderBy('id')->paginate(config('editorial.admin_per_page'))->appends(array_intersect_key($filter, ['q' => true, 'status' => true, 'category' => true]));

        return view('admin.articles.index', ['posts' => $posts, 'filter' => $filter, 'categories' => Category::where('for_blog', true)->orderBy('sort_order')->orderBy('id')->get()]);
    }

    public function create()
    {
        return $this->form(new Post(['status' => 'draft', 'on_homepage' => false, 'sort_order' => (Post::max('sort_order') ?? -1) + 1]));
    }

    public function edit(Post $post)
    {
        return $this->form($post->load(['mediaAsset', 'ogImage', 'slugRedirects']));
    }

    private function form(Post $post)
    {
        return view('admin.articles.form', ['post' => $post, 'definition' => app(ContentRegistry::class)->fields('Post'),
            'categories' => Category::where('for_blog', true)->orderBy('sort_order')->orderBy('id')->get(),
            'mediaChoices' => MediaAsset::orderBy('id')->get(['id', 'title', 'alt_text', 'path', 'disk', 'source_url'])]);
    }

    public function store(ArticleRequest $request, EditorialManager $manager)
    {
        try {
            $post = $manager->savePost(null, $request->validated('post'));
        } catch (ValidationException $error) {
            throw $error->redirectTo(route('admin.articles.create'));
        } catch (\Throwable) {
            return redirect()->route('admin.articles.create')->withErrors(['post' => 'The article could not be created. Please retry.'])->withInput();
        }

        return redirect()->route('admin.articles.edit', $post)->with('status', 'Article created. Publication follows its status and timestamp.');
    }

    public function update(ArticleRequest $request, Post $post, EditorialManager $manager)
    {
        try {
            $manager->savePost($post, $request->validated('post', []), $request->boolean('confirm_slug_change'));
        } catch (ValidationException $error) {
            throw $error->redirectTo(route('admin.articles.edit', $post));
        } catch (\Throwable) {
            return redirect()->route('admin.articles.edit', $post)->withErrors(['post' => 'Changes could not be saved. No changes were committed; please retry.'])->withInput();
        }

        return redirect()->route('admin.articles.edit', $post)->with('status', 'Article saved.');
    }

    public function publication(Request $request, Post $post, EditorialManager $manager)
    {
        try {
            $data = $request->validate(['status' => ['required', 'in:draft,published'], 'confirm_publication' => ['accepted']]);
            $manager->savePost($post, ['status' => $data['status']]);
        } catch (ValidationException $error) {
            throw $error->redirectTo(route('admin.articles.edit', $post));
        } catch (\Throwable) {
            return redirect()->route('admin.articles.edit', $post)->withErrors(['post' => 'Publication state could not be changed. Please retry.']);
        }

        return redirect()->route('admin.articles.edit', $post)->with('status', 'Publication state saved. Future-dated articles remain private until their timestamp.');
    }

    public function preview(Request $request, WebsiteContent $site, Post $post)
    {
        $view = app(PublicPageController::class)->show($request, $site, 'article', $post->load(['category', 'mediaAsset', 'ogImage']), true);

        return response($view)->withHeaders(['X-Robots-Tag' => 'noindex, nofollow, nosnippet', 'Cache-Control' => 'private, no-store, max-age=0']);
    }

    public function destroy(Request $request, Post $post, EditorialManager $manager)
    {
        try {
            $request->validate(['confirm_delete' => ['accepted']]);
            $manager->deletePost($post);
        } catch (ValidationException $error) {
            throw $error->redirectTo(route('admin.articles.edit', $post));
        } catch (\Throwable) {
            return redirect()->route('admin.articles.edit', $post)->withErrors(['post' => 'The article could not be deleted. Please retry.']);
        }

        return redirect()->route('admin.articles.index')->with('status', 'Article and its old-slug mappings deleted. Shared media records/files remain.');
    }
}
