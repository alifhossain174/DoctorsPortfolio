<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Post;
use App\Models\PostSlugRedirect;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

final class EditorialManager
{
    private function lockWebsite(): void
    {
        Gate::authorize('access-admin');
        SiteSetting::where('key', 'primary')->lockForUpdate()->firstOrFail();
    }

    public function saveCategory(?Category $category, array $input): Category
    {
        return DB::transaction(function () use ($category, $input) {
            $this->lockWebsite();
            $current = $category ? Category::lockForUpdate()->findOrFail($category->id) : new Category;
            $blog = (bool) ($input['for_blog'] ?? $current->for_blog);
            $gallery = (bool) ($input['for_gallery'] ?? $current->for_gallery);
            if (! $blog && ! $gallery) {
                throw ValidationException::withMessages(['category.for_blog' => 'Choose at least one applicability.']);
            }
            if ($current->exists && ((! $blog && $current->posts()->exists()) || (! $gallery && $current->galleryImages()->exists()))) {
                throw ValidationException::withMessages(['category' => 'Reassign existing content before removing its category applicability.']);
            }
            if ($current->exists) {
                app(CmsEditorSaver::class)->update($current, $input, 'category');
            } else {
                $input['sort_order'] ??= (Category::max('sort_order') ?? -1) + 1;
                $current = Category::create($this->withLocaleKeys('Category', $input));
            }
            app()->forgetInstance(WebsiteContent::class);

            return $current->fresh();
        }, 3);
    }

    public function reassign(Category $category, array $input): void
    {
        DB::transaction(function () use ($category, $input) {
            $this->lockWebsite();
            $current = Category::lockForUpdate()->findOrFail($category->id);
            foreach (['posts' => ['blog_category_id', 'for_blog'], 'galleryImages' => ['gallery_category_id', 'for_gallery']] as $relation => [$field, $flag]) {
                if (! $current->$relation()->exists()) {
                    continue;
                }
                $target = Category::whereKey($input[$field] ?? null)->where('id', '!=', $current->id)->where($flag, true)->lockForUpdate()->first();
                if (! $target) {
                    throw ValidationException::withMessages([$field => 'Choose another category with the required applicability.']);
                }
                $current->$relation()->update(['category_id' => $target->id, 'updated_at' => now()]);
            }
            app()->forgetInstance(WebsiteContent::class);
        }, 3);
    }

    public function deleteCategory(Category $category): void
    {
        DB::transaction(function () use ($category) {
            $this->lockWebsite();
            $category = Category::lockForUpdate()->findOrFail($category->id);
            if ($category->posts()->exists() || $category->galleryImages()->exists()) {
                throw ValidationException::withMessages(['category' => 'This category is in use. Explicitly reassign all content first.']);
            }
            $category->delete();
            app()->forgetInstance(WebsiteContent::class);
        }, 3);
    }

    private function withLocaleKeys(string $owner, array $input): array
    {
        foreach (app(ContentRegistry::class)->fields($owner) as $field => $definition) {
            if (($definition['localized'] ?? false) && is_array($input[$field] ?? null)) {
                $input[$field] = array_replace(['en' => null, 'bn' => null], $input[$field]);
            }
        }

        return $input;
    }

    public function savePost(?Post $post, array $input, bool $confirmedSlug = false): Post
    {
        return DB::transaction(function () use ($post, $input, $confirmedSlug) {
            $this->lockWebsite();
            $current = $post ? Post::lockForUpdate()->findOrFail($post->id) : new Post;
            $slug = $input['slug'] ?? $current->slug;
            if (Post::where('slug', $slug)->when($current->exists, fn ($query) => $query->whereKeyNot($current->id))->exists() || PostSlugRedirect::where('slug', $slug)->when($current->exists, fn ($query) => $query->where('post_id', '!=', $current->id))->exists()) {
                throw ValidationException::withMessages(['post.slug' => 'Choose a unique slug that is not reserved by another article.']);
            }
            $changed = $current->exists && $slug !== $current->slug;
            if ($changed && ! $confirmedSlug) {
                throw ValidationException::withMessages(['confirm_slug_change' => 'Confirm the explicit slug change; old links will redirect only when this article is public.']);
            }
            $category = Category::whereKey($input['category_id'] ?? $current->category_id)->where('for_blog', true)->lockForUpdate()->first();
            if (! $category) {
                throw ValidationException::withMessages(['post.category_id' => 'Choose a category enabled for articles.']);
            }
            if (($input['status'] ?? $current->status) === 'published' && ! ($input['published_at'] ?? $current->published_at)) {
                $input['published_at'] = now();
            }
            // Explicitly clearing a published timestamp also means publish now; no worker/auto-title slugging.
            if (($input['status'] ?? $current->status) === 'published' && array_key_exists('published_at', $input) && ! $input['published_at']) {
                $input['published_at'] = now();
            }
            $oldSlug = $current->slug;
            if ($current->exists) {
                app(CmsEditorSaver::class)->update($current, $input, 'post');
                if ($changed) {
                    PostSlugRedirect::where('post_id', $current->id)->where('slug', $slug)->delete(); // Safe return to own old slug.
                    PostSlugRedirect::firstOrCreate(['slug' => $oldSlug], ['post_id' => $current->id]);
                }
            } else {
                $input['sort_order'] ??= (Post::max('sort_order') ?? -1) + 1;
                $current = Post::create($this->withLocaleKeys('Post', $input));
            }
            app()->forgetInstance(WebsiteContent::class);

            return $current->fresh();
        }, 3);
    }

    public function deletePost(Post $post): void
    {
        DB::transaction(function () use ($post) {
            $this->lockWebsite();
            Post::lockForUpdate()->findOrFail($post->id)->delete(); // Only redirect rows cascade, never shared media files.
            app()->forgetInstance(WebsiteContent::class);
        }, 3);
    }
}
