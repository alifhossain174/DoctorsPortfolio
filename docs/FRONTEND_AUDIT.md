# Original frontend audit

Audited 2026-10-10. Source: `E:/xampp8.2/htdocs/DoctorPortfolio/frontend/` directly; no `frontend/demo3/` exists. The initial project contains only this reference directory: no Laravel scaffold, Composer/npm manifest, prior project instructions, database configuration, local media, or tests. No ancestor AGENTS.md was found at E:/, E:/xampp8.2/, or E:/xampp8.2/htdocs/. The new root AGENTS.md records the owner's contract.

## Inventory and audit method

Read all eight complete UTF-8 HTML files, all 23 logical lines of `assets/app.js`, and both logical lines of `assets/style.css`. Bangla and typographic punctuation are valid UTF-8; an initial PowerShell default-encoding display was mojibake, not evidence of corrupt source. Inspected every template function and event handler, not just the empty root nodes. Data-only extraction evaluates declarations for PHOTOS/content/articles/galleries/serviceDetails; it never runs draw(), page renderers, browser handlers, or publishes the original script. This is a source and generated-template audit; no claim of a browser visual test is made.

Each HTML file has the same head, `lang="en"`, `#root`, deferred `assets/app.js`, and `assets/style.css`. Only `body[data-page]` differs. Shared initial title: `Dr. Shah Alam Talukder`. Shared description: `Dr. Shah Alam Talukder — former Associate Professor of Obstetrics and Gynecology, Sher-e-Bangla Medical College. Deepan IUI Lab, Barishal.` Theme color `#205d54`; UTF-8 charset and responsive viewport. No favicon, social metadata, image downloads, SVG/icon library, map embed, analytics, or third-party auth is bundled.

## Eight page types and every section

All pages receive nav()/footer()/lightbox through draw(). All except contact receive cta(). Article is a detail template for four articles, not four extra HTML files.

| Shell / body key | Renderer / source line | Sections in rendered order; exact content references |
| --- | --- | --- |
| index.html / home | home(), L14 | Hero `.hero-grid`: eyebrow, herotitle, herodesc; appointment → contact.html; explore → services.html. `.stats`: credential, department, place, profession. PHOTOS.hero portrait, floating BARISHAL/visit/visitText. Care section OUR AREAS OF CARE, servicesTitle/servicesDesc + four cards(). Doctor split PHOTOS.consult, doctorEyebrow/doctorTitle/doctorText, credential/department/place bullets, readmore → about.html. Journal THE JOURNAL/blogtitle, readmore → blog.html, articleCards(3). VISUAL STORIES/gallerytitle/gallerydesc, readmore → gallery.html, galleryGrid(3). Shared CTA. blogdesc is not rendered here. |
| about.html / about | about(), L15 | Page hero about/abouttitle/aboutdesc. PHOTOS.care split, qualification, brand, doctorText, three credential bullets, galleryNote. Muted grid: visitTitle/visitText; hoursTitle/hours/friday. CTA. |
| services.html / services | services(), L17; serviceDetails L16 | Hero services/servicePageTitle/servicePageDesc. Five numbered `.info-box` blocks from serviceDetails[lang], 01–05. Shared disclaimer notice. CTA. No service-detail URLs, images or booking widget. |
| diagnostics.html / diagnostics | diagnostics(), L18 | Hero diagnostics/diagtitle/diagdesc. PHOTOS.lab split, literal DEEPAN IUI LAB, visitTitle/visitText, diagtags/diagtagsdesc, diagnotice, contact link → contact.html. CTA. No verified test list, prices, report times or telephone. |
| blog.html / blog | blog(), L19 | Hero blog/blogheading/blogintro. tagtitle, filters('article'), all four articleCards(). CTA. No search, pagination, comments, author field or actual publication dates. |
| gallery.html / gallery | gallery(), L20 | Hero gallery/gallerytitle/gallerydesc. filters('gallery'), all eight galleryGrid() figures, galleryNote. CTA. |
| article.html / article | article(), L21 | Reads ?id=slug; missing or unknown id silently selects articles[0]. Hero a.date (topic label, not date), localized title/tease. Feature image with title as alt. Category tag → blog.html; two body paragraphs; disclaimer; back-to-blog button. CTA. |
| contact.html / contact | contact(), L22 | Hero contact/contacttitle/contactdesc. Left information card: DEEPAN IUI LAB, addressLabel/address, Google Maps link, hoursLabel/hours/friday, official Facebook link. Right card: formtitle, name/phone/topic/message, prepare button, privacy notice, formStatus. No CTA section. |

## Shared functions, keys and DOM contracts

