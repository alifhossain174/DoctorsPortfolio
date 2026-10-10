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

function mediaSetup() {
    const document = new Element(); document.body = new Element();
    const create = () => { const item = new Element(); item.document = document; return item; };
    const picker = create(), select = create(), preview = create(), browse = create();
    const empty = create(); empty.value = ''; select.options = [empty]; select.value = '';
    Object.defineProperty(select, 'selectedOptions', { get: () => select.options.filter(option=>option.value===select.value) });
    select.add = option => select.options.push(option);
    browse.href = 'https://example.test/admin/media?picker=1';
    picker.querySelector = selector => ({ '[data-media-select]':select,'[data-media-preview]':preview,'[data-media-browse]':browse })[selector];
    const choose = create(); choose.dataset = { mediaId:'9',mediaUrl:'https://example.test/uploads/image.png',mediaTitle:'Selected title',mediaAlt:'Selected alt' };
    const copy = create(), input = create(), status = create(); copy.dataset.copyUrl='public-url'; input.value='https://example.test/uploads/image.png'; input.select=()=>{input.selected=true;}; copy.parentElement={querySelector:()=>status};
    document.querySelectorAll = selector => ({ '[data-media-picker]':[picker],'[data-media-choose]':[choose],'[data-copy-url]':[copy] })[selector] ?? [];
    document.getElementById = id => id==='public-url' ? input : null;
    const window = create(); const popup = {}; window.matchMedia=()=>create();
    window.location={origin:'https://example.test',href:'https://example.test/admin/media/9/edit'};
    window.open=()=>popup; window.close=()=>{window.closed=true;};
    window.opener={postMessage:(message,origin)=>{window.sent={message,origin};}};
    const navigator={clipboard:{writeText: async ()=>{ throw Error('Unavailable'); }}};
    function Option(text,value) { const option=create(); option.text=text; option.value=value; return option; }
    vm.runInNewContext(fs.readFileSync('resources/js/admin.js','utf8'),{document,window,navigator,Option,URL,HTMLFormElement:Element,queueMicrotask});
    return { document,window,popup,select,preview,browse,choose,copy,input,status };
}

test('shared media picker accepts only its same-origin popup and updates a reference without submitting', () => {
    const {window,popup,select,preview,browse,choose} = mediaSetup();
    assert.equal(preview.hidden,true);
    assert.equal(browse.fire('click',{currentTarget:browse}).defaultPrevented,true);
    const data={type:'doctor-portfolio:media',id:14,title:'<img onerror=unsafe>',alt:'বাংলা',url:'https://example.test/uploads/image.png'};
    window.fire('message',{origin:'https://attacker.test',source:popup,data}); assert.equal(select.value,'');
    window.fire('message',{origin:window.location.origin,source:{},data}); assert.equal(select.value,'');
    window.fire('message',{origin:window.location.origin,source:popup,data:{...data,url:'javascript:alert(1)'}}); assert.equal(select.value,'');
    window.fire('message',{origin:window.location.origin,source:popup,data});
    assert.equal(select.value,'14'); assert.equal(preview.hidden,false); assert.equal(preview.alt,'বাংলা');
    assert.equal(select.options.at(-1).text,'#14 · <img onerror=unsafe>');
    select.value=''; select.fire('change'); assert.equal(preview.hidden,true);
    choose.fire('click'); assert.equal(window.sent.origin,window.location.origin); assert.equal(window.sent.message.id,9); assert.equal(window.closed,true);
});

test('copy URL falls back to keyboard selection when clipboard access fails', async () => {
    const {copy,input,status} = mediaSetup();
    copy.fire('click'); await new Promise(setImmediate);
    assert.equal(input.selected,true); assert.match(status.textContent,/keyboard/);
});
