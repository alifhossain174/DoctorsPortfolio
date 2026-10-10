<title>{{ $seo['title'] }}</title>
<meta name="description" content="{{ $seo['description'] }}">
@if($seo['robots'])<meta name="robots" content="{{ $seo['robots'] }}">@endif
<meta property="og:title" content="{{ $seo['og_title'] }}">
<meta property="og:description" content="{{ $seo['og_description'] }}">
<meta property="og:type" content="{{ $seo['type'] }}">
<meta property="og:site_name" content="{{ $seo['site_name'] }}">
<meta property="og:locale" content="{{ $seo['locale'] }}">
@if($seo['image'])<meta property="og:image" content="{{ $seo['image'] }}"><meta property="og:image:alt" content="{{ $seo['image_alt'] }}">@endif
@if($seo['canonical'])<link rel="canonical" href="{{ $seo['canonical'] }}"><meta property="og:url" content="{{ $seo['canonical'] }}">@endif
@foreach($seo['alternates'] as $language => $href)<link rel="alternate" hreflang="{{ $language }}" href="{{ $href }}">@endforeach
