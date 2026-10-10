# Implementation status

Updated 2026-10-10 (Europe/Berlin). **Steps 1–3 complete.** Step 3 includes the actual schema/import, typed content registry, localization and populated public Blade foundation, verified against local MySQL/MariaDB. Next: Step 4 public browser/interaction QA and compatibility; Step 5 private admin authentication and secure interactive provisioning. Original Step 1/2 evidence is preserved below. The roadmap is reconciled with the owner’s latest Step 3 scope and explicit Step 5 provisioning requirement; completed public components must be reused.

## Ordered checklist: 15 build steps

1. [x] **Inspect and document the original site.** Preserve the original complete audit, eight shells/generated templates, all 81 keys, full articles/services/cards/gallery/literals/assets and project contract.
2. [x] **Scaffold Laravel 12 at the root.** Locked PHP 8.2-compatible dependencies, isolated compiled admin Tailwind/vanilla JS, MySQL/file/sync/log configuration and verified tool/database boot. Historical exact results retained below.
3. [x] **Implement relational schema, localization and explicit initial content.** Migrations, models/casts/relations/constraints, typed field registry, 165 initial content records plus completion marker, all original bilingual content and truthful request copy. Actually migrated/seeded doctor_portfolio. Repeat import/deletion/repair safeguards and focused tests passed. Public database-backed Blade foundation implemented now to meet readable/populated acceptance. SEED_CONTENT_REPORT contains all 81 destinations and actual counts.
4. [ ] **Verify and finish public presentation/interactions.** Reuse the existing database-backed eight page templates and shared components; perform desktop/mobile browser QA in both locales, original design comparison, no-JS/mobile/filter/lightbox/contact-mode checks, empty/missing media states, failure handling and safe legacy .html compatibility redirects. Preserve original stock/medical caveats, public CSS and first-three previews. Do not re-audit source or rebuild completed modules.
5. [ ] **Implement private admin auth and secure owner provisioning.** /admin entry, plain Blade/session authentication, CSRF/throttle/logout, access-admin Gate and admin-only compiled assets. Add a separate interactive admin-creation command with securely entered credentials; no fixed password/default-user seeder, registration, patient accounts or third-party auth. Test non-admin denial and session lifecycle.
6. [ ] **Implement shared settings/doctor/clinic editors.** Structured English / বাংলা controls, canonical facts, notices, social URLs, CTA, default locale and media reference; validation and immediate public changes. Avoid duplicated profile/address/hours values.
7. [ ] **Implement fixed page/section/SEO/UI editors.** Use the registry’s named controls, typed rules, original layout positions and approved optional section flags/counts. No arbitrary JSON/HTML/template editor or visual page builder; essential notices remain visible.
8. [ ] **Implement navigation editor.** Separate header/footer controls, localized labels/targets, approved internal route keys or HTTP(S) URLs, order/visibility and server-side whitelists.
9. [ ] **Implement care-card/service/consultation-option editors.** Keep four cards, five detail blocks and four consultation choices independent. Localized fields/icons, stable identity, order/enabled/active state; appropriate card targets and inquiry snapshot retention.
10. [ ] **Implement category editor.** Stable slugs, localized labels, order and explicit blog/gallery applicability; protect referenced categories and do not add a tag subsystem.
11. [ ] **Implement post editors.** Full bilingual plain paragraph bodies, excerpts/badges/media/alt/SEO, slug/order/homepage/publication controls; preserve source editorial badges independently of published_at and enforce published public scope.
12. [ ] **Implement gallery and media editors/uploads.** Localized caption/alt/provenance/stock notes, category/order/visibility/homepage flags, validated raster bytes/size/disk/path and usage protection. Legacy external references stay until verified replacements exist; no fabricated photos or claims.
13. [ ] **Implement private inquiry management.** Appointment/contact list/filter/details/status/notes/optional confirmed visit time/handler. Preserve readable consultation snapshots and keep reference/PII out of public listings. Verify existing POST→save→PRG requests become visible privately.
14. [ ] **Complete integrated security/content verification.** Focused tests and full public/admin browser checks in both languages, all matrix rows editable/rendered as intended, visibility/publication/fallback/validation/upload/authorization boundaries, repeat initialization preserving owner edits/deletions. No queues, scheduler, Redis, public admin CSS or runtime source/seed fallback.
15. [ ] **Prepare cPanel handoff and deployment docs.** Actual host PHP/extensions/document-root/storage permissions, compiled admin artifacts and public assets, production Composer install, private env/key, database/media backup and non-destructive migration guidance. No publishing or unrelated infrastructure changes without authorization.


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

