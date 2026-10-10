@php
    $locale = app()->getLocale();
    $label = $pageRecord->localized('label');
    $metaTitle = $post?->localized('meta_title') ?: $pageRecord->localized('meta_title');
    $metaDescription = $post?->localized('meta_description') ?: $pageRecord->localized('meta_description');
    $sections = $pageRecord->sections->keyBy('key');
    $visible = fn ($key) => (bool) ($sections->get($page.'.'.$key)?->is_visible ?? false);
@endphp
<!doctype html>
<html lang="{{ $locale }}">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#205d54">
    <meta name="description" content="{{ $site->format($metaDescription, ['page_label' => $label]) }}">
    <title>{{ $site->format($metaTitle, ['page_label' => $label]) }}</title>
    <link rel="stylesheet" href="{{ asset('assets/style.css') }}">
    <script src="{{ asset('assets/public.js') }}" defer></script>
</head>
<body data-page="{{ $page }}"><div id="root">
    <div class="topbar"><div class="wrap"><span>📍 {{ $site->setting('address') }}</span><span>🕘 {{ $site->setting('consultation_hours') }} · {{ $site->setting('friday_note') }}</span></div></div>
    <header class="nav"><div class="wrap navin">
        <a class="brand" href="{{ $site->target($site->text('shared.brand_target')) }}"><span class="brandmark">{{ $site->settings?->brand_icon }}</span><span>{{ $site->setting('doctor_name') }}<span style="display:block;font-size:10px;letter-spacing:0;font-weight:600;color:#72847c">{{ $site->setting('subtitle') }}</span></span></a>
        <button class="menu" id="menuBtn" aria-label="{{ $site->text('ui.open_navigation') }}" aria-expanded="false" aria-controls="navLinks">☰</button>
        <nav class="links" id="navLinks">
            @foreach ($navigation->get('header', collect()) as $item)<a href="{{ $site->target($item->localized('target')) }}" @class(['active' => $item->localized('target') === ($page === 'article' ? 'blog' : $page)])>{{ $item->localized('label') }}</a>@endforeach
            <a class="lang" id="langBtn" href="{{ request()->fullUrlWithQuery(['lang' => $locale === 'en' ? 'bn' : 'en']) }}">{{ $site->text($locale === 'en' ? 'ui.switch_to_bn' : 'ui.switch_to_en') }}</a>
        </nav>
    </div></header>
    @include('public.pages.'.$page)
    <footer class="footer"><div class="wrap"><div class="footergrid">
        <div><div class="brand" style="color:white"><span class="brandmark" style="background:#387669">{{ $site->settings?->brand_icon }}</span>{{ $site->setting('doctor_name') }}</div><p>{{ $site->setting('footer_description') }}</p></div>
        <div><h3>{{ $site->text('ui.explore') }}</h3>@foreach ($navigation->get('footer', collect()) as $item)<a href="{{ $site->target($item->localized('target')) }}">{{ $item->localized('label') }}</a>@endforeach</div>
        <div><h3>{{ $navigation->get('header', collect())->firstWhere('key', 'header:contact')?->localized('label') }}</h3><p>{{ $site->setting('address') }}</p><p>{{ $site->setting('consultation_hours') }}<br>{{ $site->setting('friday_note') }}</p><a href="{{ $site->setting('facebook_url') }}" target="_blank" rel="noopener">{{ $site->text('footer.social_label') }}</a></div>
    </div><div class="footer-bottom">{{ $site->text('footer.copyright_format') }}</div></div></footer>
    <div class="gallery-lightbox" id="lightbox" role="dialog" aria-modal="true" aria-label="{{ $site->text('ui.gallery_preview') }}"><button id="closeLightbox" aria-label="{{ $site->text('ui.close_lightbox') }}">×</button><img id="lbImage" alt=""><p id="lbCaption"></p></div>
</div></body></html>
