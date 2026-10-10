@extends('public.layouts.app')
@section('content')
@include('public.parts.hero', ['eyebrow' => '404', 'title' => $site->text('ui.not_found_title'), 'description' => $site->text('ui.not_found_description')])
<main><section><div class="wrap"><a class="btn light" href="{{ $urls->route('home') }}">← {{ $site->text('ui.not_found_link') }}</a></div></section></main>
@endsection