## Step 1 exact verification results (historical)

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

## Step 1 missing assets, infrastructure and blockers (historical)

No blocker to Step 1 completion. The source matches every requested audit count. The following limitations are recorded accurately for later work:

- All photos/fonts are remote, with no bundled bytes. Every source URL responded HTTP 200 to HEAD, so none is classified as broken now. The signed hero can expire; photo identity/clinic authenticity and usage rights are not verified. Missing local assets must be resolved during media/import, without fabricated substitutions.
- No favicon/logo asset or verified doctor/clinic photo set, telephone/email, tests/prices/report timings, extra credentials, author identity or actual publication dates were supplied. Preserve stock and medical notices; do not invent missing information.
- XAMPP database service/credentials, dedicated database existence, PHP extensions and Composer version are unverified, not known inaccessible. No database was contacted or modified. Future work must report real failures, not infer availability.
- cPanel access/document-root/symlink/upload permissions and production PHP extensions are unverified; the hosting strategy is proposed, not deployed.
- Only development CLI tools were observed. No dependencies installed, assets downloaded, framework scaffold or admin credentials created.

## Step 2 implementation and decisions

- Read AGENTS.md and all four Step 1 docs. Preserved the audit, matrix, architecture, project rules, and all original frontend bytes; only this status document is intentionally updated. Root scaffold did not already exist. Existing Git repository has the Step 1 baseline commit.
- Downloaded laravel/laravel **v12.12.2** into `.bootstrap-laravel/skeleton` using `create-project ... '12.*' --no-install --no-scripts`. No create-project into the nonempty root, source renaming/deletion, auto SQLite creation, auto key display, or migration hooks. A whole-file collision pass found **55 files, 0 collisions**; copied with overwrite=false, including .editorconfig/.env.example/.gitattributes/.gitignore and nested dotfiles.
- Removed skeleton Sail/Pail, default queue/concurrent dev/setup/automatic-migration hooks, Axios, general app CSS/JS and the Tailwind-heavy welcome page. No starter kit or client framework. Composer ^12.0 excludes Laravel 13; config.platform.php=8.2.12 constrains all resolved packages without ignore-platform-reqs. Lockfiles record exact dependencies.
- Minimal root Blade boot view has no stylesheet/script; `/up` uses Laravel's health route. Private admin layout is an unrouted reusable scaffold, not an exposed admin screen. Auth/private entry are Step 11; public CMS layout is Step 5. Content matrix remains planned; no managed website content is represented as implemented.
- `resources/css/admin.css` imports compiled Tailwind with automatic scanning disabled; explicit sources include only admin templates and admin.js. Two separate Vite inputs feed `public/build/manifest.json` and hashed CSS/JS. Only the admin layout uses @vite. Plain Vite + @tailwindcss/vite, **no Laravel-specific/client-framework Vite plugin**. `publicDir=false` prevents public/index.php or other backend files being copied into build output. No CDN dependencies.
- `npm run dev` uses a small local asset-only runner to publish/remove the standard public/hot URL for Laravel @vite. Build and dev integration verified; no production Node service. Temporary verification servers stopped and hot file removed. Ctrl+C from the automation terminal did not terminate the Vite runner; process identity was verified and only that task PID was stopped with Windows taskkill after Stop-Process failed. No global service was changed.
- `.env.example` holds the requested MySQL/timezone/file/sync/log defaults with credential/key placeholders. Verified local login is stored only in ignored `.env`; key generated once without printing its value. `config/app.php` resolves APP_TIMEZONE, and config defaults now match MySQL/file sessions/file cache/sync queues. No global PHP configuration/installation changed.
- Read-only `app:check-database` verifies the selected MySQL/MariaDB schema and reports server version; failures reveal no raw exception, password or key, and provide the precise connection retry commands. The existing dedicated doctor_portfolio database is empty and reachable; no need to create a DB or fall back to SQLite.
- Removed unneeded default cache/jobs/sessions migration definitions for file/sync drivers and disabled the default test-user seeder. Remaining skeleton user/reset-token migration is **unapplied** and must be reviewed/adapted for the admin model in Step 3. No owner account or known-password account created. PHPUnit DB settings point to a separate doctor_portfolio_test MySQL schema, not SQLite or development tables; that test schema has not been created.
- README provides actual Windows PHP/Composer paths, XAMPP MariaDB setup, serve/build/diagnostic commands, real directory structure and cPanel requirements. Ignore rules cover .env variants, private/runtime storage, uploads, bootstrap temp/cache, local generated artifacts, dependencies and public/build/hot. .env.example and both lockfiles are not ignored. Required deployment artifacts are public/build/manifest.json and every referenced hashed asset, even though generated files are ignored.

