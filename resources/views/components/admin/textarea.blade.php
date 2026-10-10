@props(['name', 'label', 'value' => '', 'bag' => 'default', 'hint' => null, 'id' => null])
@php
    $id = $id ?? 'field-'.preg_replace('/[^a-zA-Z0-9_-]/', '-', $name);
    $errorKey = trim(str_replace(['[', ']'], ['.', ''], $name), '.');
    $error = $errors->getBag($bag)->first($errorKey);
    $currentValue = old($errorKey, $value);
    $currentValue = is_string($currentValue) ? $currentValue : '';
@endphp
<div><label class="admin-label" for="{{ $id }}">{{ $label }}</label><textarea id="{{ $id }}" name="{{ $name }}" rows="5" {{ $attributes->class(['admin-field', 'border-rose-500' => (bool) $error]) }} @if ($error) aria-invalid="true" @endif @if ($error || $hint) aria-describedby="{{ $id }}-help" @endif>{{ $currentValue }}</textarea>@if ($error || $hint)<p id="{{ $id }}-help" @class(['mt-2 text-xs leading-5', 'text-rose-700' => (bool) $error, 'text-slate-500' => ! $error])>{{ $error ?: $hint }}</p>@endif</div>
