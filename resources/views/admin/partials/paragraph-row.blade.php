<div class="mb-3" data-paragraph-row>
    <label class="admin-label">{{ $language === 'en' ? 'English paragraph' : 'বাংলা অনুচ্ছেদ' }}<textarea class="admin-field mt-2 min-h-28" name="body[{{ $language }}][]" maxlength="15000">{{ $paragraph }}</textarea></label>
    <div class="mt-2 flex gap-2">
        <x-admin.button type="button" variant="quiet" data-paragraph-up>Move up</x-admin.button>
        <x-admin.button type="button" variant="quiet" data-paragraph-down>Move down</x-admin.button>
        <x-admin.button type="button" variant="quiet" data-paragraph-remove>Remove</x-admin.button>
    </div>
</div>
