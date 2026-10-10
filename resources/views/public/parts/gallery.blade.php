<div class="gallery" id="galleryGrid">
@forelse ($images as $image)
    @include('public.parts.gallery-item')
@empty
    <p class="small-note">{{ $site->text('ui.empty_filter_results') }}</p>
@endforelse
</div>
