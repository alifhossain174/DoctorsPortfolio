// Dependency-free behavior tests with DOM doubles; these do not claim browser visual QA.
import { test } from 'node:test';
import assert from 'node:assert/strict';
import fs from 'node:fs';
import vm from 'node:vm';

class Element {
    constructor() {
        this.handlers = {}; this.attributes = {}; this.dataset = {}; this.style = {};
        this.children = []; this.inert = false; this.classes = new Set();
        this.classList = {
            contains: value => this.classes.has(value),
            add: value => this.classes.add(value), remove: value => this.classes.delete(value),
            toggle: (value, enabled) => { if (enabled) this.classes.add(value); else this.classes.delete(value); },
        };
    }
    addEventListener(name, handler) { (this.handlers[name] ??= []).push(handler); }
    fire(name, values = {}) {
        const event = { target: this, preventDefault() { this.defaultPrevented = true; }, ...values };
        for (const handler of this.handlers[name] ?? []) handler(event);
        return event;
    }
    setAttribute(name, value) { this.attributes[name] = value; }
    removeAttribute(name) { delete this.attributes[name]; }
    focus() { this.focused = true; }
    closest(selector) { return selector === '.filters' ? this.parentElement : null; }
    querySelector() { return this.image; }
}

function setup() {
    const ids = Object.fromEntries(['menuBtn','navLinks','lightbox','closeLightbox','lbImage','lbCaption'].map(id => [id, new Element()]));
    const document = new Element();
    document.body = new Element(); document.body.style.overflow = 'auto';
    document.getElementById = id => ids[id];
    const item = new Element(); item.dataset = { url: 'https://example.org/image.jpg', caption: 'Database caption' }; item.image = { alt: 'Database alternative text' };
    const sibling = new Element();
    const alreadyInert = new Element(); alreadyInert.inert = true;
    ids.lightbox.parentElement = { children: [sibling, alreadyInert, ids.lightbox] };
    const filter = new Element(); filter.parentElement = new Element();
    const mode = new Element(); mode.form = { requestSubmit() { this.submitted = true; } };
    document.querySelectorAll = selector => selector === '.filters .filter' ? [filter] : selector.startsWith('.gallery-item') ? [item] : selector === '.filters[aria-busy]' ? [filter.parentElement] : [];
    document.querySelector = () => mode;
    const window = new Element();
    window.scrollX = 4; window.scrollY = 350;
    window.scrollTo = (...position) => { window.restoredScroll = position; };
    window.media = new Element(); window.matchMedia = () => window.media;
    ids.menuBtn.dataset = { openLabel: 'Open from database', closeLabel: 'Close from database' };
    vm.runInNewContext(fs.readFileSync('public/assets/public.js','utf8'), { document, window, location: { href: 'http://localhost/gallery' }, URL });
    return { ids, document, window, item, sibling, alreadyInert, filter, mode };
}

test('mobile menu opens, closes on Escape/outside/resizing, and updates localized accessible labels', () => {
    const { ids, document, window } = setup();
    ids.menuBtn.fire('click');
    assert.equal(ids.navLinks.classList.contains('open'), true);
    assert.equal(ids.menuBtn.attributes['aria-label'], 'Close from database');
    document.fire('keydown', { key: 'Escape' });
    assert.equal(ids.menuBtn.attributes['aria-expanded'], 'false');
    assert.equal(ids.menuBtn.focused, true);
    ids.menuBtn.fire('click');
    document.fire('click', { target: new Element() });
    assert.equal(ids.navLinks.classList.contains('open'), false);
    ids.menuBtn.fire('click');
    window.media.fire('change', { matches: false });
    assert.equal(ids.navLinks.classList.contains('open'), false);
});

test('lightbox supports Enter/Space, escaped DOM copy, focus trapping/restoration, scroll and all close paths', () => {
    const { ids, document, window, item, sibling, alreadyInert } = setup();
    item.fire('keydown', { key: 'Enter' });
    assert.equal(ids.lightbox.attributes['aria-hidden'], 'false');
    assert.equal(ids.lbImage.alt, 'Database alternative text');
    assert.equal(ids.lbCaption.textContent, 'Database caption');
    assert.equal(sibling.inert, true);
    assert.equal(document.body.style.overflow, 'hidden');
    assert.equal(document.fire('keydown', { key: 'Tab', shiftKey: true }).defaultPrevented, true);
    document.fire('keydown', { key: 'Escape' });
    assert.equal(ids.lightbox.classList.contains('open'), false);
    assert.equal(sibling.inert, false);
    assert.equal(alreadyInert.inert, true);
    assert.equal(document.body.style.overflow, 'auto');
    assert.deepEqual([...window.restoredScroll], [4,350]);
    assert.equal(item.focused, true);
    item.fire('keydown', { key: ' ' });
    ids.lightbox.fire('click');
    assert.equal(ids.lightbox.classList.contains('open'), false);
    item.fire('click'); ids.closeLightbox.fire('click');
    assert.equal(ids.lightbox.attributes['aria-hidden'], 'true');
    item.dataset.url = 'javascript:alert(1)'; item.fire('click');
    assert.equal(ids.lightbox.classList.contains('open'), false);
});

test('filters retain native server navigation and contact mode changes only its GET form', () => {
    const { filter, mode, window } = setup();
    assert.equal(filter.fire('click', { button: 0 }).defaultPrevented, undefined);
    assert.equal(filter.parentElement.attributes['aria-busy'], 'true');
    window.fire('pageshow');
    assert.equal(filter.parentElement.attributes['aria-busy'], undefined);
    mode.fire('change');
    assert.equal(mode.form.submitted, true);
});