| Exact function/data | Source | Responsibility |
| --- | --- | --- |
| PHOTOS | app.js L1 | Nine symbolic image keys to external URLs; no local images. |
| content.en / content.bn | L2 | 81 matching keys each; full values below. |
| articles / galleries | L3 / L4 | Four articles / eight gallery objects; full bilingual records below. |
| page / lang / t(k) / localized(obj) / photo(key,alt,cls,extra) | L5 | Page from body dataset (default home); locale from deepan-lang localStorage (unsupported value → en); t returns locale value or key, with no English fallback; localized has no fallback. photo emits lazy `<img>` with raw interpolated alt/class/extra. |
| nav() | L6 | Topbar address + hours/friday; sticky brand/subtitle; seven nav links; active class by page key; menuBtn/navLinks/langBtn. Article has no active blog nav link. |
| footer() | L7 | Brand/footertext; explore heading with about/services/blog/gallery links; contact/address/hours/friday + literal Facebook ↗ link. Copyright `© current-browser-year brand · galleryNote`. |
| cta() | L8 | DEEPAN IUI LAB eyebrow, ctatitle/ctadesc, ctabtn → contact.html. Description currently mentions Facebook even though button points to contact. |
| cards() | L9 | Four care overview links using icon + title/description dictionary keys; learn label. Fourth goes to diagnostics; first three to services. |
| articleCards(limit=99) | L10 | #articleGrid `.article-card[data-category]`, image/title/tease/date label/category/read link. First three for home, all four for blog. |
| galleryGrid(limit=99) | L11 | #galleryGrid `.gallery-item[data-category][data-caption][data-url]`, tabindex=0, role=button, caption as aria-label/alt/figcaption. First three home, all eight gallery. |
| filters(type) | L12 | `.filters[data-filter]`, `.filter[data-cat]`, first selected; gallery all/clinical/wellness/facility; articles all/clinical/wellness. |
| hero(eyebrow,title,desc) | L13 | `.pagehero` with eyebrow, h1, paragraph. |
| serviceDetails.en / .bn | L16 | Five ordered localized title/body pairs distinct from homepage cards. |
| pages / draw() | L23 | Whole root replacement with shared layout; html lang and dynamic title; listeners reattached each redraw; adds #lightbox/#closeLightbox/#lbImage/#lbCaption on every page. |

Public Blade must preserve these CSS classes, section order, and useful interaction IDs/data attributes. Fixed DOM layout and typography remain code-owned. Content values migrate to database owners documented in CONTENT_MATRIX; no original content-array renderer in production.

## Exact interactive behavior

- Language: langBtn flips en/bn, persists `deepan-lang`, calls draw(), changes document language and title (`localized page label | brand`; article uses blog). Every redraw discards form entries, chosen filters, menu/lightbox state. Description metadata stays English. No server locale, URL locale, fetch or translation service.
- Mobile navigation: menuBtn toggles `.links.open`; CSS reveals at <=850px. No aria-expanded, focus handling or Escape handler for the menu; language control is inside the collapsed links.
- Filters: select a category button, remove other selected states, toggle `.hidden` on matching article/gallery cards by data-category. Client-only filtering of rendered arrays; no search, pagination, backend query or URL state.
- Lightbox: gallery figure click or Enter copies data-url and data-caption, opens `.gallery-lightbox.open`. Close button, backdrop click and Escape close it. No previous/next, Space activation, focus trap/restore, body scroll lock, or dynamic alt (lbImage alt remains empty). The dialog and Close aria-labels stay English. Preserve appearance while making keyboard/focus behavior accessible later.
- Links: normal static HTML navigation. Facebook and Google Maps open new tabs with noopener; ordinary CTA contact links do not. Only standalone social links may retain this external behavior in the Laravel site.
- Contact submission: only patientName is required, max 80; phoneNumber optional max 40; topic has four localized card-title options and defaults to obstetrics; message optional max 500. Inputs have IDs but no names, action or method. Browser required/maxlength checks are the only validation. onsubmit prevents default, builds an English-labeled bilingual-header message; source contains literal `\\n` escapes inside the template, so separators are backslash-n text rather than real newline characters. Attempts navigator.clipboard.writeText; success shows localized Copied text; failure displays the complete message. Then always window.open()s the official Facebook page. It does not automatically send a Facebook message, create a record, submit an HTTP request, or confirm an appointment. Pop-up/clipboard permissions may affect it; these were not exercised.

## Intentional inquiry addition and copy changes

Retain contact's existing `.info-box`/`.form` card. Add a small localized Appointment / General contact selector; consultation type is visible and validated only for Appointment. Appointment CTA targets `/contact?mode=appointment`; standalone navigation Contact allows either mode. No new page or booking engine. POST to Laravel with CSRF, validate, store in MySQL, redirect to contact, and display success only after persistence. Admin privately reviews both types. General contact is a new function, not present in the demo.

Replace the old misleading server/privacy/Facebook submission copy and booking implication with editable bilingual defaults: "Send inquiry", "Request an appointment", "Send an appointment request or general message", and a privacy note explaining private server storage, avoidance of unnecessary sensitive details, and no confirmed booking. Exact proposed English/Bangla defaults are in ARCHITECTURE. Old copy is preserved in this audit for provenance, not used as a runtime fallback. All original medical and stock-image caveats remain.

## CSS/design audit

CSS L1 imports DM Sans 400/500/600/700, Manrope 400/500/600/700/800 and Noto Sans Bengali 400/500/600/700 via Google Fonts with display=swap. L2 contains the entire design: jade #205d54, ink #172a2a, muted #61716e, mint #e6f1ec, gold #b58c56, paper #faf9f5, white, border #e2e9e4, 22px base radius; 1190px wrap; sticky nav; rounded pill links/buttons; arched hero/split photographs; grids, cards, floating panel, tags, notices, dark footer and full-screen lightbox. Pseudo-element bullet ornament is ✦.

Breakpoints: <=1050px reduces link/hero gaps; <=850px menu/nav collapse, hero/split one column, gallery/grid3 two columns, footer two columns, CTA block; <=550px wrap 28px gutters, topbar hours hidden, nav 70px, hero photo 340px, grid2/grid3/gallery/footer one column, section 54px and pagehero 58px. Public CSS must remain isolated from admin Tailwind. Inline styling (brand subtitle, footer mark, CTA eyebrow, form heading) is layout code, not arbitrary CMS CSS.

## Additional literal strings outside the 81-key dictionary

