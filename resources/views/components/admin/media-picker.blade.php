@props(['name' => 'media_asset_id', 'label' => 'Image', 'selected' => null, 'assets' => [], 'required' => false])
@php
    $pickerId = 'media-picker-'.preg_replace('/[^a-zA-Z0-9_-]/','-',$name);
    $errorKey=trim(str_replace(['[',']'],['.',''],$name),'.');
    $selectedId = old($errorKey, $selected?->id);
    $selectedId = is_scalar($selectedId) ? (string)$selectedId : '';
    $hasCurrent = collect($assets)->contains(fn($asset)=> (string)$asset->id === (string)$selected?->id);
@endphp
<fieldset class="rounded-xl border border-slate-200 bg-slate-50 p-5" data-media-picker>
    <legend class="px-1 text-sm font-medium text-slate-700">{{ $label }}</legend>
    <img data-media-preview class="mb-4 max-h-48 max-w-full rounded-lg object-contain" alt="{{ $selected?->localized('alt_text','en') ?? '' }}" @if($selected) src="{{ $selected->url() }}" @else hidden @endif referrerpolicy="no-referrer">
    <p data-media-preview-error class="mb-4 text-sm text-amber-800" hidden>Image preview unavailable. You can upload a replacement below.</p>
    <div hidden data-media-upload-controls class="mb-5 space-y-3">
        <label class="admin-label" for="{{ $pickerId }}-upload">Upload from device</label>
        <input type="file" id="{{ $pickerId }}-upload" data-media-upload data-upload-url="{{ route('admin.media.store') }}" accept="image/jpeg,image/png,image/webp,.jpg,.jpeg,.png,.webp" class="admin-field" aria-describedby="{{ $pickerId }}-upload-help {{ $pickerId }}-upload-status">
        <p id="{{ $pickerId }}-upload-help" class="text-xs leading-5 text-slate-500">Choose a JPEG, PNG or WebP (up to {{ config('media.max_kilobytes') / 1024 }} MiB). It uploads and selects here automatically. Save this form to apply the image.</p>
        <p id="{{ $pickerId }}-upload-status" data-media-upload-status class="text-sm text-slate-700" role="status" aria-live="polite" tabindex="-1"></p>
    </div>
    <label class="admin-label" for="{{ $pickerId }}">Choose an existing image</label>
    <select name="{{ $name }}" id="{{ $pickerId }}" class="admin-field" data-media-select @required($required)>
        <option value="">{{ $required ? 'Select an image' : 'No image' }}</option>
        @if($selected && !$hasCurrent)<option value="{{ $selected->id }}" data-url="{{ $selected->url() }}" data-alt="{{ $selected->localized('alt_text','en') }}" @selected($selectedId===(string)$selected->id)>#{{ $selected->id }} · {{ $selected->localized('title','en') }}</option>@endif
        @foreach ($assets as $asset)<option value="{{ $asset->id }}" data-url="{{ $asset->url() }}" data-alt="{{ $asset->localized('alt_text','en') }}" @selected($selectedId===(string)$asset->id)>#{{ $asset->id }} · {{ $asset->localized('title','en') }}</option>@endforeach
    </select>
    @error($errorKey)<p class="mt-2 text-xs text-rose-700">{{ $message }}</p>@enderror
    <a href="{{ route('admin.media.index',['picker'=>1]) }}" target="_blank" class="admin-button admin-button-secondary mt-4" data-media-browse>Browse library</a>
    <noscript><p class="mt-3 text-xs text-slate-500">To add a new image, upload it in the Media library first, then reopen this editor and choose it from the list.</p></noscript>
    <p class="mt-3 text-xs leading-5 text-slate-500">Your selection applies when you save this form. Uploading alone does not change existing content. Uploaded images stay in the library, even if you leave without saving.</p>
</fieldset>
