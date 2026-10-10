@props(['name' => 'pages'])
@php
    $paths = [
        'dashboard' => 'M3 3h7v7H3z M14 3h7v7h-7z M3 14h7v7H3z M14 14h7v7h-7z',
        'settings' => 'M4 7h16 M4 17h16 M8 4v6 M16 14v6',
        'pages' => 'M5 3h10l4 4v14H5z M14 3v5h5 M8 12h8 M8 16h6',
        'navigation' => 'M12 3l8 18-8-5-8 5z',
        'cards' => 'M3 5h18v14H3z M7 9h4 M7 13h8',
        'services' => 'M9 3h6v6h6v6h-6v6H9v-6H3V9h6z',
        'categories' => 'M3 3h8v8H3z M16 3l5 8H11z M3 17a4 4 0 108 0 4 4 0 10-8 0 M15 15h6v6h-6z',
        'articles' => 'M5 3h14v18H5z M8 7h8 M8 11h8 M8 15h5',
        'gallery' => 'M3 4h18v16H3z M3 16l6-6 5 5 3-3 4 4 M15 8h.01',
        'media' => 'M5 3h14v18H5z M8 16l3-4 5 4 M14 7h.01',
        'appointments' => 'M4 5h16v16H4z M8 3v4 M16 3v4 M4 10h16 M8 14h3',
        'messages' => 'M3 5h18v14H7l-4 3z M7 9h10 M7 13h6',
        'profile' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0 M4 21v-2a8 8 0 0116 0v2',
        'arrow' => 'M5 12h14 M13 6l6 6-6 6',
        'menu' => 'M4 6h16 M4 12h16 M4 18h16',
        'close' => 'M6 6l12 12 M18 6L6 18',
        'logout' => 'M9 3H4v18h5 M10 12h11 M16 7l5 5-5 5',
        'check' => 'M4 12l5 5L20 6',
        'lock' => 'M5 10h14v11H5z M8 10V7a4 4 0 018 0v3',
    ];
@endphp
<svg {{ $attributes->class(['h-5 w-5 shrink-0']) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="{{ $paths[$name] ?? $paths['pages'] }}"/></svg>
