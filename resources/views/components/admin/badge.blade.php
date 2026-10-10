@props(['tone' => 'neutral'])
@php($style = ['success' => 'bg-jade-50 text-jade-800 ring-jade-200', 'warning' => 'bg-amber-50 text-amber-800 ring-amber-200', 'danger' => 'bg-rose-50 text-rose-800 ring-rose-200', 'neutral' => 'bg-slate-100 text-slate-600 ring-slate-200'][$tone] ?? 'bg-slate-100 text-slate-600 ring-slate-200')
<span {{ $attributes->class(['inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium ring-1 ring-inset', $style]) }}>{{ $slot }}</span>
