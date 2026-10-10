# Implementation status

Updated 2026-10-10 (Europe/Berlin). Current stage: **Step 1 complete — source audit, persistent rules and architecture documentation.** Steps 2–15 are not started. This ordered breakdown is defined here because no previous build checklist existed. Each future step must be implemented and verified before advancing; update this file and relevant matrix rows without repeating the entire audit.

## Ordered checklist: 15 build steps

1. [x] **Inspect and document the original site.** Read existing instructions/directory, all eight HTML shells, entire JS/CSS and generated templates/data. Create AGENTS.md and the four required docs; reconcile all 81 locale keys, four articles, eight gallery entries, five service blocks, four care cards, literal copy, assets and interactions. Preserve source. Verification below passed.
2. [ ] **Scaffold Laravel 12 in the project root.** Preserve frontend/docs/AGENTS; verify PHP 8.2 extensions and Composer, create minimal framework without a starter kit, establish `.env.example` and dedicated XAMPP MySQL configuration; file sessions/cache; `php artisan serve`. Check boot/route response and dependency manifests for forbidden frameworks. Do not change global services or unrelated DBs.
3. [ ] **Implement migrations, models, typed registry and localization.** Canonical shared settings; fields/navigation/care cards/services/categories/posts/gallery/media/inquiries/admin user; safe import identities/ledger; common `{en,bn}` accessor/format rules; Laravel 12 middleware. Verify schema on dedicated DB, constraints and fallback/validation behavior, non-destructive migration path.
4. [ ] **Prepare local demo media and explicitly initialize bilingual data.** Snapshot audited source into initialization-only data; bounded acquisition of source images and report unavailable files; import full originals, UI literals and intentional inquiry copy. Display imported values as public modules are introduced without an empty-content phase. Verify all inventory counts, locale/paragraph fidelity and a repeat import after an edit/hide/unpublish/delete preserving owner decisions. Never create runtime source/seed fallback or overwrite admin credentials.
5. [ ] **Implement shared public Blade layout and language handling.** Preserve CSS/DOM topbar/navigation/footer/CTA/metadata; database-fed canonical settings; GET locale switch; vanilla mobile menu. Keep admin CSS separate. Verify both locales, fallback and usable server-rendered/no-JS navigation; no original app.js loaded.
6. [ ] **Implement the homepage.** Hero/canonical facts, four care cards, doctor split, first three published posts, first three visible gallery entries and CTA, all DB-fed. Verify content/order/targets and original responsive design.
7. [ ] **Implement about, services and diagnostics.** Reuse hero/shared components; five distinct service details; canonical clinic/hours and caveats; no fabricated tests/credentials. Verify both locales and correct card-versus-services distinction.
8. [ ] **Implement blog and four article detail routes.** Database categories, filters and published posts; original full bilingual bodies and topic eyebrows; editable metadata; known-slug compatibility redirects, draft/unknown 404. Verify filtering, all four articles, hidden publication scope and article fallback correction.
9. [ ] **Implement gallery and lightbox.** Eight DB-backed images, categories/captions/alt; vanilla filter/lightbox using server DOM data. Preserve layout and add accessible keyboard/focus close behavior. Verify all items/category counts and mobile/keyboard interactions.
10. [ ] **Implement Appointment and General inquiries in existing contact card.** Localized small selector; appointment-only consultation type; normal POST/CSRF/validation/MySQL save/redirect/flash. Truthful privacy/CTA/request wording. Verify each valid mode saves, invalid input saves nothing, general strips topic, failure shows no success, and no clipboard/external launch occurs. Mode/language/filter/login forms create no Inquiry.
11. [ ] **Implement private admin login and layout.** `/admin` entry, explicit owner account provisioning, session auth/admin authorization, CSRF/throttle/logout, separate compiled Tailwind and vanilla JS. Verify unauthenticated/non-admin denial, login/logout session behavior and absence of registration/third-party/framework dependencies.
12. [ ] **Implement shared settings, fixed page fields and navigation editors.** Structured English / বাংলা controls, registry validation, media slots/links/metadata/format fields, safe visibility/order. Verify edits immediately affect relevant public pages and shared identity/address/hours are not duplicated; prohibit arbitrary JSON/HTML/layout editing.
13. [ ] **Implement care-card, service and category admin editors.** Independent collections, bilingual fields/icons/targets/order/visibility; category relationships. Verify homepage cards and service detail blocks can be edited independently, safe icon choices, and referenced category protection.
14. [ ] **Implement posts, gallery, media and private inquiry management.** Full bilingual text/metadata/publication, local validated uploads/references/caveats, inquiry list/filter/details/new-read-handled status and notes. Verify public visibility scopes, safe upload/replace/reference rules, both inquiry types privately visible, and reimport respects edits/publication/removals.
15. [ ] **Run final integrated verification and prepare cPanel delivery.** Focused automated tests plus public/admin desktop/mobile visual checks in both locales; all field rows accounted for; no source-file reads, JS content arrays or admin CSS public leakage; request/save/admin flow. Verify relevant production extensions, public-only document root, uploads/symlink alternative, secure env/file permissions and compiled assets. Write deployment/backup/non-destructive migration instructions; report actual host limitations. Do not publish or touch production infrastructure without authorization.

