@props(['name', 'label', 'value' => [], 'type' => 'text', 'required' => false, 'hint' => null, 'maxlength'=>null, 'routes'=>null, 'allowExternal'=>true])
@php
    $editorId = 'editor-'.preg_replace('/[^a-zA-Z0-9_-]/', '-', $name);
    $dot=trim(str_replace(['[',']'],['.',''],$name),'.');
    $bangla = old($dot.'.bn', $value['bn'] ?? null);
    $missingBangla = $type === 'paragraphs' && is_array($bangla) ? count(array_filter($bangla, fn ($paragraph) => is_string($paragraph) && trim($paragraph) !== '')) === 0 : trim(is_string($bangla) ? $bangla : '') === '';
@endphp
<fieldset class="space-y-4" data-editor-tabs>
    <legend class="mb-3 flex flex-wrap items-center gap-3 text-sm font-semibold text-slate-800">{{ $label }} @if ($missingBangla)<x-admin.badge tone="warning">Bangla missing</x-admin.badge>@endif</legend>
    <div class="flex gap-1 rounded-xl bg-slate-100 p-1" data-editor-tablist aria-label="{{ $label }} language">
        <button type="button" class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-jade-800 shadow-xs" data-editor-tab="en" id="{{ $editorId }}-en-tab" aria-controls="{{ $editorId }}-en-panel">English</button>
        <button type="button" class="rounded-lg px-4 py-2 text-sm font-semibold text-slate-500" data-editor-tab="bn" id="{{ $editorId }}-bn-tab" aria-controls="{{ $editorId }}-bn-panel">বাংলা</button>
    </div>
    @foreach (['en' => 'English', 'bn' => 'বাংলা'] as $language => $languageLabel)
        <div id="{{ $editorId }}-{{ $language }}-panel" data-editor-panel="{{ $language }}" aria-labelledby="{{ $editorId }}-{{ $language }}-tab">
            @if ($type === 'paragraphs')
                @php
                    $paragraphValues = old($dot.'.'.$language, $value[$language] ?? []);
                    $paragraphValues = is_array($paragraphValues) ? array_values(array_filter($paragraphValues, 'is_string')) : [];
                @endphp
                @include('admin.partials.paragraph-language', ['language' => $language, 'paragraphs' => $paragraphValues ?: ['']])
            @elseif ($type === 'target')
                <x-admin.link-target :name="$name.'['.$language.']'" :label="$languageLabel" :value="$value[$language] ?? ''" :required="$required && $language === 'en'" :hint="$hint" :routes="$routes" :allow-external="$allowExternal"/>
            @elseif ($type === 'icon')
                @php($errorKey=$dot.'.'.$language)
                <label class="admin-label" for="{{ $editorId }}-{{ $language }}-icon">{{ $languageLabel }} icon</label><select id="{{ $editorId }}-{{ $language }}-icon" name="{{ $name }}[{{ $language }}]" class="admin-field" @required($required && $language==='en')><option value="">{{ $language==='en' ? 'Choose a safe icon' : 'Use English icon' }}</option>@foreach(\App\Services\ContentRegistry::ICONS as $icon)<option value="{{ $icon }}" @selected(old($errorKey,$value[$language] ?? '')===$icon)>{{ $icon }}</option>@endforeach</select>@error($errorKey)<p class="text-xs text-rose-700">{{ $message }}</p>@enderror<p class="text-xs leading-5 text-slate-500">{{ $hint }} Fixed glyphs only; no SVG, HTML or script input.</p>
            @elseif ($type === 'textarea')
                <x-admin.textarea :name="$name.'['.$language.']'" :label="$languageLabel" :value="$value[$language] ?? ''" :required="$required && $language === 'en'" :hint="$hint" :maxlength="$maxlength"/>
            @else
                <x-admin.input :name="$name.'['.$language.']'" :label="$languageLabel" :value="$value[$language] ?? ''" :required="$required && $language === 'en'" :hint="$hint" :type="$type==='url' ? 'url' : 'text'" :maxlength="$maxlength"/>
            @endif
        </div>
    @endforeach
    <p class="text-xs leading-5 text-slate-500">Both translations are saved together. Empty Bangla uses the English value on the public website.</p>
</fieldset>