## Step 2 exact verification results

| Check / command | Result |
| --- | --- |
| PHP resolution / php -v / php --ini | **PHP 8.2.12**, E:/xampp8.2/php/php.exe; loaded ini E:/xampp8.2/php/php.ini. PATH also lists E:/xampp7.4/php/php.exe second. No path/global config change. |
| Composer resolution / composer --version | **Composer 2.10.2**; C:/ProgramData/ComposerSetup/bin/composer.bat calls PATH php with composer.phar. Reports **PHP 8.2.12 at the same XAMPP path**. Installs/checks explicitly invoked that binary. |
| Artisan PHP | Explicit E:/xampp8.2/php/php.exe invocations; bootstrapped probe PHP_BINARY confirms that same executable. |
| PHP extensions | pdo_mysql, mbstring, openssl, fileinfo, dom, xml, ctype, curl, filter, hash, pcre, PDO, session, tokenizer all **loaded**; php -m also confirms libxml/xmlreader/xmlwriter/SimpleXML, zip and GD. |
| Node / npm | **Node v24.18.0**, C:/Program Files/nodejs/node.exe; **npm 11.16.0**. No global upgrade. |
| Composer install / lock / audit | **108 packages**, Laravel **12.69.3**, Tinker **2.11.1**, PHPUnit **11.5.57**; dependency install and package discovery exit 0; **no vulnerability advisories**. PHP platform fixed to 8.2.12. |
| composer validate --strict | **Exit 0: composer.json is valid**; lock content agrees. |
| composer check-platform-reqs | **Exit 0: every required platform check succeeds** on real PHP 8.2.12. No ignored requirements. |
| php artisan about | **Exit 0**; DoctorPortfolio, Laravel **12.69.3**, PHP **8.2.12**, Composer **2.10.2**, local environment, **Asia/Dhaka**; mysql DB, file cache/session, sync queue, log mail/broadcast; no storage link yet (media step). |
| MySQL server / selected schema | XAMPP mysql.exe and Laravel read-only SELECT DATABASE(), VERSION(): **doctor_portfolio**, **10.4.32-MariaDB**, 127.0.0.1:3306. Default mysql PDO driver works with this MariaDB server. |
| php artisan app:check-database | **Exit 0**, selected dedicated DB and exact server version reported. No schema mutations. |
| No migrations / DB preservation | Dedicated schema table count **0 before and after scaffold/HTTP verification**. No migrate, db:seed, reset, truncate or unrelated database commands. |
| npm install / pins | **32 packages added / 33 audited / 0 vulnerabilities**; exact Vite **7.3.7**, Tailwind and @tailwindcss/vite **4.3.3**. npm reported esbuild postinstall pending under its script policy; no additional script approval needed for successful build/dev checks with installed binaries. |
| npm run build (final) | **Exit 0, 117ms**; two transformed modules; manifest **349 bytes**, assets/admin-BPT_co5T.css **4297 bytes**, assets/admin-C-qhbeH0.js **555 bytes**. Initial publicDir warning corrected; final build has no warning or copied PHP/backend files. |
| Admin built assets / @vite | Bootstrapped admin Blade render resolves both hashed manifest entries successfully without a Laravel-specific Vite plugin. No admin route added. |
| npm run dev / @vite development | Vite started at **127.0.0.1:5173**; GET /@vite/client, /resources/css/admin.css and /resources/js/admin.js each **200**; admin Blade resolves all three URLs through public/hot. Server stopped and hot file removed after verification. |
| Artisan server HTTP boot | `php artisan serve --host=127.0.0.1 --port=8000 --no-reload` starts successfully. GET **/ = 200**, **/up = 200**. Root contains Application ready and **no admin build assets, Tailwind or original app.js**. Verification server stopped. |
| Public/private boundaries | GET **/admin = 404** (not implemented), **/frontend/assets/app.js = 404**, **/.env = 404** under Artisan public root. |
| Forbidden dependencies | Targeted composer/package/lock/resources/Vite search returns **no React, Vue, Alpine, Livewire, Inertia, Svelte, Angular, Next, Axios, concurrently, Sail, Pail or laravel-vite-plugin entries**. No scheduled task/worker starts configured. |
| Ignore rules | Git confirms .env, temp bootstrap, local artifacts/cache, public/build, uploads, private/public runtime media, sessions and bootstrap generated cache ignored; .env.example and both lockfiles remain eligible for tracking. |
| Preservation | **14 frontend/Step-1-doc hashes unchanged** before intentional status update; final frontend + audit/architecture/matrix remain identical to baseline. AGENTS unchanged. Temporary bootstrap scaffold removed after verification. |

