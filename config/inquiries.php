<?php

// Private workflow rules; never public content or a booking engine.
return [
    'per_page' => 20,
    'recent_count' => 6,
    'inboxes' => [
        'appointments' => ['kind' => 'appointment', 'label' => 'Appointments', 'singular' => 'appointment request', 'statuses' => ['new', 'read', 'confirmed', 'completed', 'cancelled', 'spam']],
        'contact-messages' => ['kind' => 'contact', 'label' => 'Contact Messages', 'singular' => 'contact message', 'statuses' => ['new', 'read', 'completed', 'spam']],
    ],
    'transitions' => [
        'appointment' => ['new' => ['read', 'confirmed', 'cancelled', 'spam'], 'read' => ['new', 'confirmed', 'completed', 'cancelled', 'spam'], 'confirmed' => ['read', 'completed', 'cancelled', 'spam'], 'completed' => ['read', 'spam'], 'cancelled' => ['read', 'spam'], 'spam' => ['read']],
        'contact' => ['new' => ['read', 'completed', 'spam'], 'read' => ['new', 'completed', 'spam'], 'completed' => ['read', 'spam'], 'spam' => ['read']],
    ],
];
