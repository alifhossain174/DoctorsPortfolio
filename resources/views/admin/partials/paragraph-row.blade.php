<div class="mb-3" data-paragraph-row>
    <textarea class="block min-h-28 w-full rounded border p-2" name="body[{{ $language }}][]" maxlength="15000" aria-label="{{ $language === 'en' ? 'English paragraph' : 'বাংলা অনুচ্ছেদ' }}">{{ $paragraph }}</textarea>
    <div class="mt-2 flex gap-2">
        <button type="button" class="rounded border px-2 py-1" data-paragraph-up>{{ $language === 'en' ? 'Move up' : 'উপরে নিন' }}</button>
        <button type="button" class="rounded border px-2 py-1" data-paragraph-down>{{ $language === 'en' ? 'Move down' : 'নিচে নিন' }}</button>
        <button type="button" class="rounded border px-2 py-1" data-paragraph-remove>{{ $language === 'en' ? 'Remove' : 'সরান' }}</button>
    </div>
</div>
