@include('public.parts.hero', ['eyebrow' => $label, 'title' => $site->text('blog.blogheading'), 'description' => $site->text('blog.blogintro')])
<main><section><div class="wrap"><h3>{{ $site->text('blog.tagtitle') }}</h3>@include('public.parts.filters')@include('public.parts.articles')</div></section>@include('public.parts.cta')</main>
