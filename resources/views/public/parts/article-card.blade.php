<article class="card article-card" data-category="{{ $entry->category?->slug }}">
    @include('public.parts.image', ['url' => $entry->mediaAsset?->url() ?? '', 'class' => 'article-img', 'alt' => $entry->localized('image_alt')])
    <div class="card-body"><div class="article-meta">{{ $entry->localized('editorial_badge') }}</div><h3><a href="{{ $urls->route('article', ['post' => $entry->slug]) }}">{{ $entry->localized('title') }}</a></h3><p>{{ $entry->localized('excerpt') }}</p><div class="tags"><span class="tag">#{{ $entry->category?->localized('label') }}</span></div><a class="mini" href="{{ $urls->route('article', ['post' => $entry->slug]) }}">{{ $site->text('ui.read') }} →</a></div>
</article>
