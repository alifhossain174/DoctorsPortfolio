@props(['name','label','value'=>'','routes'=>null,'required'=>false,'hint'=>null,'allowExternal'=>true])
@php
    $dot=trim(str_replace(['[',']'],['.',''],$name),'.');
    $current=old($dot,$value);
    $isExternal=is_string($current) && \App\Rules\PublicTarget::safeExternal($current);
    $kind=is_array($current) ? ($current['kind'] ?? 'internal') : ($isExternal ? 'external' : 'internal');
    $internal=is_array($current) ? ($current['route'] ?? '') : ($isExternal ? '' : $current);
    $external=is_array($current) ? ($current['url'] ?? '') : ($isExternal ? $current : '');
    $routes=$routes ?? \App\Rules\PublicTarget::ROUTES;
@endphp
<div class="space-y-3" data-target-control>
    <label class="admin-label" for="target-{{ preg_replace('/[^a-zA-Z0-9_-]/','-',$name) }}">{{ $label }} destination {{ $required ? '(required)' : '(optional; English fallback)' }}</label>
    <select id="target-{{ preg_replace('/[^a-zA-Z0-9_-]/','-',$name) }}" name="{{ $name }}[kind]" class="admin-field" data-target-kind><option value="internal" @selected($kind==='internal')>Internal named page</option>@if($allowExternal && count($routes)>1)<option value="external" @selected($kind==='external')>External HTTP(S) URL</option>@endif</select>
    <div data-target-part="internal"><label class="admin-label" for="route-{{ preg_replace('/[^a-zA-Z0-9_-]/','-',$name) }}">Public page</label><select class="admin-field" name="{{ $name }}[route]" id="route-{{ preg_replace('/[^a-zA-Z0-9_-]/','-',$name) }}"><option value="">Choose a page</option>@foreach($routes as $routeKey)<option value="{{ $routeKey }}" @selected($internal===$routeKey)>{{ ucfirst($routeKey) }} ({{ $routeKey }})</option>@endforeach</select></div>
    @if($allowExternal)<div data-target-part="external"><x-admin.input :name="$name.'[url]'" label="External URL" type="url" :value="is_string($external) ? $external : ''" maxlength="2048" placeholder="https://…"/></div>@endif
    @error($dot)<p class="text-xs text-rose-700" role="alert">{{ $message }}</p>@enderror
    <p class="text-xs leading-5 text-slate-500">{{ $hint ?? 'Only the selected destination type is saved. Choose an existing route key or an HTTP(S) URL without credentials.' }}</p>
</div>
