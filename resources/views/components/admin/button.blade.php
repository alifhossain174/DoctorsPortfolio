@props(['variant' => 'primary', 'type' => 'submit', 'href' => null])
@php($style = ['primary' => 'admin-button-primary', 'secondary' => 'admin-button-secondary', 'danger' => 'admin-button-danger', 'quiet' => 'admin-button-quiet'][$variant] ?? 'admin-button-primary')
@if ($href)
<a href="{{ $href }}" {{ $attributes->class(['admin-button', $style]) }}>{{ $slot }}</a>
@else
<button type="{{ $type }}" {{ $attributes->class(['admin-button', $style]) }}>{{ $slot }}</button>
@endif
