// Optional enhancement for ordinary admin POST forms; no content or client routing.
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