| Literal(s) | Exact source | Ownership / treatment |
| --- | --- | --- |
| BARISHAL; OUR AREAS OF CARE; THE JOURNAL; VISUAL STORIES | home() L14 | Localized named fields home.location_eyebrow, home.care_eyebrow, home.journal_eyebrow, home.gallery_eyebrow. |
| DEEPAN IUI LAB | cta() L8; diagnostics() L18; contact() L22 | Single localized clinic display name from settings, reused everywhere. |
| Facebook ↗ | footer() L7 | Localized footer social label; official target shared with contact, not duplicated. |
| বাংলা / English | nav() L6 | Editable localized target-language button labels, with fixed locale codes. |
| Open navigation; Gallery image preview; Close | nav() L6; draw() L23 | Localized accessible UI labels. |
| Illustrative healthcare portrait — not the actual doctor; Healthcare consultation illustration; Illustrative consultation photo; Illustrative lab stock photograph | home() L14; about() L15; diagnostics() L18 | Localized slot alt text; preserve illustrative caveats. Hero source is Facebook CDN, so actual identity is not verified by this audit. |
| PREGNANCY; GYNECOLOGY; FERTILITY; WOMEN’S HEALTH | articles[].date L3 | Localized post eyebrow/topic labels; these are not dates. |
| Appointment inquiry / অ্যাপয়েন্টমেন্ট অনুরোধ; Name:; Phone:; Topic:; Message: | submit handler L23 | Clipboard-only formatting removed from production; form labels and new notices are DB-managed. |
| Copied! Paste it into a Facebook message. / কপি হয়েছে! ফেসবুক মেসেজে পেস্ট করুন। | submit handler L23 | Historical success copy replaced with truthful stored-inquiry success copy. |
| Four article titles/teases and sixteen total paragraph values; eight gallery captions; five service titles/descriptions in each language | L3, L4, L16 | Full exact values below; relational collections, not omitted dictionary keys. |
| Initial title/description; page-title format `${page label} | ${brand}` | all HTML; draw() L23 | Per-page localized metadata registry; shared brand and allowlisted format placeholders. |
| © YEAR BRAND · STOCK-NOTE; 01–05; #; ✳; ☰; 📍; 🕘; 🤰; ✿; ♡; 🔬; →; ↗; ←; ×; ✦ | footer(), services(), tags, shared helpers, CSS | Copyright localized format; care-card and shared branding icons selectable from safe code allowlist; numbering is derived from sort order; visual control glyphs/layout punctuation remain code. No arbitrary HTML/SVG injection. |
| Static link targets | nav/footer/cards/home/article/contact functions | Editable navigation/card/CTA targets constrained to known public routes; map/Facebook targets validated external URLs in shared settings. Original .html paths can receive compatibility redirects. |

Unused dictionary entries are still content: `visitTitle` is used, but `blogdesc` is never called; `diatitle` is used for card/form options, not the services detail grid. All 81 keys are retained in the migration matrix, including blogdesc as reserved editable copy.

## External assets and missing local files

There are exactly ten bundled files (eight HTML, one JS, one CSS), and zero bundled photos/fonts/favicon. Nine image URLs: one signed Facebook CDN hero and eight Unsplash stock URLs, reused across slots/posts/gallery. Font CSS calls fonts.googleapis.com and font binaries depend on fonts.gstatic.com. Google Maps and Facebook are outbound links, not loaded widgets. No external JavaScript library/CDN exists.

Full image URLs and their uses appear below. Remote availability checks are documented in IMPLEMENTATION_STATUS; availability does not verify identity or usage rights. Signed CDN URLs may expire. Approved local media should be acquired during the media/import step; do not silently substitute another doctor, invent images of this clinic, or remove stock notices. No verified facility photographs, logo file, phone/email contact, test catalogue/pricing, publication dates, or additional credentials were supplied. These are absent source content, not fabricated import values.

Read-only HEAD checks on 2026-10-10 returned HTTP 200 for PHOTOS.hero, doctor, consult, clinic, lab, pregnancy, care, interior, team and the exact Google Fonts CSS import. The first sandboxed attempts failed with curl exit 35 / Windows Schannel SEC_E_NO_CREDENTIALS and HTTP 000; approved outside-sandbox retry succeeded for all ten URLs. No bytes were downloaded and no image identity, display, font-binary availability or licensing verification is implied by HEAD status. There is no verified broken URL at this step; the missing assets are local bundled files.


## All 81 exact bilingual dictionary keys

