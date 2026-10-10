@if($paginator->hasPages())
<div class="spacing"></div><nav class="filters" aria-label="{{ $site->text('blog.blogheading') }}">
    @foreach($elements as $element)
        @if(is_string($element))<span class="filter" aria-hidden="true">{{ $element }}</span>@endif
        @if(is_array($element))@foreach($element as $number=>$url)
            @if($number===$paginator->currentPage())<span class="filter selected" aria-current="page">{{ $number }}</span>@else<a class="filter" href="{{ $url }}">{{ $number }}</a>@endif
        @endforeach @endif
    @endforeach
</nav>
@endif
