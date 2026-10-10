@foreach($fields as $field)
    @php($definition=config('content_fields')[$field->key])
    <div data-cms-key="{{ $field->key }}"><x-admin.cms-field :name="'fields['.$field->id.']['.($definition['type']==='media' ? 'media_asset_id' : 'value').']'" :label="$editor->label($field->key)" :definition="$definition" :value="$field->value" :hint="$editor->help($field->key,$definition)" :media-choices="$mediaChoices" :selected="$field->mediaAsset" :routes="in_array($field->key,['home.primary_target','diagnostics.contact_target'],true) ? ['contact'] : null"/></div>
@endforeach