| Key (content.en/bn, L2) | English original | বাংলা original |
| --- | --- | --- |
| home | Home | হোম |
| about | About | ডাক্তার পরিচিতি |
| services | Services | সেবাসমূহ |
| diagnostics | Diagnostic Center | ডায়াগনস্টিক সেন্টার |
| blog | Blog | ব্লগ |
| gallery | Gallery | গ্যালারি |
| contact | Contact | যোগাযোগ |
| appointment | Book an Appointment | অ্যাপয়েন্টমেন্ট নিন |
| brand | Dr. Shah Alam Talukder | ডাঃ শাহ্ আলম তালুকদার |
| subtitle | Women’s Health · Fertility · IUI | নারীস্বাস্থ্য · বন্ধ্যাত্ব · আইইউআই |
| eyebrow | COMPASSIONATE WOMEN’S HEALTHCARE | যত্নশীল নারীস্বাস্থ্য সেবা |
| herotitle | Care that listens. Expertise that guides. | মনোযোগ দিয়ে শোনা, অভিজ্ঞতার সঙ্গে পরামর্শ। |
| herodesc | Consultations for obstetrics, gynecology, infertility and IUI-related care with Dr. Shah Alam Talukder, former Associate Professor, Department of Obstetrics & Gynecology, Sher-e-Bangla Medical College. | ডাঃ শাহ্ আলম তালুকদারের প্রসূতি ও স্ত্রীরোগ, বন্ধ্যাত্ব এবং আইইউআই-সংক্রান্ত পরামর্শ। তিনি শের-ই-বাংলা মেডিকেল কলেজের প্রসূতি ও স্ত্রীরোগ বিভাগের সাবেক সহযোগী অধ্যাপক। |
| credential | Former Associate Professor | সাবেক সহযোগী অধ্যাপক |
| department | Obstetrics & Gynecology | প্রসূতি ও স্ত্রীরোগ বিভাগ |
| place | Sher-e-Bangla Medical College | শের-ই-বাংলা মেডিকেল কলেজ |
| visit | Visit Deepan IUI Lab | দীপন আইইউআই ল্যাবে আসুন |
| explore | Explore Services | সেবাসমূহ দেখুন |
| servicesTitle | Thoughtful care at every stage | জীবনের প্রতিটি পর্যায়ে সচেতন স্বাস্থ্যসেবা |
| servicesDesc | Learn about common women’s health concerns and fertility treatment pathways. Specific procedures and availability should be confirmed with the chamber. | নারীস্বাস্থ্য ও বন্ধ্যাত্ব চিকিৎসার বিষয়গুলো সম্পর্কে জানুন। নির্দিষ্ট সেবার প্রাপ্যতা চেম্বারে নিশ্চিত করুন। |
| obtitle | Obstetrics & Pregnancy Care | প্রসূতি ও গর্ভকালীন যত্ন |
| obdesc | Preconception counseling, antenatal follow-up, pregnancy concerns and postnatal guidance. | গর্ভধারণের পূর্বপরামর্শ, গর্ভকালীন ফলোআপ, সমস্যা ও প্রসব-পরবর্তী নির্দেশনা। |
| gytitle | Gynecology Consultation | স্ত্রীরোগ পরামর্শ |
| gydesc | Menstrual health, PCOS, pelvic pain, fibroids, ovarian cysts and menopausal concerns. | মাসিকের সমস্যা, পিসিওএস, তলপেটের ব্যথা, ফাইব্রয়েড, ওভারিয়ান সিস্ট ও মেনোপজ। |
| iuititle | Infertility & IUI Guidance | বন্ধ্যাত্ব ও আইইউআই পরামর্শ |
| iuidesc | Fertility assessment, counseling and explanations of IUI treatment pathways. | ফার্টিলিটি মূল্যায়ন, পরামর্শ এবং আইইউআই চিকিৎসা সম্পর্কে ধারণা। |
| diatitle | Diagnostic Services | ডায়াগনস্টিক সেবা |
| diadesc | Contact Deepan IUI Lab for confirmed testing, preparation and report information. | পরীক্ষা, প্রস্তুতি ও রিপোর্টের তথ্য জানতে দীপন আইইউআই ল্যাবে যোগাযোগ করুন। |
| learn | Learn More → | বিস্তারিত জানুন → |
| doctorEyebrow | MEET YOUR DOCTOR | চিকিৎসক পরিচিতি |
| doctorTitle | Dedicated to women’s wellbeing | নারীস্বাস্থ্য সেবায় নিবেদিত |
| doctorText | Dr. Shah Alam Talukder is a former Associate Professor in the Department of Obstetrics & Gynecology at Sher-e-Bangla Medical College. His areas of focus include women’s reproductive health, infertility and IUI. | ডাঃ শাহ্ আলম তালুকদার শের-ই-বাংলা মেডিকেল কলেজের প্রসূতি ও স্ত্রীরোগ বিভাগের সাবেক সহযোগী অধ্যাপক। নারী প্রজননস্বাস্থ্য, বন্ধ্যাত্ব ও আইইউআই তাঁর বিশেষ আগ্রহের ক্ষেত্র। |
| qualification | Clinical background | পেশাগত পরিচিতি |
| profession | Former Associate Professor · Obstetrics & Gynecology · Sher-e-Bangla Medical College | সাবেক সহযোগী অধ্যাপক · প্রসূতি ও স্ত্রীরোগ · শের-ই-বাংলা মেডিকেল কলেজ |
| visitTitle | At the chamber | চেম্বার |
| visitText | Deepan IUI Lab, 4S Tower, 628 East Bogura Road, Barishal. | দীপন আইইউআই ল্যাব, ৪ এস টাওয়ার, ৬২৮ পূর্ব বগুড়া রোড, বরিশাল। |
| hoursTitle | Consultation hours | রোগী দেখার সময় |
| hours | 10:00 AM – 2:00 PM and 5:00 PM – 8:00 PM | সকাল ১০টা – দুপুর ২টা এবং বিকাল ৫টা – রাত ৮টা |
| friday | Friday: By appointment | শুক্রবার: অ্যাপয়েন্টমেন্টের মাধ্যমে |
| blogtitle | Health journal | স্বাস্থ্য ব্লগ |
| blogdesc | Friendly, general-education articles about reproductive and women’s health. These are not individual medical advice. | নারীস্বাস্থ্য ও প্রজননস্বাস্থ্য নিয়ে সহজ শিক্ষামূলক লেখা; ব্যক্তিগত চিকিৎসা পরামর্শ নয়। |
| gallerytitle | Our gallery | আমাদের গ্যালারি |
| gallerydesc | An editorial image gallery representing women’s healthcare and diagnostic environments. Stock photographs do not depict this doctor or actual facilities. | নারীস্বাস্থ্য ও ডায়াগনস্টিক পরিবেশের প্রতিনিধিত্বমূলক স্টক ছবি। এগুলো চিকিৎসক বা প্রতিষ্ঠানের প্রকৃত ছবি নয়। |
| all | All | সব |
| clinical | Clinical Care | চিকিৎসা |
| wellness | Women’s Health | নারীস্বাস্থ্য |
| facility | Facility & Lab | চেম্বার ও ল্যাব |
| read | Read Article | লেখাটি পড়ুন |
| ctatitle | Your wellbeing deserves thoughtful attention. | আপনার সুস্থতার জন্য যত্নশীল পরামর্শ। |
| ctadesc | For consultation inquiries, connect with the chamber through the official Facebook page. | পরামর্শের জন্য অফিসিয়াল ফেসবুক পেজের মাধ্যমে যোগাযোগ করুন। |
| ctabtn | Request an Appointment | অ্যাপয়েন্টমেন্টের অনুরোধ |
| address | 4S Tower, 628 East Bogura Road, Barishal, Bangladesh | ৪ এস টাওয়ার, ৬২৮ পূর্ব বগুড়া রোড, বরিশাল, বাংলাদেশ |
| fb | Official Facebook Page | অফিসিয়াল ফেসবুক পেজ |
| footertext | Obstetrics, gynecology, fertility counseling and IUI-focused care in Barishal. | বরিশালে প্রসূতি ও স্ত্রীরোগ, বন্ধ্যাত্ব এবং আইইউআই বিষয়ে পরামর্শ। |
| galleryNote | Illustrative stock photos — not verified images of Deepan IUI Lab or Dr. Talukder. | প্রতিনিধিত্বমূলক স্টক ছবি — দীপন আইইউআই ল্যাব বা চিকিৎসকের যাচাইকৃত ছবি নয়। |
| blogheading | Insights for better health | সুস্বাস্থ্যের জন্য জ্ঞান |
| blogintro | Educational reading on pregnancy, gynecological conditions, and fertility care. | গর্ভাবস্থা, স্ত্রীরোগ ও বন্ধ্যাত্ব চিকিৎসা সম্পর্কে শিক্ষামূলক তথ্য। |
| tagtitle | Browse by tag | ট্যাগ অনুযায়ী দেখুন |
| contacttitle | Visit us in Barishal | বরিশালে আমাদের চেম্বার |
| contactdesc | Find chamber information and send an appointment inquiry. | চেম্বারের তথ্য ও অ্যাপয়েন্টমেন্টের জন্য যোগাযোগ করুন। |
| formtitle | Prepare an appointment inquiry | অ্যাপয়েন্টমেন্ট বার্তা প্রস্তুত করুন |
| name | Patient name | রোগীর নাম |
| phone | Phone number | ফোন নম্বর |
| topic | Consultation type | পরামর্শের ধরন |
| message | Brief message | সংক্ষিপ্ত বার্তা |
| prepare | Copy inquiry & open Facebook | বার্তা কপি করে ফেসবুক খুলুন |
| privacy | This form does not store or send medical information to a server. It copies your message and opens Facebook; send it yourself via the doctor’s page. | এই ফর্ম কোনো সার্ভারে স্বাস্থ্যতথ্য পাঠায় না। এটি বার্তা কপি করে ফেসবুক খুলবে; আপনাকে নিজে বার্তা পাঠাতে হবে। |
| addressLabel | Chamber address | চেম্বারের ঠিকানা |
| hoursLabel | Patient visiting hours | রোগী দেখার সময় |
| openmap | Find on Google Maps | গুগল ম্যাপে খুঁজুন |
| abouttitle | Experience rooted in women’s healthcare | নারীস্বাস্থ্য সেবায় অভিজ্ঞতা |
| aboutdesc | Learn more about the doctor and the care topics supported by the chamber. | চিকিৎসক ও পরামর্শের বিষয় সম্পর্কে আরও জানুন। |
| diagtitle | Deepan IUI Lab & Diagnostic Center | দীপন আইইউআই ল্যাব ও ডায়াগনস্টিক সেন্টার |
| diagdesc | Chamber and laboratory information, with service availability confirmed directly by staff. | চেম্বার ও ল্যাবের তথ্য; সেবার প্রাপ্যতা সরাসরি নিশ্চিত করুন। |
| diagnotice | A complete, verified test menu, prices and report delivery times have not been provided. Please confirm directly with the center before visiting. | পরীক্ষার পূর্ণ তালিকা, মূল্য ও রিপোর্টের সময় নিশ্চিত তথ্য হিসেবে দেওয়া হয়নি। যাওয়ার আগে কেন্দ্রে জেনে নিন। |
| diagtags | Possible inquiry topics | যেসব বিষয়ে খোঁজ নিতে পারেন |
| diagtagsdesc | Patients may ask about availability of fertility-related laboratory services, sample collection instructions, reports and appointment scheduling. No specific test is claimed to be available here. | বন্ধ্যাত্বসংক্রান্ত পরীক্ষার প্রাপ্যতা, নমুনা দেওয়ার নিয়ম, রিপোর্ট ও অ্যাপয়েন্টমেন্ট নিয়ে জানতে পারেন। এখানে কোনো নির্দিষ্ট পরীক্ষা নিশ্চিতভাবে উপলব্ধ বলা হচ্ছে না। |
| servicePageTitle | Obstetrics, Gynecology & Fertility | প্রসূতি, স্ত্রীরোগ ও বন্ধ্যাত্ব |
| servicePageDesc | Overview of care topics and when to seek professional advice. | চিকিৎসা ও কখন পরামর্শ নেওয়া উচিত সে সম্পর্কে ধারণা। |
| disclaimer | Educational information only. A clinician must evaluate individual symptoms and treatment options. | শুধু শিক্ষামূলক তথ্য। ব্যক্তিগত উপসর্গ ও চিকিৎসা নির্ধারণে চিকিৎসকের মূল্যায়ন প্রয়োজন। |
| readmore | View details | বিস্তারিত দেখুন |

