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

test('destination selector retains inactive values and keeps each language independent', () => {
    const document = new Element(); document.body = new Element();
    const make = () => { const item = new Element(); item.document = document; return item; };
    const controls = ['en','bn'].map(language => {
        const control = make(), kind = make(), internal = make(), external = make();
        const route = make(), url = make(); route.value = 'services'; url.value = 'https://example.test/'+language;
        kind.value = 'internal'; internal.dataset.targetPart = 'internal'; external.dataset.targetPart = 'external';
        internal.querySelectorAll = () => [route]; external.querySelectorAll = () => [url];
        control.querySelector = () => kind; control.querySelectorAll = () => [internal, external];
        return { control, kind, internal, external, route, url };
    });
    document.querySelectorAll = selector => selector === '[data-target-control]' ? controls.map(item=>item.control) : [];
    document.getElementById = () => null;
    const window = make(); window.matchMedia = () => make();
    vm.runInNewContext(fs.readFileSync('resources/js/admin.js','utf8'), { document, window, HTMLFormElement: Element, queueMicrotask });
    const [en, bn] = controls;
    assert.equal(en.external.hidden, true); assert.equal(en.route.disabled, false); assert.equal(en.url.disabled, true);
    en.kind.value = 'external'; en.kind.fire('change');
    assert.equal(en.external.hidden, false); assert.equal(en.route.disabled, true); assert.equal(en.url.disabled, false);
    assert.equal(en.route.value, 'services'); assert.equal(bn.kind.value, 'internal'); assert.equal(bn.route.disabled, false);
    en.kind.value = 'internal'; en.kind.fire('change');
    assert.equal(en.url.value, 'https://example.test/en'); assert.equal(en.route.value, 'services');
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

function mediaSetup(options = {}) {
    const document = new Element(); document.body = new Element();
    const create = () => { const item = new Element(); item.document = document; return item; };
    const picker = create(), select = create(), preview = create(), browse = create();
    const upload = create(), uploadControls = create(), uploadStatus = create(), previewError = create(), form = create();
    const token = create(); token.value = 'csrf-test-token';
    const textInput = create(); textInput.value = 'Unsaved bilingual content';
    form.querySelector = () => token;
    picker.closest = () => form;
    upload.files = []; upload.dataset.uploadUrl = 'https://example.test/admin/media';
    const empty = create(); empty.value = ''; select.options = [empty]; select.value = '';
    Object.defineProperty(select, 'selectedOptions', { get: () => select.options.filter(option=>option.value===select.value) });
    select.add = option => select.options.push(option);
    browse.href = 'https://example.test/admin/media?picker=1';
    picker.querySelector = selector => ({ '[data-media-select]':select,'[data-media-preview]':preview,'[data-media-browse]':browse, '[data-media-upload]':upload, '[data-media-upload-controls]':uploadControls, '[data-media-upload-status]':uploadStatus, '[data-media-preview-error]':previewError })[selector];
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
    class FormData extends Map { append(key, value) { this.set(key, value); } }
    const requests = [];
    const fetch = async (url, settings) => { requests.push({ url, ...settings }); return options.fetch(url, settings); };
    vm.runInNewContext(fs.readFileSync('resources/js/admin.js','utf8'),{document,window,navigator,Option,URL,HTMLFormElement:Element,queueMicrotask,FormData,fetch,Error});
    return { document,window,popup,select,preview,browse,choose,copy,input,status,upload,uploadControls,uploadStatus,previewError,form,textInput,requests };
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

test('inline upload sends file and CSRF, blocks save while pending, and selects only after persistence', async () => {
    let finish;
    const state = mediaSetup({ fetch: () => new Promise(resolve => { finish = resolve; }) });
    const { upload,uploadControls,uploadStatus,form,select,preview,textInput,requests } = state;
    assert.equal(uploadControls.hidden, false);
    upload.files = [{ name: 'clinic.png' }]; upload.fire('change');
    assert.equal(upload.disabled, true); assert.equal(select.disabled, true);
    assert.equal(form.fire('submit').defaultPrevented, true);
    assert.equal(select.value, ''); assert.equal(textInput.value, 'Unsaved bilingual content');
    assert.equal(requests[0].body.get('_token'), 'csrf-test-token');
    assert.equal(requests[0].body.get('image'), upload.files[0]);
    assert.equal(requests[0].body.get('quick_upload'), '1');
    assert.equal(requests[0].body.size, 3);
    assert.equal(requests[0].credentials, 'same-origin');
    finish({ status: 201, ok: true, json: async () => ({ id: 18, title: 'clinic', alt: '', url: 'https://example.test/uploads/clinic.png' }) });
    await new Promise(setImmediate);
    assert.equal(select.value, '18'); assert.equal(preview.src, 'https://example.test/uploads/clinic.png');
    assert.equal(upload.disabled, false); assert.equal(select.disabled, false); assert.equal(upload.value, '');
    assert.match(uploadStatus.textContent, /Save this form/);
    assert.notEqual(form.fire('submit').defaultPrevented, true);
    assert.equal(textInput.value, 'Unsaved bilingual content');
});

test('failed inline upload preserves selection and text, allows retry and explains session and size failures', async () => {
    for (const [response, message] of [
        [{ status: 422, ok: false, json: async () => ({ errors: { image: ['Choose a valid raster image.'] } }) }, /valid raster/],
        [{ status: 419 }, /session expired/],
        [{ status: 413 }, /too large/],
        [{ status: 201, ok: true, json: async () => ({ id: 5, title: 'bad', alt: '', url: 'javascript:alert(1)' }) }, /could not be read/],
    ]) {
        const { window,popup,browse,select,upload,uploadStatus,textInput,form } = mediaSetup({ fetch: async () => response });
        browse.fire('click', { currentTarget: browse });
        window.fire('message', { origin: window.location.origin, source: popup, data: { type:'doctor-portfolio:media', id:9, title:'Current', alt:'', url:'https://example.test/current.png' } });
        upload.files = [{ name: 'bad.png' }]; upload.fire('change');
        await new Promise(setImmediate);
        assert.equal(select.value, '9'); assert.equal(upload.disabled, false);
        assert.match(uploadStatus.textContent, message);
        assert.equal(textInput.value, 'Unsaved bilingual content');
        assert.notEqual(form.fire('submit').defaultPrevented, true);
    }
});

test('unavailable image preview gives a replacement hint and clears it for a new selection', () => {
    const { preview,previewError,select } = mediaSetup();
    preview.fire('error'); assert.equal(preview.hidden, true); assert.equal(previewError.hidden, false);
    select.fire('change'); assert.equal(previewError.hidden, true);
});

test('network failure allows uploading the same file again without losing editor text', async () => {
    let attempt = 0;
    const { upload,uploadStatus,select,textInput,requests } = mediaSetup({ fetch: async () => {
        if (++attempt === 1) throw new TypeError('Failed to fetch');
        return { status:201, ok:true, json:async () => ({ id:23, title:'Retried image', alt:'', url:'https://example.test/uploads/retried.png' }) };
    } });
    upload.files = [{ name:'same.png' }]; upload.fire('change');
    await new Promise(setImmediate);
    assert.equal(select.value, ''); assert.equal(upload.value, '');
    assert.match(uploadStatus.textContent, /connection/);
    upload.fire('change'); await new Promise(setImmediate);
    assert.equal(select.value, '23'); assert.equal(requests.length, 2);
    assert.equal(textInput.value, 'Unsaved bilingual content');
});
