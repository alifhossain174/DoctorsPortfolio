<div class="gallery" id="galleryGrid">
@foreach ($images as $image)
<figure @class(['gallery-item', 'hidden' => $filter !== 'all' && $filter !== $image->category->slug]) data-category="{{ $image->category->slug }}" data-caption="{{ $image->localized('caption') }}" data-url="{{ $image->mediaAsset->url() }}" tabindex="0" role="button" aria-label="{{ $image->localized('caption') }}"><img src="{{ $image->mediaAsset->url() }}" alt="{{ $image->localized('alt_text') }}" loading="lazy"><figcaption>{{ $image->localized('caption') }}</figcaption></figure>
@endforeach
</div>