## Four original bilingual articles (app.js L3)

Each article has exactly two paragraphs in each locale. Order is source order. Image alt equals localized title. Original category tag links back to blog.html, not a filtered URL.

### pregnancy-care

Original URL: `article.html?id=pregnancy-care`; category `wellness`; PHOTOS.pregnancy; literal date/topic eyebrow `PREGNANCY`.

**English**

Title: A practical guide to antenatal care

Excerpt: Understanding routine prenatal visits, warning signs and questions to ask during pregnancy.

Antenatal care provides an opportunity to monitor the health of the pregnant person and developing baby, identify risks, and plan appropriate care. The schedule and tests are tailored to medical history and stage of pregnancy.

Discuss medications, nutrition, blood pressure, diabetes screening and any new symptoms with your clinician. Seek urgent assessment for heavy bleeding, severe abdominal pain, seizures, severe headache with visual changes or reduced fetal movements later in pregnancy.
**বাংলা**

Title: গর্ভকালীন যত্নের সহজ নির্দেশিকা

Excerpt: গর্ভাবস্থায় নিয়মিত চেকআপ, সতর্কতার লক্ষণ ও চিকিৎসককে কী জিজ্ঞেস করবেন।

গর্ভকালীন সেবায় মা ও শিশুর স্বাস্থ্য পর্যবেক্ষণ, ঝুঁকি শনাক্তকরণ এবং প্রয়োজনমতো চিকিৎসার পরিকল্পনা করা হয়। ইতিহাস ও গর্ভকাল অনুযায়ী পরীক্ষা এবং ফলোআপ ভিন্ন হতে পারে।

