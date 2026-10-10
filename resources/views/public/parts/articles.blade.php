<div class="grid3" id="articleGrid">
@foreach ($posts as $entry)
<article @class(['card article-card', 'hidden' => $filter !== 'all' && $filter !== $entry->category->slug]) data-category="{{ $entry->category->slug }}">
    @if ($entry->mediaAsset)<img src="{{ $entry->mediaAsset->url() }}" class="article-img" alt="{{ $entry->localized('image_alt') }}" loading="lazy">@endif
    <div class="card-body"><div class="article-meta">{{ $entry->localized('editorial_badge') }}</div><h3><a href="{{ route('article', $entry->slug) }}">{{ $entry->localized('title') }}</a></h3><p>{{ $entry->localized('excerpt') }}</p><div class="tags"><span class="tag">#{{ $entry->category->localized('label') }}</span></div><a class="mini" href="{{ route('article', $entry->slug) }}">{{ $site->text('ui.read') }} →</a></div>
</article>
@endforeach
</div>