## Current blockers and next step

**No Step 2 blocker.** Framework/dependency builds and the real local database connection succeed. No local database credentials or key were printed or committed. No database schema/content/admin account has been initialized yet. Production cPanel capabilities and approved local media remain pending their later steps; these are not local scaffold blockers.

Next: **Step 3**, inspect/adapt the unapplied user migration, implement the agreed content/inquiry/admin schema, typed registry and common localization helper, and verify non-destructive migrations on this dedicated database. Read the project docs first; do not repeat the frontend audit. Do not start Step 3 until authorized.


## Step 3 implemented work and exact verification

- Read AGENTS.md and all Step 1 docs, including CONTENT_MATRIX before coding; inspected the existing scaffold and original literal/generated templates without repeating the complete source audit. No delegation used.
- Added the non-destructive website migration, twelve CMS models plus private Inquiry and User admin authorization, explicit casts/relations, unique stable keys/slugs/reference, foreign keys, indexed status/publication/order/inquiry time and portable string statuses.
- Code-owned registry: config/cms_models.php, config/content_fields.php, config/page_sections.php, config/private_content_fields.php and ContentRegistry. Localized strings/paragraph bodies use one LocalizedContent service; unknown fields/keys, missing English, unsafe targets, unapproved icons/positions/statuses rejected. Profile/address/hours are referenced via shared settings/allowlisted plain-text formats.
- InitialWebsiteContentSeeder is called by DatabaseSeeder without user credentials or fake requests. Pure UTF-8 snapshot under database/seeders/data includes source provenance/full bilingual values/mapping/import records. InitialWebsiteImporter uses a transaction, stable identities and locked completion marker; normal completed seeding skips entirely. Explicit website:repair-initial-content restores missing originals only and validates the original snapshot checksum.
- Actual data: 1 settings record, 8 pages, 88 fields, 20 sections, 11 navigation items, 4 cards, 5 services, 4 independent consultation options, 3 categories, 4 published posts, 8 visible gallery entries, 9 external media assets. **165 content records + 1 marker; users 0; inquiries 0.** Every original section/navigation visible; source first-three previews preserved. Published_at is initialization time only; editorial badges are rendered instead.
- Populated public foundation: seven main URLs + four article detail routes, reusable Blade parts, unchanged reference CSS copy, database-only content, supported GET locale/category/mode behavior and vanilla DOM menu/filter/lightbox enhancements. Original app.js and admin Tailwind never load on public pages. This advances public foundation work to satisfy the owner’s explicit Step 3 acceptance; browser polish and legacy URL compatibility remain Step 4, without rewriting it.
- Contact’s existing form card now supports Appointment request / General contact, four independent consultation choices only for appointments, truthful localized submit/privacy/success, CSRF/throttle/validation/synchronous MySQL save before internal PRG. Facebook remains a standalone link. Phone required for follow-up; optional email exists in the model but is not initially shown; no requested visit time or booking engine.
- During this first implementation, unchanged initial demo text was converted to canonical shared-fact placeholders under exact-value/old-checksum guards while users/inquiries were zero. This is not a routine update/import behavior; existing owner edits cannot be overwritten by the importer.