ওষুধ, পুষ্টি, রক্তচাপ, ডায়াবেটিস ও নতুন উপসর্গ সম্পর্কে চিকিৎসকের সঙ্গে কথা বলুন। অতিরিক্ত রক্তপাত, প্রচণ্ড পেটব্যথা, খিঁচুনি, চোখে ঝাপসা দেখা-সহ তীব্র মাথাব্যথা বা গর্ভের শিশুর নড়াচড়া কমে গেলে দ্রুত চিকিৎসা নিন।

### pcos

Original URL: `article.html?id=pcos`; category `clinical`; PHOTOS.care; literal date/topic eyebrow `GYNECOLOGY`.

**English**

Title: PCOS: symptoms, evaluation and care

Excerpt: Why irregular periods, acne and other symptoms may need a tailored evaluation.

Polycystic ovary syndrome (PCOS) can involve irregular menstrual cycles, androgen-related symptoms and metabolic health concerns. Diagnosis relies on clinical assessment and sometimes laboratory tests or imaging.

Management depends on symptoms and personal goals, including whether pregnancy is desired. Do not begin hormonal or fertility medicines without a clinician’s guidance.
**বাংলা**

Title: পিসিওএস: লক্ষণ, পরীক্ষা ও যত্ন

Excerpt: অনিয়মিত মাসিক, ব্রণসহ বিভিন্ন লক্ষণে কেন ব্যক্তিভিত্তিক পরীক্ষা দরকার।

পলিসিস্টিক ওভারি সিনড্রোমে (পিসিওএস) মাসিক অনিয়ম, হরমোনজনিত লক্ষণ ও বিপাকীয় স্বাস্থ্যঝুঁকি থাকতে পারে। রোগ নির্ণয়ে চিকিৎসা মূল্যায়ন এবং প্রয়োজনে পরীক্ষা বা আল্ট্রাসনোগ্রাম লাগে।

চিকিৎসা উপসর্গ ও ভবিষ্যৎ পরিকল্পনার ওপর নির্ভর করে। চিকিৎসকের পরামর্শ ছাড়া হরমোন বা বন্ধ্যাত্বের ওষুধ শুরু করবেন না।

### fertility-first-visit

Original URL: `article.html?id=fertility-first-visit`; category `clinical`; PHOTOS.consult; literal date/topic eyebrow `FERTILITY`.

**English**

Title: What happens at a fertility consultation?

Excerpt: How a conversation about medical history and testing can help identify the next step.

A fertility consultation often reviews menstrual history, how long pregnancy has been attempted, previous pregnancies, medications and relevant health conditions. Assessment may involve both partners when appropriate.

Options depend on the findings and may include lifestyle advice, investigations, timed intercourse, or discussion of assisted reproduction such as IUI. No treatment offers a guaranteed outcome.
**বাংলা**

Title: বন্ধ্যাত্ব পরামর্শের প্রথম সাক্ষাতে কী হয়?

Excerpt: চিকিৎসা ইতিহাস ও পরীক্ষা পরবর্তী সিদ্ধান্তে কীভাবে সাহায্য করে।

প্রথম সাক্ষাতে মাসিকের ইতিহাস, কতদিন ধরে গর্ভধারণের চেষ্টা হচ্ছে, পূর্ববর্তী গর্ভধারণ, ওষুধ ও অন্যান্য রোগ সম্পর্কে আলোচনা হতে পারে। প্রয়োজনে উভয় সঙ্গীর মূল্যায়ন গুরুত্বপূর্ণ।

