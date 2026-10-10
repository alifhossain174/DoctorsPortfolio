@php($mediaUrl = $image->mediaAsset?->url() ?? '')
@php($imageAlt = $image->localized('alt_text') ?: ($image->mediaAsset?->localized('alt_text') ?: $image->localized('caption')))
<figure class="gallery-item" data-category="{{ $image->category?->slug }}" data-caption="{{ $image->localized('caption') }}" data-url="{{ $mediaUrl }}" @if ($mediaUrl) tabindex="0" role="button" aria-label="{{ $image->localized('caption') }}" @endif>@include('public.parts.image', ['url' => $mediaUrl, 'class' => '', 'alt' => $imageAlt])<figcaption>{{ $image->localized('caption') }}</figcaption></figure>
