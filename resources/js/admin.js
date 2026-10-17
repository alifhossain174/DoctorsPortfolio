// Optional enhancement for ordinary admin POST forms; no content or client routing.
// Hide only the unused link input. Language panels retain all selected values.
document.querySelectorAll('[data-target-control]').forEach(control => {
    const kind = control.querySelector('[data-target-kind]');
    const update = () => control.querySelectorAll('[data-target-part]').forEach(part => {
        const active = part.dataset.targetPart === kind.value;
        part.hidden = !active;
        part.querySelectorAll('input,select').forEach(input => { input.disabled = !active; });
    });
    kind.addEventListener('change', update);
    update();
});

// Same-origin picker messages change only a selected reference, never website content.
document.querySelectorAll('[data-media-picker]').forEach(picker => {
    const select = picker.querySelector('[data-media-select]');
    const preview = picker.querySelector('[data-media-preview]');
    const previewError = picker.querySelector('[data-media-preview-error]');
    const upload = picker.querySelector('[data-media-upload]');
    const uploadStatus = picker.querySelector('[data-media-upload-status]');
    let libraryWindow;
    const showPreview = () => {
        const option = select.selectedOptions[0];
        const url = option?.dataset.url ?? '';
        if (previewError) previewError.hidden = true;
        preview.hidden = !url;
        if (url) { preview.src = url; preview.alt = option.dataset.alt ?? ''; }
        else { preview.removeAttribute('src'); preview.alt = ''; }
    };
    select.addEventListener('change', () => {
        showPreview();
        if (uploadStatus) uploadStatus.textContent = '';
    });
    preview.addEventListener('error', () => {
        preview.hidden = true;
        if (previewError) previewError.hidden = false;
    });
    showPreview();
    const chooseAsset = data => {
        if (!Number.isSafeInteger(data?.id) || data.id < 1 || typeof data.title !== 'string' || typeof data.url !== 'string' || typeof data.alt !== 'string') return false;
        let url;
        try { url = new URL(data.url, window.location.href); } catch { return false; }
        if (!['http:', 'https:'].includes(url.protocol) || url.username || url.password) return false;
        let option = [...select.options].find(option => option.value === String(data.id));
        if (!option) { option = new Option(`#${data.id} · ${data.title}`, String(data.id)); select.add(option); }
        option.dataset.url = url.href; option.dataset.alt = data.alt;
        select.value = String(data.id); showPreview();
        return true;
    };
    if (upload) {
        picker.querySelector('[data-media-upload-controls]').hidden = false;
        upload.addEventListener('change', async () => {
            const file = upload.files[0];
            if (!file || picker.hasAttribute('data-media-uploading')) return;
            const form = picker.closest('form');
            const previousSelection = select.value;
            const body = new FormData();
            body.append('image', file);
            body.append('quick_upload', '1');
            body.append('_token', form.querySelector('input[name="_token"]').value);
            picker.setAttribute('data-media-uploading', '');
            upload.disabled = true; select.disabled = true;
            uploadStatus.textContent = 'Uploading image… Please wait before saving.';
            try {
                const response = await fetch(upload.dataset.uploadUrl, {
                    method: 'POST', credentials: 'same-origin',
                    headers: { Accept: 'application/json' }, body,
                });
                if ([401, 419].includes(response.status) || response.redirected) throw Error('Your session expired. Keep a copy of your changes, reload and sign in, then retry.');
                if (response.status === 413) throw Error('The image is too large for this server. Choose a smaller file.');
                const data = await response.json().catch(() => { throw Error('The server could not complete this upload. Check the library before retrying.'); });
                if (!response.ok) throw Error(Object.values(data.errors ?? {}).flat().join(' ') || 'The image could not be uploaded. Please retry.');
                if (response.status !== 201 || !chooseAsset(data)) throw Error('The upload response could not be read. Check the library before retrying.');
                uploadStatus.textContent = 'Image uploaded and selected. Save this form to apply it.';
            } catch (error) {
                select.value = previousSelection; showPreview();
                uploadStatus.textContent = error?.name !== 'TypeError' && error instanceof Error && error.message ? error.message : 'The upload could not finish. Check your connection and the image library before retrying.';
            } finally {
                picker.removeAttribute('data-media-uploading');
                upload.disabled = false; select.disabled = false; upload.value = '';
            }
        });
        picker.closest('form').addEventListener('submit', event => {
            if (!picker.hasAttribute('data-media-uploading')) return;
            event.preventDefault();
            uploadStatus.textContent = 'Please wait for the image upload to finish, then save this form.';
            uploadStatus.focus();
        });
    }
    picker.querySelector('[data-media-browse]').addEventListener('click', event => {
        // If a popup is blocked, retain the ordinary library link.
        libraryWindow = window.open(event.currentTarget.href, '_blank', 'width=1100,height=800');
        if (libraryWindow) event.preventDefault();
    });
    window.addEventListener('message', event => {
        if (event.origin !== window.location.origin || !libraryWindow || event.source !== libraryWindow) return;
        const data = event.data;
        if (picker.hasAttribute('data-media-uploading') || data?.type !== 'doctor-portfolio:media') return;
        if (chooseAsset(data)) select.focus();
    });
});
document.querySelectorAll('[data-media-choose]').forEach(button => button.addEventListener('click', () => {
    if (!window.opener) { button.textContent = `Image ID ${button.dataset.mediaId} — select it in your editor`; return; }
    window.opener.postMessage({ type: 'doctor-portfolio:media', id: Number(button.dataset.mediaId), url: button.dataset.mediaUrl, title: button.dataset.mediaTitle, alt: button.dataset.mediaAlt }, window.location.origin);
    window.close();
}));
document.querySelectorAll('[data-copy-url]').forEach(button => button.addEventListener('click', async () => {
    const input = document.getElementById(button.dataset.copyUrl);
    const status = button.parentElement.querySelector('[data-copy-status]');
    try { await navigator.clipboard.writeText(input.value); status.textContent = 'Public URL copied.'; }
    catch { input.focus(); input.select(); status.textContent = 'Select and copy this URL using your keyboard.'; }
}));

