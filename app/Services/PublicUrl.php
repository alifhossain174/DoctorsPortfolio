<?php

namespace App\Services;

use Illuminate\Http\Request;

/** Only code-owned routes and validated purpose-specific query values are carried forward. */
final class PublicUrl
{
    public function route(string $name, array $parameters = []): string
    {
        return route($name, array_merge(['locale' => app()->getLocale()], $parameters));
    }

    public function current(Request $request, string $locale, array $categories = []): string
    {
        $name = $request->route()->getName();
        $parameters = in_array($name, ['article', 'admin.articles.preview'], true) ? ['post' => $request->route('post')] : [];

        return route($name, array_merge($parameters, $this->query($request, $name, $categories), ['locale' => $locale]));
    }

    public function query(Request $request, string $page, array $categories = []): array
    {
        $query = [];
        if (in_array($page, ['blog', 'gallery'], true)) {
            $category = $request->query('category');
            if ($page === 'blog' && $request->query->has('category')) {
                abort_unless(is_string($category) && in_array($category, array_merge(['all'], $categories), true), 404);
            }
            if (is_string($category) && in_array($category, array_merge(['all'], $categories), true)) {
                $query['category'] = $category;
            }
            $number = $request->query('page');
            if (is_string($number) && preg_match('/^[1-9][0-9]{0,5}$/D', $number)) {
                $query['page'] = (int) $number;
            }
        }
        if ($page === 'contact' && in_array($request->query('mode'), ['appointment', 'contact'], true)) {
            $query['mode'] = $request->query('mode');
        }

        return $query;
    }
}
