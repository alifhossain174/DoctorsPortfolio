@props(['name', 'label', 'type' => 'text', 'value' => '', 'bag' => 'default', 'hint' => null, 'id' => null, 'errorKey' => null])
@php
    $id = $id ?? 'field-'.preg_replace('/[^a-zA-Z0-9_-]/', '-', $name);
    $errorKey = $errorKey ?? trim(str_replace(['[', ']'], ['.', ''], $name), '.');
    $error = $errors->getBag($bag)->first($errorKey);
    $currentValue = old($errorKey, $value);
    $currentValue = is_scalar($currentValue) ? (string) $currentValue : '';
@endphp
<div>
    <label class="admin-label" for="{{ $id }}">{{ $label }}</label>
    <input id="{{ $id }}" name="{{ $name }}" type="{{ $type }}" @if ($type !== 'password') value="{{ $currentValue }}" @endif {{ $attributes->class(['admin-field', 'border-rose-500' => (bool) $error]) }} @if ($error) aria-invalid="true" @endif @if ($error || $hint) aria-describedby="{{ $id }}-help" @endif>
    @if ($error || $hint)<p id="{{ $id }}-help" @class(['mt-2 text-xs leading-5', 'text-rose-700' => (bool) $error, 'text-slate-500' => ! $error])>{{ $error ?: $hint }}</p>@endif
</div>
