@extends('admin.layouts.app')
@section('title', 'Image details · DoctorPortfolio')
@section('heading', 'Image details')
@section('content')
<x-admin.breadcrumbs :items="[['label'=>'Dashboard','href'=>route('admin.dashboard')],['label'=>'Media','href'=>route('admin.media.index')],['label'=>'Image '.$media->id]]"/>
<div class="mb-7"><h1 class="text-3xl font-semibold tracking-tight">Image details</h1><p class="mt-2 text-sm text-slate-500">You can upload images directly in Page Content, Website Settings, Articles or Gallery. Choose a file there, then save that content.</p></div>
<x-admin.validation-summary/>
@if($picker)<section class="admin-card mb-6"><button type="button" class="admin-button admin-button-primary" data-media-choose data-media-id="{{ $media->id }}" data-media-url="{{ $media->url() }}" data-media-title="{{ $media->localized('title','en') }}" data-media-alt="{{ $media->localized('alt_text','en') }}">Choose image</button><a class="ml-4 text-sm font-semibold text-jade-700" href="{{ route('admin.media.index',['picker'=>1]) }}">Back to image picker</a><p class="mt-3 text-xs text-slate-500">Returns this image to the open editor without saving or discarding its text. Without JavaScript, upload first and select it in a freshly opened editor.</p></section>@endif
<div class="grid items-start gap-6 xl:grid-cols-3">
    <div class="space-y-6 xl:col-span-2">
        <section class="admin-card"><h2 class="text-lg font-semibold">Usage</h2><p class="mt-1 text-sm text-slate-500">All references count, including draft articles and hidden gallery items.</p>
        @if (count($usages))<div class="mt-5"><x-admin.table :headers="['Location','Record']" caption="Current image references">@foreach ($usages as $usage)<tr><td class="px-4 py-3 text-sm">{{ $usage['type'] }}</td><td class="px-4 py-3 text-sm"><span class="font-medium">{{ $usage['label'] }}</span><span class="mt-1 block text-xs text-slate-500">{{ $usage['identity'] }}</span></td></tr>@endforeach</x-admin.table></div>
        @else<p class="mt-4 rounded-xl bg-slate-50 p-4 text-sm text-slate-500">This image is not used by website content.</p>@endif</section>
        @if (count($usages))<form method="post" action="{{ route('admin.media.replace',$media) }}" class="admin-card space-y-5" data-prevent-double-submit>@csrf @method('PUT')
            <div><h2 class="text-lg font-semibold">Change image everywhere it is used</h2><p class="mt-1 text-sm leading-6 text-slate-500">Upload a new image below or choose one from the library. Confirm to change all {{ count($usages) }} locations listed above together. To change only one location, use that content's editor instead.</p></div>
            <x-admin.media-picker name="replacement_id" label="Replacement image" :assets="$mediaChoices" required/>
            <label class="flex items-center gap-2 text-sm text-slate-600"><input type="checkbox" name="confirm_replace" value="1" required class="h-4 w-4 accent-jade-700">I confirm all listed references should use the selected image.</label>
            <x-admin.button>Replace {{ count($usages) }} references</x-admin.button>
        </form>@endif
        <form method="post" action="{{ route('admin.media.update',$media) }}" class="admin-card space-y-7" data-prevent-double-submit>@csrf @method('PUT')
            @if($picker)<input type="hidden" name="picker" value="1">@endif
            <h2 class="text-lg font-semibold">Image name &amp; descriptions</h2>
            @include('admin.media.fields')<x-admin.button>Save image details</x-admin.button>
        </form>
    </div>
    <div class="space-y-6">
        <section class="admin-card space-y-4"><img src="{{ $media->url() }}" alt="{{ $media->localized('alt_text','en') }}" class="max-h-96 w-full rounded-xl bg-slate-100 object-contain" referrerpolicy="no-referrer"><x-admin.badge :tone="$media->path ? 'success' : 'warning'">{{ $media->path ? 'Local upload' : 'Legacy external URL' }}</x-admin.badge>
            @if (!$media->path)
                <p class="text-sm leading-6 text-slate-600">@if(str_starts_with($media->key, 'demo-photo-'))This image address was imported from the original demo website and saved in the media library.@else This image is loaded from an external website using the address saved in this media record.@endif It is not an uploaded file on this website.</p>
                <p class="text-xs leading-5 text-slate-500">Image host: <span class="break-all font-semibold">{{ parse_url($media->source_url ?? '', PHP_URL_HOST) }}</span>. External links can expire or block previews. Upload your own image in the relevant content editor to replace it.</p>
            @else<p class="text-sm leading-6 text-slate-600">Uploaded to this website. The image address is generated automatically; you do not need to copy or enter it in content.</p>@endif
            <dl class="space-y-2 text-xs text-slate-500"><div><dt class="inline font-semibold">ID:</dt> <dd class="inline">{{ $media->id }}</dd></div>@if($media->width)<div><dt class="inline font-semibold">Dimensions:</dt> <dd class="inline">{{ $media->width }} × {{ $media->height }}</dd></div><div><dt class="inline font-semibold">Size / MIME:</dt> <dd class="inline">{{ number_format($media->size/1024,1) }} KiB · {{ $media->mime_type }}</dd></div>@endif</dl>
            <details class="space-y-3"><summary class="text-sm font-semibold text-jade-700">Image address (advanced)</summary><p class="mt-3 text-xs leading-5 text-slate-500">For reference only. Content uses your selected image automatically.</p><label class="admin-label" for="media-public-url">Public image URL</label><input readonly class="admin-field text-xs" id="media-public-url" value="{{ $media->url() }}"><button type="button" class="admin-button admin-button-secondary" data-copy-url="media-public-url">Copy URL</button><p class="text-xs text-slate-500" role="status" data-copy-status></p></details>
        </section>
        <section class="admin-card"><h2 class="text-base font-semibold">Delete image</h2>@if(count($usages))<p class="mt-3 text-sm leading-6 text-slate-500">Deletion is blocked while content uses this image. Replace the references first.</p>@else<p class="my-3 text-sm leading-6 text-slate-500">Remove this unused record and its local file, if present. External source files are never deleted.</p><x-admin.delete-confirmation :action="route('admin.media.destroy',$media)" label="image" :id="'delete-media-'.$media->id"/>@endif</section>
    </div>
</div>
@endsection
