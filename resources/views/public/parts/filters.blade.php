<div class="filters" data-filter="{{ $page === 'gallery' ? 'gallery' : 'article' }}">
    <a @class(['filter', 'selected' => $filter === 'all']) data-cat="all" href="{{ $urls->route($page, ['category' => 'all']) }}" @if ($filter === 'all') aria-current="true" @endif>{{ $site->text('ui.filter_all') }}</a>
    @foreach ($categories as $category)<a @class(['filter', 'selected' => $filter === $category->slug]) data-cat="{{ $category->slug }}" href="{{ $urls->route($page, ['category' => $category->slug]) }}" @if ($filter === $category->slug) aria-current="true" @endif>{{ $category->localized('label') }}</a>@endforeach
</div>
