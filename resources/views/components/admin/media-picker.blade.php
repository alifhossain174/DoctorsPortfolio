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
    <label class="admin-label" for="{{ $pickerId }}">Choose an existing image</label>
    <select name="{{ $name }}" id="{{ $pickerId }}" class="admin-field" data-media-select @required($required)>
        <option value="">{{ $required ? 'Select an image' : 'No image' }}</option>
        @if($selected && !$hasCurrent)<option value="{{ $selected->id }}" data-url="{{ $selected->url() }}" data-alt="{{ $selected->localized('alt_text','en') }}" @selected($selectedId===(string)$selected->id)>#{{ $selected->id }} · {{ $selected->localized('title','en') }}</option>@endif
        @foreach ($assets as $asset)<option value="{{ $asset->id }}" data-url="{{ $asset->url() }}" data-alt="{{ $asset->localized('alt_text','en') }}" @selected($selectedId===(string)$asset->id)>#{{ $asset->id }} · {{ $asset->localized('title','en') }}</option>@endforeach
    </select>
    @error($errorKey)<p class="mt-2 text-xs text-rose-700">{{ $message }}</p>@enderror
    <img data-media-preview class="mt-4 max-h-48 max-w-full rounded-lg object-contain" alt="{{ $selected?->localized('alt_text','en') ?? '' }}" @if($selected) src="{{ $selected->url() }}" @else hidden @endif>
    <a href="{{ route('admin.media.index',['picker'=>1]) }}" target="_blank" class="admin-button admin-button-secondary mt-4" data-media-browse>Browse library</a>
    <p class="mt-3 text-xs leading-5 text-slate-500">Choosing an image changes this form's reference when you save. It never deletes a shared file. The list works without JavaScript.</p>
</fieldset>
