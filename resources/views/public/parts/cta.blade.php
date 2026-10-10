@if ($visible('cta'))
<section><div class="wrap"><div class="cta"><div><span class="eyebrow" style="color:#cbb58d">{{ $site->setting('clinic_name') }}</span><h2>{{ $site->setting('cta_title') }}</h2><p>{{ $site->setting('cta_description') }}</p></div><a href="{{ $site->target($site->setting('cta_target'), ['kind' => 'appointment']) }}" class="btn">{{ $site->setting('cta_label') }} ↗</a></div></div></section>
@endif