document.addEventListener('submit', (event) => {
    const form = event.target;
    if (!(form instanceof HTMLFormElement) || !form.hasAttribute('data-prevent-double-submit')) return;
    if (form.dataset.submitting === 'true') {
        event.preventDefault();
        return;
    }

    // Keep the submitter enabled so its name/value reaches the server.
    queueMicrotask(() => {
        if (!event.defaultPrevented) {
            form.dataset.submitting = 'true';
            event.submitter?.setAttribute('aria-disabled', 'true');
        }
    });
});

window.addEventListener('pageshow', () => {
    document.querySelectorAll('form[data-submitting]').forEach((form) => {
        delete form.dataset.submitting;
        form.querySelectorAll('[aria-disabled="true"]').forEach((button) => button.removeAttribute('aria-disabled'));
    });
});

// Plain paragraph arrays, submitted by the parent Blade form in DOM order.
document.querySelectorAll('[data-paragraph-repeater]').forEach(repeater => {
    const list = repeater.querySelector('[data-paragraph-list]');
    repeater.addEventListener('click', event => {
        const button = event.target.closest('button');
        if (!button) return;
        const row = button.closest('[data-paragraph-row]');
        if (button.hasAttribute('data-paragraph-add')) {
            list.append(repeater.querySelector('template').content.cloneNode(true));
            list.lastElementChild.querySelector('textarea').focus();
        } else if (row && button.hasAttribute('data-paragraph-up') && row.previousElementSibling) {
            list.insertBefore(row, row.previousElementSibling);
            button.focus();
        } else if (row && button.hasAttribute('data-paragraph-down') && row.nextElementSibling) {
            list.insertBefore(row.nextElementSibling, row);
            button.focus();
        } else if (row && button.hasAttribute('data-paragraph-remove')) {
            const next = row.nextElementSibling ?? row.previousElementSibling;
            if (list.children.length > 1) row.remove();
            else row.querySelector('textarea').value = '';
            (next?.querySelector('textarea') ?? list.querySelector('textarea')).focus();
        }
    });
});

