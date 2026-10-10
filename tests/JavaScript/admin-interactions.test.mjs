// DOM doubles verify interaction contracts; they do not claim browser visual testing.
import { test } from 'node:test';
import assert from 'node:assert/strict';
import fs from 'node:fs';
import vm from 'node:vm';

class Element {
    constructor() {
        this.handlers = {}; this.attributes = {}; this.dataset = {}; this.style = {};
        this.classes = new Set(); this.children = []; this.inert = false;
        this.classList = {
            contains: value => this.classes.has(value), add: value => this.classes.add(value), remove: value => this.classes.delete(value),
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
    hasAttribute(name) { return Object.hasOwn(this.attributes, name); }
    removeAttribute(name) { delete this.attributes[name]; }
    focus() { this.document.activeElement = this; }
    getClientRects() { return [1]; }
    querySelector() { return null; }
    querySelectorAll() { return []; }
}

function setup() {
    const document = new Element(); document.body = new Element(); document.body.style.overflow = 'auto';
    const create = () => { const element = new Element(); element.document = document; return element; };
    const sidebar = create(), toggle = create(), backdrop = create(), close = create(), last = create();
    sidebar.classes.add('hidden'); backdrop.classes.add('hidden'); sidebar.nextElementSibling = create();
    sidebar.querySelector = () => close; sidebar.querySelectorAll = () => [close,last];
    document.activeElement = toggle;
    const editor = create(), tablist = create();
    const en = create(), bn = create(); en.dataset.editorTab = 'en'; bn.dataset.editorTab = 'bn';
    const enPanel = create(), bnPanel = create(); enPanel.dataset.editorPanel = 'en'; bnPanel.dataset.editorPanel = 'bn';
    const enInput = create(), bnInput = create(); enInput.value = 'Unsaved English'; bnInput.value = 'অসংরক্ষিত বাংলা';
    enInput.closest = () => enPanel; bnInput.closest = () => bnPanel;
    editor.querySelector = () => tablist;
    editor.querySelectorAll = selector => selector === '[data-editor-tab]' ? [en,bn] : [enPanel,bnPanel];
    const form = create(), dialog = create(), cancel = create(), confirm = create(), checkbox = create(), fallback = create();
    form.dataset.deleteDialog = 'deleteDialog'; form.attributes['data-prevent-double-submit'] = '';
    form.querySelector = selector => selector === '[data-delete-checkbox]' ? checkbox : fallback;
    dialog.querySelector = selector => selector === '[data-dialog-close]' ? cancel : confirm;
    dialog.showModal = () => { dialog.open = true; }; dialog.close = () => { dialog.open = false; };
    form.requestSubmit = submitter => {
        const event = form.fire('submit', { submitter });
        const bubbled = document.fire('submit', event);
        if (!bubbled.defaultPrevented) form.submissions = (form.submissions ?? 0) + 1;
    };
    document.getElementById = id => ({ adminSidebar: sidebar, sidebarToggle: toggle, sidebarBackdrop: backdrop, deleteDialog: dialog })[id];
    document.querySelectorAll = selector => ({ '[data-sidebar-close]': [close,backdrop], '[data-editor-tabs]': [editor], '[data-delete-form]': [form] })[selector] ?? [];
    const window = create(); window.media = create(); window.matchMedia = () => window.media;
    vm.runInNewContext(fs.readFileSync('resources/js/admin.js','utf8'), { document, window, HTMLFormElement: Element, queueMicrotask });
    return { document, window, sidebar, toggle, backdrop, close, last, editor, en, bn, enPanel, bnPanel, enInput, bnInput, form, dialog, cancel, confirm, checkbox };
}

test('language tabs preserve unsaved inputs, submit both languages and reveal invalid hidden fields', () => {
    const { editor,en,bn,enPanel,bnPanel,enInput,bnInput } = setup();
    assert.equal(enPanel.hidden, false); assert.equal(bnPanel.hidden, true);
    bn.fire('click');
    assert.equal(enPanel.hidden, true); assert.equal(bnPanel.hidden, false);
    assert.equal(enInput.value, 'Unsaved English'); assert.equal(bnInput.value, 'অসংরক্ষিত বাংলা');
    assert.notEqual(enInput.disabled, true); assert.notEqual(bnInput.disabled, true);
    bn.fire('keydown', { key: 'ArrowLeft' }); assert.equal(en.attributes['aria-selected'], 'true');
    editor.fire('invalid', { target: bnInput }); assert.equal(bnPanel.hidden, false);
});

test('mobile sidebar traps focus, restores background and closes by keyboard/backdrop/resize', () => {
    const { document,window,sidebar,toggle,backdrop,close,last } = setup();
    toggle.fire('click'); assert.equal(sidebar.classList.contains('hidden'), false);
    assert.equal(sidebar.nextElementSibling.inert, true); assert.equal(document.body.style.overflow, 'hidden');
    last.focus(); const tab = document.fire('keydown', { key: 'Tab' });
    assert.equal(tab.defaultPrevented, true); assert.equal(document.activeElement, close);
    document.fire('keydown', { key: 'Escape' });
    assert.equal(document.activeElement, toggle); assert.equal(sidebar.nextElementSibling.inert, false);
    assert.equal(document.body.style.overflow, 'auto');
    toggle.fire('click'); backdrop.fire('click'); assert.equal(toggle.attributes['aria-expanded'], 'false');
    toggle.fire('click'); window.media.fire('change', { matches: true }); assert.equal(sidebar.classList.contains('hidden'), true);
});

test('delete cancellation makes no request and confirmation submits once with explicit consent', async () => {
    const { form,dialog,cancel,confirm,checkbox } = setup(); const button = new Element(); button.document = form.document;
    form.requestSubmit(button); assert.equal(dialog.open, true); assert.equal(form.submissions, undefined);
    cancel.fire('click'); assert.equal(dialog.open, false); assert.equal(form.submissions, undefined);
    form.requestSubmit(button); confirm.fire('click'); await Promise.resolve();
    assert.equal(checkbox.checked, true); assert.equal(form.submissions, 1);
    form.requestSubmit(button); assert.equal(form.submissions, 1);
});