ফলাফল অনুযায়ী জীবনযাত্রার পরামর্শ, পরীক্ষা, নির্দিষ্ট সময়ে গর্ভধারণের চেষ্টা অথবা আইইউআই-এর মতো চিকিৎসার আলোচনা হতে পারে। কোনো পদ্ধতিতেই সফলতার নিশ্চয়তা নেই।

### menopause

Original URL: `article.html?id=menopause`; category `wellness`; PHOTOS.doctor; literal date/topic eyebrow `WOMEN’S HEALTH`.

**English**

Title: Understanding the menopause transition

Excerpt: Common changes, symptom management and when a medical review is important.

Perimenopause can cause menstrual changes, sleep difficulties, hot flashes and mood symptoms. Other conditions can cause similar symptoms, so individual assessment matters.

Treatment options vary depending on medical history, preferences and personal risk factors. Postmenopausal bleeding should be evaluated promptly.
**বাংলা**

Title: মেনোপজের পরিবর্তনগুলো বুঝে নিন

Excerpt: সাধারণ পরিবর্তন, উপসর্গের যত্ন ও কখন চিকিৎসকের কাছে যাবেন।

মেনোপজের আগে মাসিকের পরিবর্তন, ঘুমের সমস্যা, হঠাৎ গরম লাগা ও মানসিক পরিবর্তন দেখা দিতে পারে। একই রকম লক্ষণ অন্য কারণেও হতে পারে।

চিকিৎসা ব্যক্তির স্বাস্থ্য ইতিহাস ও ঝুঁকি অনুযায়ী নির্ধারিত হয়। মেনোপজের পরে রক্তপাত হলে দ্রুত মূল্যায়ন জরুরি।

## Gallery categories and all eight items (L4)

| Order | Category code | Photo key | English caption/alt/aria-label | বাংলা caption/alt/aria-label |
| --- | --- | --- | --- | --- |
| 1 | facility | clinic | Healthcare environment | স্বাস্থ্যসেবার পরিবেশ |
| 2 | clinical | consult | Consultation and care | পরামর্শ ও চিকিৎসা |
| 3 | facility | lab | Laboratory research | ল্যাবরেটরি কার্যক্রম |
| 4 | wellness | pregnancy | Pregnancy wellbeing | গর্ভকালীন সুস্থতা |
| 5 | clinical | team | Healthcare professionals | স্বাস্থ্যকর্মীদের সেবা |
| 6 | facility | interior | Medical facilities | চিকিৎসা কেন্দ্র |
| 7 | wellness | care | Women’s health | নারীস্বাস্থ্য |
| 8 | clinical | doctor | Clinical guidance | চিকিৎসা পরামর্শ |

Category counts: facility 3, clinical 3, wellness 2. Homepage shows only items 1–3. Captions also feed lightbox text. Filters use all/clinical/wellness/facility; article filters omit facility.

## Five services-page detail blocks (L16)

| Number | English title | English full description | বাংলা title | বাংলা full description |
| --- | --- | --- | --- | --- |
| 01 | Pregnancy and antenatal care | Routine pregnancy consultations can cover nutrition, blood-pressure monitoring, prenatal testing, discomforts and planning for birth. Immediate assessment is important for heavy bleeding, severe pain, seizures or reduced fetal movement. | গর্ভকালীন ও প্রসূতি সেবা | গর্ভাবস্থার নিয়মিত পরামর্শে পুষ্টি, রক্তচাপ, পরীক্ষা, অস্বস্তি ও প্রসবের প্রস্তুতি নিয়ে আলোচনা করা যায়। অতিরিক্ত রক্তপাত, প্রচণ্ড ব্যথা, খিঁচুনি বা শিশুর নড়াচড়া কমে গেলে দ্রুত চিকিৎসা নিন। |
| 02 | Menstrual and gynecological problems | Irregular or heavy periods, painful menstruation, abnormal discharge, pelvic pain, fibroids, ovarian cysts and suspected PCOS may warrant evaluation. Tests and treatment depend on individual symptoms. | মাসিক ও স্ত্রীরোগ সমস্যা | অনিয়মিত বা অতিরিক্ত মাসিক, মাসিকের ব্যথা, অস্বাভাবিক স্রাব, তলপেটের ব্যথা, ফাইব্রয়েড, ওভারিয়ান সিস্ট ও পিসিওএস-এ মূল্যায়ন দরকার হতে পারে। পরীক্ষা ও চিকিৎসা রোগীভেদে ভিন্ন। |
| 03 | Infertility assessment and IUI | Fertility assessment can include reproductive history and investigations for one or both partners. IUI involves preparing sperm and placing it inside the uterus around ovulation when clinically appropriate; outcomes vary and it is not suitable for everyone. | বন্ধ্যাত্ব মূল্যায়ন ও আইইউআই | বন্ধ্যাত্ব মূল্যায়নে প্রজনন ইতিহাস ও প্রয়োজনে উভয় সঙ্গীর পরীক্ষা থাকতে পারে। আইইউআই-তে উপযুক্ত সময়ে প্রক্রিয়াজাত শুক্রাণু জরায়ুতে দেওয়া হয়; ফলাফল ভিন্ন হতে পারে এবং এটি সবার জন্য উপযুক্ত নয়। |
| 04 | Menopause and midlife health | Consultation can address hot flashes, sleep, vaginal symptoms, bone health, and treatment choices. Bleeding after menopause should be assessed promptly. | মেনোপজ ও মধ্যবয়সে স্বাস্থ্য | হঠাৎ গরম লাগা, ঘুমের সমস্যা, যোনির অস্বস্তি, হাড়ের স্বাস্থ্য ও চিকিৎসার বিকল্প নিয়ে আলোচনা করা যায়। মেনোপজের পরে রক্তপাত হলে দ্রুত চিকিৎসকের পরামর্শ নিন। |
| 05 | Postnatal and reproductive health | Postnatal reviews, family-planning discussions, and concerns after pregnancy are important parts of continuing care. | প্রসব-পরবর্তী ও প্রজননস্বাস্থ্য | প্রসব-পরবর্তী ফলোআপ, পরিবার পরিকল্পনা ও গর্ভাবস্থার পরের যেকোনো উদ্বেগে ধারাবাহিক পরামর্শ গুরুত্বপূর্ণ। |

