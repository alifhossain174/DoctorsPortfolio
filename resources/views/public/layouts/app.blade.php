<!doctype html>
<html lang="{{ $locale }}">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#205d54">
    @include('public.parts.metadata')
    @php
        // A changed asset gets a new browser URL after an ordinary file upload.
        $publicAsset = function (string $filename): string {
            $path = public_path('assets/'.$filename);
            return asset('assets/'.$filename).'?v='.(is_file($path) ? filemtime($path) : '1');
        };
    @endphp
    <link rel="stylesheet" href="{{ $publicAsset('style.css') }}">
    <link rel="stylesheet" href="{{ $publicAsset('interactions.css') }}">
    <script src="{{ $publicAsset('public.js') }}" defer></script>
    <noscript><style>@media(max-width:850px){.links{display:flex}.menu{display:none}}</style></noscript>
</head>
<body data-page="{{ $page }}"><div id="root">
    @include('public.parts.topbar')
    @include('public.parts.navigation')
    @yield('content')
    @include('public.parts.footer')
    @include('public.parts.lightbox')
    @include('public.parts.call-button')
</div></body></html>
