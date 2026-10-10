<div class="grid3" id="articleGrid">
@forelse ($posts as $entry)
    @include('public.parts.article-card')
@empty
    <p class="small-note">{{ $site->text('ui.empty_filter_results') }}</p>
@endforelse
</div>