## Completed work and decisions

- Source found directly at `frontend/`, not nested demo3. Initial root had only frontend; no local/ancestor project instructions or existing Laravel modules to reuse. Eight page types use shared HTML heads and generated JS templates. All page functions, helpers, content arrays, handlers and CSS were read once in full UTF-8.
- Created AGENTS.md containing all owner rules, inquiry addition and incremental docs workflow. No sub-agents used.
- FRONTEND_AUDIT records sections/functions/line references, the full 81 en/bn pairs, all original article text and slugs, five bilingual service details, eight gallery captions/categories, four care cards, UI literals, CSS/responsive contracts, URLs, behavioral differences and SHA-256 source manifest.
- ARCHITECTURE defines minimal relational ownership, typed registry separate from DB values, shared localization/fallback, public/admin routes, local uploads, explicit safe initialization, auth/security, request workflow and cPanel deployment approach. Laravel 12 version-specific deployment/middleware/filesystem guidance was checked against official docs linked there; PHP 8.2 is the requested baseline.
- CONTENT_MATRIX has **347 data rows**, including an individual row for each of the 81 dictionary fields, per-article paragraphs/metadata, per-gallery fields, separate card/service fields, all named media text fields, nav labels/targets, extra headings/alt/accessible copy, metadata/topbar/footer/copyright/links/form copy, planned new fields and technical/code-owned boundaries. All database/admin/rendering statuses remain planned; this step creates documentation, not CMS implementation.
- Single settings record owns shared identity/clinic/address/hours. Canonical fact formats reference those fields. Fixed layouts are code-owned; collections relational; no arbitrary JSON editor or visual page builder.
- Contact will retain its card/style, with Appointment / General selector and no new appointment page. Consultation type only for Appointment. Submit saves to the server before success via PRG. Request does not confirm booking; private admin correspondence states do not represent bookings. Standalone Facebook/map links remain separate.
- Four homepage care cards and five services-page blocks are separate collections. Diagnostic card is not a sixth service. Article `date` values are topic labels, not dates; do not invent publication dates.
- Demo unknown article id currently falls back to first article; planned public detail route returns 404 for unknown/unpublished posts. Source locale redraw discards UI/input state; Laravel locale uses request/session/GET. Language/filter/mode/login forms never save inquiries.
- Demo seeding is explicit insert-if-missing with stable identity/archival or ledger protection against resurrecting removed content; never overwrite translations, owner edits, visibility/publication or passwords.

## Exact verification results

