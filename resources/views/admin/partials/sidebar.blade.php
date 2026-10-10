<div class="flex h-20 shrink-0 items-center justify-between border-b border-slate-800 px-6"><a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3"><span class="flex h-10 w-10 items-center justify-center rounded-xl bg-jade-600 text-sm font-bold tracking-wide text-white">DP</span><span class="text-sm font-semibold text-white">DoctorPortfolio<span class="mt-0.5 block text-xs font-normal text-slate-400">Website workspace</span></span></a><button type="button" class="rounded-lg p-2 text-slate-400 hover:text-white lg:hidden" data-sidebar-close aria-label="Close navigation"><x-admin.icon name="close"/></button></div>
<nav aria-label="Admin navigation" class="flex-1 space-y-5 overflow-y-auto px-4 py-6">
    @foreach (config('admin_navigation') as $group => $items)
        <div><h2 class="mb-2 px-3 text-[10px] font-semibold uppercase tracking-[.16em] text-slate-500">{{ $group }}</h2><ul class="space-y-1">
        @foreach ($items as $item)
            <li>@if ($item['route'] && \Illuminate\Support\Facades\Route::has($item['route']))
                @php($active=request()->routeIs($item['route']) || (str_contains($item['route'],'.') && $item['route']!=='admin.dashboard' && request()->routeIs(substr($item['route'],0,strrpos($item['route'],'.')).'.*')))
                <a href="{{ route($item['route']) }}" @class(['flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-colors', 'bg-jade-700 text-white' => $active, 'text-slate-300 hover:bg-slate-800 hover:text-white' => ! $active]) @if ($active) aria-current="page" @endif><x-admin.icon :name="$item['icon']"/>{{ $item['label'] }}</a>
            @else
                <span class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm text-slate-500" aria-disabled="true"><x-admin.icon :name="$item['icon']"/><span class="flex-1">{{ $item['label'] }}</span><span class="rounded bg-slate-800 px-1.5 py-0.5 text-[9px] font-medium text-slate-400">Soon</span></span>
            @endif</li>
        @endforeach
        </ul></div>
    @endforeach
</nav>
<div class="shrink-0 border-t border-slate-800 p-4"><form method="post" action="{{ route('admin.logout') }}">@csrf<button class="flex w-full items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white" type="submit"><x-admin.icon name="logout"/>Sign out</button></form></div>
