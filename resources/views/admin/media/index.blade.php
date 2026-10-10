@extends('admin.layouts.app')
@section('title', 'Media library · DoctorPortfolio')
@section('heading', 'Media library')
@section('content')
<x-admin.breadcrumbs :items="[['label'=>'Dashboard','href'=>route('admin.dashboard')],['label'=>'Media']]"/>
<div class="mb-7 flex flex-wrap items-end justify-between gap-4"><div><p class="text-xs font-semibold uppercase tracking-widest text-jade-700">Website images</p><h1 class="mt-2 text-3xl font-semibold tracking-tight">Media library</h1><p class="mt-2 text-sm text-slate-500">{{ $media->total() }} images · Original external images remain until you choose replacements.</p></div><x-admin.button :href="route('admin.media.create')">Upload image</x-admin.button></div>
<x-admin.validation-summary/>
<form method="get" action="{{ route('admin.media.index') }}" class="admin-card mb-6 flex flex-wrap items-end gap-4">
    @if (($filter['picker'] ?? null) === '1')<input type="hidden" name="picker" value="1">@endif
    <div class="min-w-48 flex-1"><x-admin.input name="q" label="Search titles" :value="$filter['q'] ?? ''" maxlength="100"/></div>
    <div><label class="admin-label" for="kind">Source</label><select class="admin-field" name="kind" id="kind"><option value="">All images</option value="local" @selected(($filter['kind'] ?? '') === 'local')>Local upload</option><option value="external" @selected(($filter['kind'] ?? '') === 'external')>Legacy external URL</option></select></div>
    <x-admin.button variant="secondary">Filter</x-admin.button><a class="pb-2 text-sm text-slate-500 hover:text-jade-700" href="{{ route('admin.media.index', array_filter(['picker'=>$filter['picker'] ?? null])) }}">Clear</a>
</form>
@if (($filter['picker'] ?? null) === '1')<p class="mb-5 rounded-xl bg-jade-50 p-4 text-sm text-jade-900">Choose an image to send it to the open editor. Without JavaScript, use its ID in the editor's image selection.</p>@endif
@if ($media->isEmpty())
    <x-admin.empty-state title="No images found" description="Upload a JPEG, PNG or WebP, or clear the filters to see original external images." icon="media"/>
@else
<div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
@foreach ($media as $asset)
    <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xs">
        <a href="{{ route('admin.media.edit', $asset) }}" class="flex aspect-[4/3] items-center justify-center bg-slate-100"><img src="{{ $asset->url() }}" alt="{{ $asset->localized('alt_text','en') }}" class="h-full w-full object-contain" loading="lazy" referrerpolicy="no-referrer"></a>
        <div class="space-y-3 p-5"><div class="flex items-start justify-between gap-2"><h2 class="min-w-0 break-words text-sm font-semibold">{{ $asset->localized('title','en') }}</h2><x-admin.badge :tone="$asset->path ? 'success' : 'warning'">{{ $asset->path ? 'Local' : 'External' }}</x-admin.badge></div>
        <p class="text-xs text-slate-500">ID {{ $asset->id }} · {{ $asset->posts_count + $asset->gallery_images_count + $asset->content_fields_count + $asset->settings_count }} references @if ($asset->width) · {{ $asset->width }} × {{ $asset->height }} @endif</p>
        <div class="flex flex-wrap items-center gap-3"><x-admin.button variant="secondary" :href="route('admin.media.edit',$asset)">Details</x-admin.button>
        @if (($filter['picker'] ?? null) === '1')<button type="button" class="admin-button admin-button-primary" data-media-choose data-media-id="{{ $asset->id }}" data-media-url="{{ $asset->url() }}" data-media-title="{{ $asset->localized('title','en') }}" data-media-alt="{{ $asset->localized('alt_text','en') }}">Choose image</button>@endif</div>
        </div>
    </article>
@endforeach
</div>
<x-admin.pagination :paginator="$media"/>
@endif
@endsection