| Check | Actual result |
| --- | --- |
| Database connection | php artisan app:check-database: doctor_portfolio, MariaDB 10.4.32; connection utf8mb4 / utf8mb4_unicode_ci. PASS. |
| Non-destructive migration | php artisan migrate: users + website migration DONE; migrate:status both [1] Ran. No migrate:fresh/reset/truncate/unrelated database changes. PASS. |
| Actual import | php artisan db:seed: Website content initialized: 165 records. Completion marker committed. PASS. |
| Initial validation failure | First seed caught the code registry’s lowercase-key regex rejecting original camelCase field names; the transaction rolled back. Named field keys now use the explicit allowlist. Retry succeeded without duplicated records. |
| Real repeated seed | php artisan db:seed: Website initialization already completed; existing content was not changed. PASS. |
| Snapshot/database fidelity | Every initial record’s supplied values matched the snapshot; **266 JSON locale pairs**, all **81** source keys’ destinations resolved in en/bn, both paragraphs per article per locale, marker checksum valid. PASS. |
| Focused PHPUnit suite | php artisan test: **10 tests passed, 161 assertions**. Fallback/structured empties, original inventory/slugs/relations, transaction rollback, edit/draft/hide/delete/password preservation and missing-only repair, validation/unique slug, bilingual route readability, two request kinds/invalid request/GET side effects, explicit admin Gate. PASS. |
| Test isolation | Only doctor_portfolio_test created/migrated; guard enforces that name. Per-test DML transactions roll back every fake user/inquiry/content import. No RefreshDatabase/migrate:fresh. Development remains users 0 / inquiries 0. PASS. |
| Public readability | PHPUnit HTTP checks: /, /about, /services, /diagnostics, /blog, /gallery, /contact + all 4 /blog/{slug} in en and bn return 200 with original article text; unknown/draft 404. No assets/app.js or /build/ in public HTML. PASS. |
| Blade build | php artisan view:cache: compiled successfully. PASS. Windows denied atomic replacement/removal of one stale local compiled contact view (also on outside-sandbox retry); generated artifacts were refreshed in place from current Blade code, without vendor/source/DB changes. Full public cache compilation then passed. No cache workaround is in production application code. |
| PHP style | vendor/bin/pint on touched PHP files: formatting/import cleanup completed; final check recorded after final verification. |
| Admin assets | npm run build: Vite 7.3.7, 2 modules, manifest + admin-BPT_co5T.css / admin-C-qhbeH0.js built successfully; isolated unchanged entries. PASS. |
| Source preservation | Git/source hashes and public stylesheet copy checked against original audit; frontend remains unmodified. Exact final results recorded below. |
| Documentation | SEED_CONTENT_REPORT created; architecture/matrix/status/README updated to actual schema, setup/repair/test commands and limitations. |

## Step 3 remaining boundaries and next step

No database/import blocker remains. Photos are still **nine legacy remote URLs**, including the fragile signed Facebook hero; no verified local photo/logo/favicon/font files or upload bytes were supplied. MIME/size/dimensions stay null. Stock and diagnostic caveats are preserved; no test menus, contact details, dates, credentials or images invented. Original Step 1 availability evidence is historical, not a new image verification.

Private admin login, secure interactive account creation, content editors, upload controls and inquiry screens are intentionally pending Steps 5 onward. No admin account exists. Contact submissions already save privately, but staff management is not yet implemented; do not describe the whole CMS as finished. Full browser visual/mobile/no-JS QA, failure handling and safe legacy redirects are Step 4. cPanel access/infrastructure is still unverified and no production deployment occurred.

Next: execute **Step 4** incrementally using the existing public components, then **Step 5** auth and secure interactive provisioning, reading AGENTS.md and current docs first. Do not re-audit source or reset/reseed the owner’s database.

Final Step 3 verification: **22 HTTP kernel responses using the actual doctor_portfolio database returned 200** (seven pages + four articles, each in en/bn). The isolated test database had **zero remaining inquiry/user/post/marker fixture rows** after rollback. A final actual-content comparison still matched **266 bilingual pairs / 81 source keys** and the completion checksum. All **10 frontend source hashes** matched the original audit; copied public CSS was byte-identical; all six project documentation artifacts were non-empty; no public/assets/app.js exists. Pint --test passed, composer validate --strict passed, git diff --check reported no whitespace errors, and git diff --exit-code -- frontend reported no source changes. The runtime source/seed/eval search across controllers/views/public.js/WebsiteContent returned no matches. No new dependencies were added in Step 3. The final focused suite remained **10 passing tests / 161 assertions**; npm build and Blade cache compilation passed.
