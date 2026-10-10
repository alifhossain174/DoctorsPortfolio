{{-- Reuse in article create/edit forms. Pass a validated localized body array.
     Native submission is body[en][] / body[bn][] in DOM order; escape every value.
     The future request must trim/remove blank paragraphs and validate plain strings. --}}
@foreach (['en' => 'English', 'bn' => 'বাংলা'] as $language => $editorLabel)
    <fieldset class="mb-6 rounded border p-4" data-paragraph-repeater>
        <legend class="px-2 font-semibold">{{ $editorLabel }}</legend>
        <div data-paragraph-list>
            @foreach (old('body.'.$language, $body[$language] ?? ['']) ?: [''] as $paragraph)
                @include('admin.partials.paragraph-row', ['language' => $language, 'paragraph' => $paragraph])
            @endforeach
        </div>
        <template data-paragraph-template>@include('admin.partials.paragraph-row', ['language' => $language, 'paragraph' => ''])</template>
        <button type="button" class="mt-3 rounded border px-3 py-1" data-paragraph-add>{{ $language === 'en' ? 'Add paragraph' : 'অনুচ্ছেদ যোগ করুন' }}</button>
    </fieldset>
@endforeach
