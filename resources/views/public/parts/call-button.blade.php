@php
    $callUrl = $site->settings?->callUrl() ?? '';
    $callLabel = $site->setting('call_button_label');
@endphp
@if(in_array($page, ['home', 'contact'], true) && $callUrl !== '' && $callLabel !== '')
    <a class="call-button" href="{{ $callUrl }}" aria-label="{{ $callLabel }}" title="{{ $callLabel }}">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 2 .7 2.9a2 2 0 0 1-.5 2.1L8 10a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.5c.9.3 1.9.6 2.9.7a2 2 0 0 1 1.7 2Z"/></svg>
        <span class="call-button-label">{{ $callLabel }}</span>
    </a>
@endif