## Four distinct homepage care cards (L9)

These are not the five services-page detail records. They have short dictionary descriptions, icons and destination links; diagnostics is a homepage card but not a serviceDetails block.

| Order | Icon | Title/description source keys | Original target |
| --- | --- | --- | --- |
| 1 | 🤰 | obtitle / obdesc | services.html |
| 2 | ✿ | gytitle / gydesc | services.html |
| 3 | ♡ | iuititle / iuidesc | services.html |
| 4 | 🔬 | diatitle / diadesc | diagnostics.html |

## Exact remote photo URLs (L1)

| PHOTOS key | URL | Public uses |
| --- | --- | --- |
| hero | https://scontent.fbzl7-1.fna.fbcdn.net/v/t39.30808-6/386655458_7165314940145758_7620333021630825497_n.jpg?stp=dst-jpg_tt6&cstp=mx1000x1000&ctp=s1000x1000&_nc_cat=100&_nc_map=urlgen_bucketless&ccb=1-7&_nc_sid=6ee11a&_nc_eui2=AeFk06JpC1BCOqXB7Ww4XxpSVfliVC9zBhtV-WJUL3MGGzhkPPiAClVm7pHgliW73nPbdqWxTcs2VrJ6W29L-hve&_nc_ohc=5X-VwDdwBmkQ7kNvwHDqX_o&_nc_oc=AdqY9Q9GpH-2u9D72wKp-6opk0YZ1EJ9GmR6TAcBFHMbCjqZ6qzXLwWhYGyShGfMokg&_nc_zt=23&_nc_ht=scontent.fbzl7-1.fna&_nc_gid=pGNeFVAGY4ZJbQHZGxS27w&_nc_ss=7b2a8&oh=00_AQM_1gBcuzQau2hyZHx4dKA60e8r8-HRPxkPlbfDw6hLYw&oe=6ACD61B1 | Home hero |
| doctor | https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=1000&q=85 | menopause article; gallery #8 |
| consult | https://images.unsplash.com/photo-1579684385127-1ef15d508118?w=900&q=85 | Home doctor split; fertility-first-visit article; gallery #2 |
| clinic | https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?w=1000&q=85 | gallery #1 |
| lab | https://images.unsplash.com/photo-1579154204601-01588f351e67?w=900&q=85 | Diagnostics split; gallery #3 |
| pregnancy | https://images.unsplash.com/photo-1493894473891-10fc1e5dbd22?w=900&q=85 | pregnancy-care article; gallery #4 |
| care | https://images.unsplash.com/photo-1559757148-5c350d0d3c56?w=900&q=85 | About split; pcos article; gallery #7 |
| interior | https://images.unsplash.com/photo-1551076805-e1869033e561?w=900&q=85 | gallery #6 |
| team | https://images.unsplash.com/photo-1551601651-2a8555f1a136?w=900&q=85 | gallery #5 |

Google font CSS import: `https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&family=Noto+Sans+Bengali:wght@400;500;600;700&display=swap`.

Facebook target: `https://www.facebook.com/drshahalamtalukder/`.

Map target: `https://www.google.com/maps/search/?api=1&query=4S+Tower+628+East+Bogura+Road+Barishal`.

## Source-preservation SHA-256 manifest

Baseline recorded before documentation generation; verified again after completion.

| Reference file | Bytes | SHA-256 |
| --- | --- | --- |
| frontend/about.html | 539 | 4637c0df746b706c59cc246c1bd0c8995326d4dcc20e31a6a158fc23ee1c1f3b |
| frontend/article.html | 541 | b600e51cf4194305488813c5e1bc35a87662e676a7f0fe4fc7ea67e1231d9192 |
| frontend/blog.html | 538 | 2cb92b9fd2fbd4f08a28c4f6aace11760f3ab5537f7ea8042306cc3d8c1a028a |
| frontend/contact.html | 541 | fe6680250797d74daa99aaa11b0a45a0df715ac628392b077b5e160b99aee739 |
| frontend/diagnostics.html | 545 | 5c2defe1ffe5882d2bfb022ac0104d1589aa7567a79591884296356281f1dbf3 |
| frontend/gallery.html | 541 | ea6a41734ae56e22fe802b4a979716feece0ad3473ee05a86d8caaf1fa949ebc |
| frontend/index.html | 538 | a1b6979fa77d145683e592e2188a29f0dee21ae7689919ffe8f9f633bfa9e279 |
| frontend/services.html | 542 | 2e20e35670919ed10519c28050c785b4bc4528c9b2c0a37d7e45232ab2bc5232 |
| frontend/assets/app.js | 40624 | 41c24a8f4166a8b41103d947eba897405dd8f63b49dc5d4d7725ed40bcbe1611 |
| frontend/assets/style.css | 8431 | 163874ac9dd4afe72b8b7635634b6c813c8a37d6fbdf76d61ae509c345a50f49 |
