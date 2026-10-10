<header class="nav"><div class="wrap navin">
    <a class="brand" href="{{ $site->target($site->text('shared.brand_target')) }}"><span class="brandmark">{{ $site->settings?->brand_icon }}</span><span>{{ $site->setting('doctor_name') }}<span style="display:block;font-size:10px;letter-spacing:0;font-weight:600;color:#72847c">{{ $site->setting('subtitle') }}</span></span></a>
    <button class="menu" id="menuBtn" aria-label="{{ $site->text('ui.open_navigation') }}" data-open-label="{{ $site->text('ui.open_navigation') }}" data-close-label="{{ $site->text('ui.menu_close') }}" aria-expanded="false" aria-controls="navLinks">☰</button>
    <nav class="links" id="navLinks">
        @foreach ($navigation->get('header', collect())->where('is_visible', true) as $item)<a href="{{ $site->target($item->localized('target')) }}" @class(['active' => $item->localized('target') === ($page === 'article' ? 'blog' : $page)])>{{ $item->localized('label') }}</a>@endforeach
        <a class="lang" id="langBtn" href="{{ $urls->current(request(), $locale === 'en' ? 'bn' : 'en', $categories->pluck('slug')->all()) }}">{{ $site->text($locale === 'en' ? 'ui.switch_to_bn' : 'ui.switch_to_en') }}</a>
    </nav>
</div></header>
