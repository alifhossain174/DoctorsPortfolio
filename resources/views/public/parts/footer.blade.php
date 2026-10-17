<footer class="footer"><div class="wrap"><div class="footergrid">
    <div><div class="brand" style="color:white"><span class="brandmark" style="background:#387669">{{ $site->settings?->brand_icon }}</span>{{ $site->setting('doctor_name') }}</div><p>{{ $site->setting('footer_description') }}</p></div>
    <div><h3>{{ $site->text('ui.explore') }}</h3>@foreach ($navigation->get('footer', collect())->where('is_visible', true) as $item)<a href="{{ $site->target($item->localized('target')) }}">{{ $item->localized('label') }}</a>@endforeach</div>
    <div><h3>{{ $navigation->get('header', collect())->firstWhere('key', 'header:contact')?->localized('label') }}</h3><p>{{ $site->setting('address') }}</p>
        @if($site->settings?->callUrl())<a class="footer-phone" href="{{ $site->settings->callUrl() }}" dir="ltr">{{ $site->settings->call_phone_number }}</a>@endif
        <p>{{ $site->setting('consultation_hours') }}<br>{{ $site->setting('friday_note') }}</p><a href="{{ $site->external($site->setting('facebook_url')) }}" target="_blank" rel="noopener">{{ $site->text('footer.social_label') }}</a></div>
</div><div class="footer-bottom">{{ $site->text('footer.copyright_format') }}</div></div></footer>
