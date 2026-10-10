<?php

use App\Models\CareCard;
use App\Models\ConsultationOption;
use App\Models\Service;

// Three purpose-built editors, not an arbitrary model/JSON editor. Public values stay in MySQL.
return [
    'care-cards' => [
        'model' => CareCard::class, 'owner' => 'CareCard',
        'title' => 'Homepage care cards', 'singular' => 'care card', 'label_field' => 'title',
        'state' => 'is_enabled', 'state_label' => 'Visible on homepage', 'preview' => 'home',
        'help' => 'Short icon cards in the homepage care grid. The original four cards are independent of the five service-detail blocks and the appointment form choices.',
        'fields' => ['title', 'description', 'icon', 'link_label', 'target'],
        'labels' => ['title' => 'Card title', 'description' => 'Short description', 'icon' => 'Safe icon', 'link_label' => 'Learn-more link label', 'target' => 'Internal destination'],
    ],
    'services' => [
        'model' => Service::class, 'owner' => 'Service',
        'title' => 'Service-detail blocks', 'singular' => 'service block', 'label_field' => 'title',
        'state' => 'is_enabled', 'state_label' => 'Enabled on Services page', 'preview' => 'services',
        'help' => 'Longer numbered blocks on the Services page. The original five blocks do not control homepage cards or consultation choices. Public numbers follow enabled display order.',
        'fields' => ['title', 'body'], 'labels' => ['title' => 'Service title', 'body' => 'Plain-text body'],
    ],
    'consultation-options' => [
        'model' => ConsultationOption::class, 'owner' => 'ConsultationOption',
        'title' => 'Appointment consultation options', 'singular' => 'consultation option', 'label_field' => 'label',
        'state' => 'is_active', 'state_label' => 'Active in appointment requests', 'preview' => 'contact',
        'help' => 'The appointment form uses these independent choices: obstetrics/pregnancy, gynecology, infertility/IUI and diagnostics. They are request topics, not bookings or a diagnostic catalog. General contact has no consultation choice.',
        'fields' => ['label'], 'labels' => ['label' => 'Consultation label'],
    ],
];
