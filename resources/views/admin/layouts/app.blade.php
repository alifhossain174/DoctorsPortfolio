<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name').' Admin')</title>
    @vite(['resources/css/admin.css', 'resources/js/admin.js'])
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-900 antialiased">
    <a href="#main-content" class="sr-only z-50 rounded-lg bg-jade-700 p-3 text-white focus:not-sr-only focus:fixed focus:left-4 focus:top-4">Skip to content</a>
    <div class="fixed inset-0 z-30 hidden bg-slate-950/50 lg:hidden" id="sidebarBackdrop" data-sidebar-close></div>
    <aside id="adminSidebar" class="fixed inset-y-0 left-0 z-40 hidden w-72 flex-col bg-slate-900 lg:flex" aria-label="Workspace sidebar">@include('admin.partials.sidebar')</aside>
    <div class="lg:pl-72">
        <header class="sticky top-0 z-20 flex min-h-20 items-center justify-between gap-4 border-b border-slate-200 bg-white/95 px-5 backdrop-blur-sm sm:px-8">
            <div class="flex items-center gap-3"><button type="button" id="sidebarToggle" class="rounded-xl border border-slate-200 p-2 text-slate-600 lg:hidden" aria-expanded="false" aria-controls="adminSidebar" aria-label="Open navigation"><x-admin.icon name="menu"/></button><div><p class="text-sm font-semibold">@yield('heading', 'Workspace')</p><p class="mt-0.5 hidden text-xs text-slate-500 sm:block">DoctorPortfolio administration</p></div></div>
            <div class="flex items-center gap-4"><a href="{{ route('home') }}" class="hidden items-center gap-2 text-xs font-medium text-slate-500 hover:text-jade-700 sm:flex" target="_blank" rel="noopener">View website ↗</a><span class="hidden h-6 w-px bg-slate-200 sm:block"></span><a href="{{ route('admin.profile.edit') }}" class="flex items-center gap-2.5 rounded-xl px-1 py-1"><span class="flex h-9 w-9 items-center justify-center rounded-full bg-jade-50 text-xs font-bold text-jade-800">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span><span class="hidden max-w-40 truncate text-xs font-semibold sm:block">{{ auth()->user()->name }}</span></a></div>
        </header>
        <main id="main-content" class="mx-auto max-w-7xl px-5 py-8 sm:px-8 lg:py-10" tabindex="-1">
            @include('admin.partials.flash')
            @yield('content')
        </main>
        <footer class="mx-auto flex max-w-7xl flex-wrap justify-between gap-2 px-5 pb-6 text-[11px] text-slate-400 sm:px-8"><span>DoctorPortfolio · Private workspace</span><span>English / বাংলা website content</span></footer>
    </div>
    <noscript><style>@media(max-width:1023px){#adminSidebar{display:flex;position:static;width:auto}#sidebarToggle,[data-sidebar-close]{display:none}}</style></noscript>
</body>
</html>
