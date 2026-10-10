<?php

// Private, code-owned interface labels. Null routes remain visible but non-interactive.
return [
    'Overview' => [['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'dashboard']],
    'Website' => [
        ['label' => 'Website Settings', 'route' => 'admin.settings.edit', 'icon' => 'settings'],
        ['label' => 'Page Content', 'route' => 'admin.pages.index', 'icon' => 'pages'],
        ['label' => 'Navigation', 'route' => 'admin.navigation.index', 'icon' => 'navigation'],
        ['label' => 'Home Care Cards', 'route' => 'admin.care-cards.index', 'icon' => 'cards'],
        ['label' => 'Services', 'route' => 'admin.services.index', 'icon' => 'services'],
        ['label' => 'Consultation Options', 'route' => 'admin.consultation-options.index', 'icon' => 'appointments'],
        ['label' => 'Categories', 'route' => 'admin.categories.index', 'icon' => 'categories'],
        ['label' => 'Articles', 'route' => 'admin.articles.index', 'icon' => 'articles'],
        ['label' => 'Gallery', 'route' => null, 'icon' => 'gallery'],
        ['label' => 'Media', 'route' => 'admin.media.index', 'icon' => 'media'],
    ],
    'Inbox' => [
        ['label' => 'Appointments', 'route' => null, 'icon' => 'appointments'],
        ['label' => 'Contact Messages', 'route' => null, 'icon' => 'messages'],
    ],
    'Account' => [['label' => 'Profile', 'route' => 'admin.profile.edit', 'icon' => 'profile']],
];
