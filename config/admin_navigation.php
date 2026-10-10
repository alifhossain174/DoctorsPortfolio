<?php

// Private, code-owned interface labels. Null routes remain visible but non-interactive.
return [
    'Overview' => [['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'dashboard']],
    'Website' => [
        ['label' => 'Website Settings', 'route' => null, 'icon' => 'settings'],
        ['label' => 'Page Content', 'route' => null, 'icon' => 'pages'],
        ['label' => 'Navigation', 'route' => null, 'icon' => 'navigation'],
        ['label' => 'Home Care Cards', 'route' => null, 'icon' => 'cards'],
        ['label' => 'Services', 'route' => null, 'icon' => 'services'],
        ['label' => 'Categories', 'route' => null, 'icon' => 'categories'],
        ['label' => 'Articles', 'route' => null, 'icon' => 'articles'],
        ['label' => 'Gallery', 'route' => null, 'icon' => 'gallery'],
        ['label' => 'Media', 'route' => 'admin.media.index', 'icon' => 'media'],
    ],
    'Inbox' => [
        ['label' => 'Appointments', 'route' => null, 'icon' => 'appointments'],
        ['label' => 'Contact Messages', 'route' => null, 'icon' => 'messages'],
    ],
    'Account' => [['label' => 'Profile', 'route' => 'admin.profile.edit', 'icon' => 'profile']],
];
