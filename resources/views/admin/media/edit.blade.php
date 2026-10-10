@extends('admin.layouts.app')
@section('title', 'Image details · DoctorPortfolio')
@section('heading', 'Image details')
@section('content')
<x-admin.breadcrumbs :items="[['label'=>'Dashboard','href'=>route('admin.dashboard')],['label'=>'Media','href'=>route('admin.media.index')],['label'=>'Image '.$media->id]]"/>
<div class="mb-7"><h1 class="text-3xl font-semibold tracking-tight">Image details</h1><p class="mt-2 text-sm text-slate-500">Edit descriptions or replace content references. Image files are never overwritten in place.</p></div>
<x-admin.validation-summary/>
<div class="grid items-start gap-6 xl:grid-cols-3">
    <div class="space-y-6 xl:col-span-2">
        <form method="post" action="{{ route('admin.media.update',$media) }}" class="admin-card space-y-7" data-prevent-double-submit>@csrf @method('PUT')
            @include('admin.media.fields')<x-admin.button>Save image details</x-admin.button>
        </form>
        <section class="admin-card"><h2 class="text-lg font-semibold">Usage</h2><p class="mt-1 text-sm text-slate-500">All references count, including draft articles and hidden gallery items.</p>
        @if (count($usages))<div class="mt-5"><x-admin.table :headers="['Location','Record']" caption="Current image references">@foreach ($usages as $usage)<tr><td class="px-4 py-3 text-sm">{{ $usage['type'] }}</td><td class="px-4 py-3 text-sm"><span class="font-medium">{{ $usage['label'] }}</span><span class="mt-1 block text-xs text-slate-500">{{ $usage['identity'] }}</span></td></tr>@endforeach</x-admin.table></div>
        @else<p class="mt-4 rounded-xl bg-slate-50 p-4 text-sm text-slate-500">This image is not used by website content.</p>@endif</section>
        @if (count($usages))<form method="post" action="{{ route('admin.media.replace',$media) }}" class="admin-card space-y-5" data-prevent-double-submit>@csrf @method('PUT')
            <div><h2 class="text-lg font-semibold">Replace all references</h2><p class="mt-1 text-sm leading-6 text-slate-500">Choose an existing asset to replace these {{ count($usages) }} references together. The original image remains in this library. Check every affected slot afterwards.</p></div>
            <x-admin.media-picker name="replacement_id" label="Replacement image" :assets="$mediaChoices" required/>
            <label class="flex items-center gap-2 text-sm text-slate-600"><input type="checkbox" name="confirm_replace" value="1" required class="h-4 w-4 accent-jade-700">I confirm all listed references should use the selected image.</label>
            <x-admin.button>Replace {{ count($usages) }} references</x-admin.button>
        </form>@endif
    </div>
    <div class="space-y-6">
        <section class="admin-card space-y-4"><img src="{{ $media->url() }}" alt="{{ $media->localized('alt_text','en') }}" class="max-h-96 w-full rounded-xl bg-slate-100 object-contain" referrerpolicy="no-referrer"><x-admin.badge :tone="$media->path ? 'success' : 'warning'">{{ $media->path ? 'Local upload' : 'Legacy external URL' }}</x-admin.badge>
            @if (!$media->path)<p class="text-xs leading-5 text-slate-500">This record contains an original URL, not a downloaded local file. The source host can expire or block it. No unrelated replacement has been substituted.</p>@endif
            <dl class="space-y-2 text-xs text-slate-500"><div><dt class="inline font-semibold">ID:</dt> <dd class="inline">{{ $media->id }}</dd></div>@if($media->width)<div><dt class="inline font-semibold">Dimensions:</dt> <dd class="inline">{{ $media->width }} × {{ $media->height }}</dd></div><div><dt class="inline font-semibold">Size / MIME:</dt> <dd class="inline">{{ number_format($media->size/1024,1) }} KiB · {{ $media->mime_type }}</dd></div>@endif</dl>
            <label class="admin-label" for="media-public-url">Public image URL</label><input readonly class="admin-field text-xs" id="media-public-url" value="{{ $media->url() }}"><button type="button" class="admin-button admin-button-secondary" data-copy-url="media-public-url">Copy URL</button><p class="text-xs text-slate-500" role="status" data-copy-status></p>
        </section>
        <section class="admin-card"><h2 class="text-base font-semibold">Delete image</h2>@if(count($usages))<p class="mt-3 text-sm leading-6 text-slate-500">Deletion is blocked while content uses this image. Replace the references first.</p>@else<p class="my-3 text-sm leading-6 text-slate-500">Remove this unused record and its local file, if present. External source files are never deleted.</p><x-admin.delete-confirmation :action="route('admin.media.destroy',$media)" label="image" :id="'delete-media-'.$media->id"/>@endif</section>
    </div>
</div>
@endsection
