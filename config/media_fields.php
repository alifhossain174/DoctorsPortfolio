<?php

// Upload identity/bytes/paths are code-owned; only these localized descriptions are editable.
$fields = [];
foreach (['title' => ['text', true], 'alt_text' => ['text', false], 'caption' => ['textarea', false], 'provenance_note' => ['textarea', false], 'stock_note' => ['textarea', false]] as $name => [$type, $required]) {
    $fields[$name] = ['type' => $type, 'localized' => true, 'required' => $required, 'owner' => 'MediaAsset', 'screen_group' => 'Media', 'control' => 'English / বাংলা', 'editable' => true];
}
foreach ([
    'key' => ['key', true, ['string', 'max:100', 'regex:/^[a-z0-9][a-z0-9:._-]*$/']],
    'source_url' => ['url', false, null],
    'disk' => ['enum', false, ['string', 'in:public-media']],
    'path' => ['path', false, ['string', 'max:500', 'regex:~^images/[0-9]{4}/(?:0[1-9]|1[0-2])/[a-f0-9]{32}\.(?:jpg|png|webp)$~D']],
    'mime_type' => ['string', false, ['string', 'in:image/jpeg,image/png,image/webp']],
    'size' => ['integer', false, ['integer', 'min:1', 'max:5242880']],
    'original_filename' => ['string', false, ['string', 'max:255']],
    'width' => ['integer', false, ['integer', 'min:1', 'max:6000']],
    'height' => ['integer', false, ['integer', 'min:1', 'max:6000']],
] as $name => [$type, $required, $validation]) {
    $fields[$name] = ['type' => $type, 'localized' => false, 'required' => $required, 'owner' => 'MediaAsset', 'screen_group' => 'Media', 'control' => 'readonly', 'editable' => false];
    if ($validation !== null) {
        $fields[$name]['validation'] = $validation;
    }
}

return $fields;