| Check | Method / result |
| --- | --- |
| Initial instructions and project inventory | Get-ChildItem/rg --files plus ancestor AGENTS existence/read checks: only frontend at root; exactly 8 HTML + assets/app.js + assets/style.css; no nested demo3 or prior instruction file found. |
| Full source read | UTF-8 Get-Content for every HTML file, all app.js L1–L23, all style.css L1–L2; every renderer/event handler inspected. No browser renderer executed. |
| Locale inventory | Data-only declaration extraction: en **81**, bn **81**, ordered key lists equal; every key mapped individually in CONTENT_MATRIX. PASS. |
| Page inventory | Body types about/article/blog/contact/diagnostics/gallery/home/services exactly once. PASS. |
| Articles | **4** unique original slugs: pregnancy-care, pcos, fertility-first-visit, menopause; **2 paragraphs per article per locale**, **8 per locale / 16 bilingual paragraph values total**. Full values retained. PASS. |
| Gallery | **8** entries; facility **3**, clinical **3**, wellness **2**; homepage first **3**. PASS. |
| Services / homepage cards | **5** title/description pairs in each locale for serviceDetails; **4** care-card tuples with icons/targets/title/description keys. PASS. |
| Remote image/font inventory | **9** PHOTOS URLs (hero Facebook CDN + 8 Unsplash); **1** Google Fonts CSS import. No local image/font/favicon files. PASS inventory. |
| Remote HEAD availability | First curl attempts in sandbox: HTTP **000**, exit **35**, Windows Schannel **SEC_E_NO_CREDENTIALS** for all ten. Approved outside-sandbox read-only retry with curl -I -L --max-time 15: **HTTP 200, exit 0 for hero/doctor/consult/clinic/lab/pregnancy/care/interior/team/google_fonts**. No downloads; HEAD does not prove image identity, display/rights, or font binaries. |
| Local tools | `node --version`: **v24.18.0**; `php --version`: **PHP 8.2.12 (cli), ZTS Visual C++ 2019 x64**; Get-Command found **composer.bat**. Composer version/PHP extensions are not yet checked. |
| Required artifacts | AGENTS.md, docs/FRONTEND_AUDIT.md, docs/ARCHITECTURE.md, docs/CONTENT_MATRIX.md, docs/IMPLEMENTATION_STATUS.md all exist/nonempty. PASS. |
| Matrix coverage | **347** documented data rows; **81** dictionary rows; exact key coverage and article body/gallery presence checks pass. All are audited/planned or new/planned. |
| Source preservation | All **10** reference-file SHA-256/byte sizes match the audit manifest after documentation work. PASS. No frontend writes, downloads or source rendering. |
| Scaffold boundary | No artisan, composer.json or package.json created. No migrations/DB/service changes or package installs. PASS. |
| Verification helper | Temporary local Node helper extracted only literal declarations (no draw/page/event execution), generated exact data appendix and checked counts/hashes/artifact coverage. Removed after final verification. No permanent runtime/test dependency introduced. |

No Laravel tests, server boot, browser visual checks or database connection tests were run: Laravel has not been scaffolded and those checks belong to later steps. Documentation/source assertions and read-only URL checks are the verification for this step.

## Missing assets, infrastructure and blockers

No blocker to Step 1 completion. The source matches every requested audit count. The following limitations are recorded accurately for later work:

- All photos/fonts are remote, with no bundled bytes. Every source URL responded HTTP 200 to HEAD, so none is classified as broken now. The signed hero can expire; photo identity/clinic authenticity and usage rights are not verified. Missing local assets must be resolved during media/import, without fabricated substitutions.
- No favicon/logo asset or verified doctor/clinic photo set, telephone/email, tests/prices/report timings, extra credentials, author identity or actual publication dates were supplied. Preserve stock and medical notices; do not invent missing information.
- XAMPP database service/credentials, dedicated database existence, PHP extensions and Composer version are unverified, not known inaccessible. No database was contacted or modified. Future work must report real failures, not infer availability.
- cPanel access/document-root/symlink/upload permissions and production PHP extensions are unverified; the hosting strategy is proposed, not deployed.
- Only development CLI tools were observed. No dependencies installed, assets downloaded, framework scaffold or admin credentials created.

## Next step

Step 2: minimally scaffold Laravel **12.x** in this existing folder while retaining frontend/ and all docs. First read AGENTS.md and these documents, verify the required local dependencies, and proceed only within the next authorized implementation scope. Do not re-audit the full source or repeat this completed step.
