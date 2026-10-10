// Interactions only. All labels, URLs and category identities come from rendered DOM.
const menu = document.getElementById('menuBtn');
const navigation = document.getElementById('navLinks');
const setMenu = open => {
    if (!menu || !navigation) return;
    navigation.classList.toggle('open', open);
    menu.setAttribute('aria-expanded', String(open));
    menu.setAttribute('aria-label', open ? menu.dataset.closeLabel : menu.dataset.openLabel);
};
menu?.addEventListener('click', () => setMenu(!navigation.classList.contains('open')));
document.addEventListener('click', event => {
    if (navigation?.classList.contains('open') && !event.target.closest('.nav')) setMenu(false);
});
window.matchMedia('(max-width:850px)').addEventListener('change', event => {
    if (!event.matches) setMenu(false);
});

// Native category links perform server-side filtering across the complete collection.
// Keep their native navigation, new-tab and no-JS behavior; never hide only loaded cards.
document.querySelectorAll('.filters .filter').forEach(link => {
    link.addEventListener('click', event => {
        if (event.button === 0 && !event.ctrlKey && !event.metaKey && !event.shiftKey && !event.altKey) {
            link.closest('.filters').setAttribute('aria-busy', 'true');
        }
    });
});
const inquiryForm = document.getElementById('appointmentForm');
if (inquiryForm) {
    const mode = inquiryForm.querySelector('[data-form-mode]');
    const topic = inquiryForm.querySelector('#topic');
    const message = inquiryForm.querySelector('#patientMessage');
    const submit = inquiryForm.querySelector('button[type="submit"]');
    const updateMode = () => {
        const appointment = mode.value === 'appointment';
        inquiryForm.querySelectorAll('[data-appointment-field]').forEach(element => { element.hidden = !appointment; });
        topic.disabled = !appointment;
        topic.required = appointment;
        message.required = !appointment;
        submit.disabled = appointment && topic.options.length === 0;
        submit.textContent = `${appointment ? inquiryForm.dataset.appointmentSubmit : inquiryForm.dataset.contactSubmit} ↗`;
    };
    mode.addEventListener('change', updateMode);
    updateMode();
    inquiryForm.addEventListener('submit', () => {
        // Native POST remains responsible for saving. Pending is never a success claim.
        submit.disabled = true;
        inquiryForm.setAttribute('aria-busy', 'true');
        document.getElementById('formStatus').textContent = inquiryForm.dataset.pending;
    });
    window.addEventListener('pageshow', () => {
        inquiryForm.removeAttribute('aria-busy');
        const status = document.getElementById('formStatus');
        if (status.textContent === inquiryForm.dataset.pending) status.textContent = '';
        updateMode();
    });
}

const dialog = document.getElementById('lightbox');
const closeButton = document.getElementById('closeLightbox');
const preview = document.getElementById('lbImage');
let previousFocus;
let previousOverflow;
let scrollPosition;
let siblings = [];
const close = () => {
    if (!dialog?.classList.contains('open')) return;
    dialog.classList.remove('open');
    dialog.setAttribute('aria-hidden', 'true');
    siblings.forEach(([element, inert]) => { element.inert = inert; });
    siblings = [];
    document.body.style.overflow = previousOverflow;
    preview.removeAttribute('src');
    window.scrollTo(...scrollPosition);
    previousFocus?.focus({ preventScroll: true });
};
document.querySelectorAll('.gallery-item[role="button"]').forEach(item => {
    const show = () => {
        if (!dialog || !closeButton || !preview || !item.dataset.url) return;
        let url;
        try { url = new URL(item.dataset.url, location.href); } catch { return; }
        if (url.protocol !== 'http:' && url.protocol !== 'https:') return;
        previousFocus = item;
        previousOverflow = document.body.style.overflow;
        scrollPosition = [window.scrollX, window.scrollY];
        preview.src = url.href;
        preview.alt = item.querySelector('img')?.alt ?? '';
        document.getElementById('lbCaption').textContent = item.dataset.caption ?? '';
        siblings = [...dialog.parentElement.children].filter(element => element !== dialog).map(element => [element, element.inert]);
        siblings.forEach(([element]) => { element.inert = true; });
        document.body.style.overflow = 'hidden';
        dialog.classList.add('open');
        dialog.setAttribute('aria-hidden', 'false');
        closeButton.focus({ preventScroll: true });
    };
    item.addEventListener('click', show);
    item.addEventListener('keydown', event => {
        if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); show(); }
    });
});
closeButton?.addEventListener('click', close);
dialog?.addEventListener('click', event => { if (event.target === dialog) close(); });
document.addEventListener('keydown', event => {
    if (dialog?.classList.contains('open')) {
        if (event.key === 'Escape') { event.preventDefault(); close(); }
        if (event.key === 'Tab') {
            // The original dialog has a single focusable control: its close button.
            event.preventDefault();
            closeButton.focus({ preventScroll: true });
        }
    } else if (event.key === 'Escape' && navigation?.classList.contains('open')) {
        setMenu(false);
        menu.focus();
    }
});
window.addEventListener('pageshow', () => {
    document.querySelectorAll('.filters[aria-busy]').forEach(element => element.removeAttribute('aria-busy'));
});
