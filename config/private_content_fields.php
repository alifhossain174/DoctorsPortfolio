<?php

// Private controls never become public content. Snapshot/reference/attribution are read-only.
$field = static fn (string $owner, string $type, bool $required, array $rules, bool $editable = true): array => [
    'owner' => $owner, 'screen_group' => $owner, 'type' => $type,
    'control' => $type, 'localized' => false, 'required' => $required,
    'validation' => $rules, 'editable' => $editable,
];

return [
    'Inquiry' => [
        'public_reference' => $field('Inquiry', 'reference', true, ['string', 'size:26'], false),
        'kind' => $field('Inquiry', 'select', true, ['in:appointment,contact'], false),
        'name' => $field('Inquiry', 'text', true, ['string', 'max:80']),
        'phone' => $field('Inquiry', 'text', true, ['string', 'max:40']),
        'email' => $field('Inquiry', 'email', false, ['email', 'max:255']),
        'message' => $field('Inquiry', 'textarea', false, ['string', 'max:500']),
        'locale' => $field('Inquiry', 'locale', true, ['in:en,bn'], false),
        'consultation_option_id' => $field('Inquiry', 'relation', false, ['integer', 'exists:consultation_options,id'], false),
        'consultation_key' => $field('Inquiry', 'key', false, ['string', 'max:100'], false),
        'consultation_label' => [...$field('Inquiry', 'text', false, [], false), 'localized' => true],
        'status' => $field('Inquiry', 'select', true, ['in:new,read,confirmed,completed,cancelled,spam']),
        'internal_notes' => $field('Inquiry', 'textarea', false, ['string', 'max:15000']),
        'confirmed_visit_at' => $field('Inquiry', 'datetime', false, ['date']),
        'handled_by' => $field('Inquiry', 'relation', false, ['integer', 'exists:users,id'], false),
        'handled_at' => $field('Inquiry', 'datetime', false, ['date'], false),
    ],
    'User' => [
        'name' => $field('User', 'text', true, ['string', 'max:255']),
        'email' => $field('User', 'email', true, ['email', 'max:255']),
        'password' => $field('User', 'password', false, ['string', 'min:12', 'max:255']),
        'is_admin' => $field('User', 'authorization', true, ['boolean'], false),
    ],
];
