@props(['action', 'label' => 'record', 'id'])
<div>
    <form method="post" action="{{ $action }}" id="{{ $id }}-form" data-delete-form data-delete-dialog="{{ $id }}" data-prevent-double-submit>@csrf @method('DELETE')<label class="mb-3 flex items-center gap-2 text-sm text-slate-600" data-delete-fallback><input type="checkbox" name="confirm_delete" value="1" required class="h-4 w-4 accent-rose-700" data-delete-checkbox>I confirm that this {{ $label }} should be deleted.</label><x-admin.button variant="danger">Delete {{ $label }}</x-admin.button></form>
    <dialog id="{{ $id }}" class="m-auto w-full max-w-md rounded-2xl border-0 bg-white p-6 shadow-xl" aria-labelledby="{{ $id }}-title">
        <h2 id="{{ $id }}-title" class="text-lg font-semibold text-slate-900">Delete {{ $label }}?</h2><p class="mt-2 text-sm leading-6 text-slate-500">This action permanently removes this record. Check that it is no longer needed.</p>
        <div class="mt-6 flex justify-end gap-3"><x-admin.button variant="secondary" type="button" data-dialog-close>Cancel</x-admin.button><x-admin.button variant="danger" type="button" data-delete-confirm>Delete record</x-admin.button></div>
    </dialog>
</div>
