<div data-paragraph-repeater>
    <div data-paragraph-list>@foreach ($paragraphs as $paragraph)@include('admin.partials.paragraph-row', ['language' => $language, 'paragraph' => $paragraph])@endforeach</div>
    <template data-paragraph-template>@include('admin.partials.paragraph-row', ['language' => $language, 'paragraph' => ''])</template>
    <x-admin.button type="button" variant="secondary" data-paragraph-add>Add paragraph</x-admin.button>
</div>
