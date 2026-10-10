<div class="filters" data-filter="{{ $page === 'gallery' ? 'gallery' : 'article' }}">
    <a @class(['filter', 'selected' => $filter === 'all']) data-cat="all" href="{{ request()->fullUrlWithQuery(['category' => 'all']) }}">{{ $site->text('ui.filter_all') }}</a>
    @foreach ($categories as $category)<a @class(['filter', 'selected' => $filter === $category->slug]) data-cat="{{ $category->slug }}" href="{{ request()->fullUrlWithQuery(['category' => $category->slug]) }}">{{ $category->localized('label') }}</a>@endforeach
</div>
