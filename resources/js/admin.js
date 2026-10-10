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