// Sidebar is a mobile enhancement. Desktop navigation and no-JS fallback stay server rendered.
const sidebar = document.getElementById('adminSidebar');
const sidebarToggle = document.getElementById('sidebarToggle');
const sidebarBackdrop = document.getElementById('sidebarBackdrop');
const desktop = window.matchMedia('(min-width:1024px)');
let sidebarFocus;
let sidebarOverflow;
let containerInert;
let sidebarOpen = false;
const closeSidebar = () => {
    if (!sidebarOpen) return;
    sidebarOpen = false;
    sidebar.classList.add('hidden'); sidebar.classList.remove('flex');
    sidebarBackdrop.classList.add('hidden');
    sidebarToggle.setAttribute('aria-expanded', 'false');
    sidebar.nextElementSibling.inert = containerInert;
    document.body.style.overflow = sidebarOverflow;
    sidebarFocus?.focus({ preventScroll: true });
};
sidebarToggle?.addEventListener('click', () => {
    if (sidebarOpen) { closeSidebar(); return; }
    sidebarOpen = true;
    sidebarFocus = document.activeElement;
    sidebarOverflow = document.body.style.overflow;
    containerInert = sidebar.nextElementSibling.inert;
    sidebar.nextElementSibling.inert = true;
    document.body.style.overflow = 'hidden';
    sidebar.classList.remove('hidden'); sidebar.classList.add('flex');
    sidebarBackdrop.classList.remove('hidden');
    sidebarToggle.setAttribute('aria-expanded', 'true');
    sidebar.querySelector('[data-sidebar-close]').focus();
});
document.querySelectorAll('[data-sidebar-close]').forEach(control => control.addEventListener('click', closeSidebar));
desktop.addEventListener('change', event => { if (event.matches) closeSidebar(); });
document.addEventListener('keydown', event => {
    if (!sidebarOpen) return;
    if (event.key === 'Escape') { event.preventDefault(); closeSidebar(); }
    if (event.key === 'Tab') {
        const controls = [...sidebar.querySelectorAll('a[href],button:not([disabled])')].filter(element => element.getClientRects().length);
        const first = controls[0], last = controls.at(-1);
        if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus(); }
        else if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus(); }
    }
});

// Hide panels, never remove/disable their inputs: both languages submit together.
document.querySelectorAll('[data-editor-tabs]').forEach(editor => {
    const buttons = [...editor.querySelectorAll('[data-editor-tab]')];
    const panels = [...editor.querySelectorAll('[data-editor-panel]')];
    editor.querySelector('[data-editor-tablist]').setAttribute('role', 'tablist');
    const activate = language => {
        buttons.forEach(button => {
            const selected = button.dataset.editorTab === language;
            button.setAttribute('role', 'tab'); button.setAttribute('aria-selected', String(selected));
            button.tabIndex = selected ? 0 : -1;
            for (const name of ['bg-white','text-jade-800','shadow-xs']) button.classList.toggle(name, selected);
            button.classList.toggle('text-slate-500', !selected);
        });
        panels.forEach(panel => { panel.setAttribute('role', 'tabpanel'); panel.hidden = panel.dataset.editorPanel !== language; });
    };
    buttons.forEach((button, index) => {
        button.addEventListener('click', () => activate(button.dataset.editorTab));
        button.addEventListener('keydown', event => {
            if (!['ArrowLeft','ArrowRight','Home','End'].includes(event.key)) return;
            event.preventDefault();
            const next = event.key === 'Home' ? 0 : event.key === 'End' ? buttons.length - 1 : (index + (event.key === 'ArrowRight' ? 1 : -1) + buttons.length) % buttons.length;
            activate(buttons[next].dataset.editorTab); buttons[next].focus();
        });
    });
    editor.addEventListener('invalid', event => {
        const panel = event.target.closest('[data-editor-panel]');
        if (panel) activate(panel.dataset.editorPanel);
    }, true);
    activate(panels.find(panel => panel.querySelector('[aria-invalid="true"]'))?.dataset.editorPanel ?? 'en');
});

document.querySelectorAll('[data-delete-form]').forEach(form => {
    const dialog = document.getElementById(form.dataset.deleteDialog);
    if (!dialog?.showModal) return;
    const checkbox = form.querySelector('[data-delete-checkbox]');
    checkbox.required = false;
    form.querySelector('[data-delete-fallback]').hidden = true;
    let confirmed = false;
    let submitter;
    form.addEventListener('submit', event => {
        if (confirmed) return;
        event.preventDefault(); submitter = event.submitter;
        dialog.showModal(); dialog.querySelector('[data-dialog-close]').focus();
    });
    const cancel = () => { dialog.close(); submitter?.focus(); };
    dialog.querySelector('[data-dialog-close]').addEventListener('click', cancel);
    dialog.addEventListener('cancel', event => { event.preventDefault(); cancel(); });
    dialog.addEventListener('click', event => { if (event.target === dialog) cancel(); });
    dialog.querySelector('[data-delete-confirm]').addEventListener('click', () => {
        confirmed = true; checkbox.checked = true; dialog.close(); form.requestSubmit(submitter);
    });
});
document.querySelectorAll('[data-dismiss-flash]').forEach(button => button.addEventListener('click', () => button.closest('[data-flash]').remove()));
