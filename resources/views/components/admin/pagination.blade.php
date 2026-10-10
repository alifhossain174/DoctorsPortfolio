@props(['paginator'])
@if ($paginator->hasPages())<div class="border-t border-slate-100 px-6 py-4">{{ $paginator->links('pagination::tailwind') }}</div>@endif
