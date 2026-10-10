@include('public.parts.hero', ['eyebrow' => $label, 'title' => $site->text('shared.gallerytitle'), 'description' => $site->text('shared.gallerydesc')])
<main><section><div class="wrap">@include('public.parts.filters')@include('public.parts.gallery')<p class="small-note">{{ $site->setting('stock_notice') }}</p></div></section>@include('public.parts.cta')</main>
