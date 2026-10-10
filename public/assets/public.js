// Enhancements use database-rendered DOM; there is no client content catalog.
const menu = document.getElementById('menuBtn');
const navigation = document.getElementById('navLinks');
menu?.addEventListener('click', () => {
    const open = navigation.classList.toggle('open');
    menu.setAttribute('aria-expanded', String(open));
});
document.querySelectorAll('.filters').forEach(filters => {
    filters.querySelectorAll('.filter').forEach(button => button.addEventListener('click', event => {
        event.preventDefault();
        filters.querySelectorAll('.filter').forEach(item => item.classList.toggle('selected', item === button));
        const selector = filters.dataset.filter === 'gallery' ? '#galleryGrid .gallery-item' : '#articleGrid .article-card';
        document.querySelectorAll(selector).forEach(item => item.classList.toggle('hidden', button.dataset.cat !== 'all' && item.dataset.category !== button.dataset.cat));
        history.replaceState(null, '', button.href);
    }));
});
const dialog = document.getElementById('lightbox');
const closeButton = document.getElementById('closeLightbox');
let previousFocus;
const close = () => { dialog?.classList.remove('open'); previousFocus?.focus(); };
document.querySelectorAll('.gallery-item').forEach(item => {
    const show = () => {
        previousFocus = item;
        document.getElementById('lbImage').src = item.dataset.url;
        document.getElementById('lbImage').alt = item.querySelector('img').alt;
        document.getElementById('lbCaption').textContent = item.dataset.caption;
        dialog.classList.add('open');
        closeButton.focus();
    };
    item.addEventListener('click', show);
    item.addEventListener('keydown', event => { if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); show(); } });
});
closeButton?.addEventListener('click', close);
dialog?.addEventListener('click', event => { if (event.target === dialog) close(); });
document.addEventListener('keydown', event => {
    if (!dialog?.classList.contains('open')) return;
    if (event.key === 'Escape') close();
    if (event.key === 'Tab') { event.preventDefault(); closeButton.focus(); }
});
