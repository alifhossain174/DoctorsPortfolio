@props(['name','label','definition','value'=>null,'hint'=>null,'mediaChoices'=>[],'selected'=>null,'routes'=>null,'allowExternal'=>true])
@php
    $type=$definition['type'];
    $editorType=($definition['multiline'] ?? false) ? 'textarea' : $type;
    $required=$definition['required'] ?? false;
    $max=$definition['max_length'] ?? (in_array($type,['textarea','format'],true) ? 15000 : ($type==='url' ? 4096 : 500));
@endphp
@if($definition['localized'] ?? false)
    <x-admin.localized-tabs :name="$name" :label="$label" :value="is_array($value) ? $value : []" :type="in_array($editorType,['textarea','format'],true) ? 'textarea' : $editorType" :required="$required" :hint="$hint" :maxlength="$max" :routes="$routes" :allow-external="$allowExternal"/>
@elseif($type==='relation' || $type==='media')
    <x-admin.media-picker :name="$name" :label="$label" :selected="$selected" :assets="$mediaChoices" :required="$required"/>
@elseif($type==='integer')
    <x-admin.input :name="$name" :label="$label" type="number" :value="$value ?? ''" min="0" max="24" :required="$required" :hint="$hint"/>
@elseif($type==='enum')
    @php
        $inRule=collect($definition['validation'] ?? [])->first(fn($rule)=>is_string($rule) && str_starts_with($rule,'in:'));
        $choices=$inRule ? explode(',',substr($inRule,3)) : [];
        $dot=trim(str_replace(['[',']'],['.',''],$name),'.');
    @endphp
    <div><label class="admin-label" for="enum-{{ preg_replace('/[^a-zA-Z0-9_-]/','-',$name) }}">{{ $label }}</label><select class="admin-field" id="enum-{{ preg_replace('/[^a-zA-Z0-9_-]/','-',$name) }}" name="{{ $name }}" @required($required)>@foreach($choices as $choice)<option value="{{ $choice }}" @selected(old($dot,$value)===$choice)>{{ $choice==='bn' ? 'বাংলা (bn)' : ($choice==='en' ? 'English (en)' : $choice) }}</option>@endforeach</select>@error($dot)<p class="mt-2 text-xs text-rose-700">{{ $message }}</p>@enderror<p class="mt-2 text-xs leading-5 text-slate-500">{{ $hint }}</p></div>
@endif
