<?php

namespace App\Http\Requests\Admin;

use App\Models\PostSlugRedirect;
use App\Services\ContentRegistry;
use Illuminate\Validation\Rule;

final class ArticleRequest extends ContentEditorRequest
{
    protected function prepareForValidation(): void
    {
        $post = $this->input('post');
        if (! is_array($post)) {
            return;
        }
        if (is_array($post['body'] ?? null)) {
            foreach ($post['body'] as $locale => &$paragraphs) {
                if (! is_array($paragraphs)) {
                    continue;
                }
                // Invalid nested/non-string items remain for validation; only blank strings are removed.
                $paragraphs = array_values(array_filter(array_map(fn ($paragraph) => is_string($paragraph) ? trim($paragraph) : $paragraph, $paragraphs), fn ($paragraph) => $paragraph !== '' && $paragraph !== null));
                if ($locale === 'bn' && $paragraphs === []) {
                    $paragraphs = null;
                }
            }
            unset($paragraphs);
        }
        $this->merge(['post' => $post]);
    }

    protected function getRedirectUrl(): string
    {
        return $this->isMethod('POST') ? route('admin.articles.create') : route('admin.articles.edit', $this->route('post'));
    }

    public function rules(): array
    {
        $creating = $this->isMethod('POST');
        $fields = array_keys(app(ContentRegistry::class)->fields('Post'));
        $rules = $this->ownerRules('Post', 'post', $fields);
        if ($creating) {
            foreach ($rules as $key => &$fieldRules) {
                if ($key !== 'post.sort_order') {
                    $fieldRules = array_values(array_filter($fieldRules, fn ($rule) => $rule !== 'sometimes'));
                }
            }
            unset($fieldRules);
            $rules['post'][] = 'required';
        }
        $id = $this->route('post')?->id;
        $rules['post.slug'][] = Rule::unique('posts', 'slug')->ignore($id);
        $rules['post.slug'][] = function ($attribute, $value, $fail) use ($id) {
            if (is_string($value) && PostSlugRedirect::where('slug', $value)->when($id, fn ($query) => $query->where('post_id', '!=', $id))->exists()) {
                $fail('This slug is reserved by another article\'s existing links.');
            }
        };
        $rules['post.category_id'][] = Rule::exists('categories', 'id')->where('for_blog', true);
        $rules['confirm_slug_change'] = ['sometimes', 'accepted'];

        return $rules;
    }
}
