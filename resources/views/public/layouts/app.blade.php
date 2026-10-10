<!doctype html>
<html lang="{{ $locale }}">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#205d54">
    @if($preview ?? false)<meta name="robots" content="noindex, nofollow, nosnippet">@endif
    <meta name="description" content="{{ $site->format($metaDescription, ['page_label' => $label]) }}">
    <title>{{ $site->format($metaTitle, ['page_label' => $label]) }}</title>
    <link rel="stylesheet" href="{{ asset('assets/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/interactions.css') }}">
    <script src="{{ asset('assets/public.js') }}" defer></script>
    <noscript><style>@media(max-width:850px){.links{display:flex}.menu{display:none}}</style></noscript>
</head>
<body data-page="{{ $page }}"><div id="root">
    @include('public.parts.topbar')
    @include('public.parts.navigation')
    @yield('content')
    @include('public.parts.footer')
    @include('public.parts.lightbox')
</div></body></html>
