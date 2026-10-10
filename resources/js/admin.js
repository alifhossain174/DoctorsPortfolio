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
