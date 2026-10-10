<?php

$fields = [
    'key' => ['type' => 'key', 'localized' => false, 'required' => true, 'validation' => ['string', 'in:home,about,services,diagnostics,blog,gallery,contact,article'], 'editable' => false],
    'label' => ['type' => 'text', 'localized' => true, 'required' => true, 'editable' => true],
    'meta_title' => ['type' => 'format', 'localized' => true, 'required' => false, 'max_length' => 200, 'editable' => true],
    'meta_description' => ['type' => 'format', 'localized' => true, 'required' => false, 'max_length' => 500, 'editable' => true],
    'og_title' => ['type' => 'format', 'localized' => true, 'required' => false, 'max_length' => 200, 'editable' => true],
    'og_description' => ['type' => 'format', 'localized' => true, 'required' => false, 'max_length' => 500, 'editable' => true],
    'og_media_asset_id' => ['type' => 'relation', 'localized' => false, 'required' => false, 'validation' => ['integer', 'exists:media_assets,id'], 'editable' => true],
];
foreach ($fields as &$field) {
    $field += ['owner' => 'Page', 'screen_group' => 'Pages', 'control' => $field['localized'] ? 'English / বাংলা' : $field['type']];
}
unset($field);

return $fields;
