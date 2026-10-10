# DoctorPortfolio

Standalone Laravel 12 / PHP 8.2 application with Blade, Eloquent and vanilla JavaScript. Steps 3–13 supply initialized public pages, private authentication/dashboard/profile, media, settings/page/navigation editors, care/service/consultation editors and category/article and gallery CRUD. Hardened bilingual appointment/contact submissions now save synchronously. Kind-scoped private staff inboxes, the real-data dashboard and audited database-backed public content/SEO are implemented. Read AGENTS.md and docs/IMPLEMENTATION_STATUS.md before continuing.

## Local setup (PowerShell, project root)

PHP on this machine resolves to `E:\xampp8.2\php\php.exe`, using `E:\xampp8.2\php\php.ini`. Composer's launcher uses that same PATH PHP. XAMPP PHP 7.4 is later on PATH; leave global installations unchanged. To select 8.2 explicitly, use `& 'E:\xampp8.2\php\php.exe' artisan ...` or run Composer with that executable and `C:\ProgramData\ComposerSetup\bin\composer.phar`.

```powershell
composer install
if (!(Test-Path -LiteralPath .env)) { Copy-Item -LiteralPath .env.example -Destination .env }
# Set your local DB_USERNAME and DB_PASSWORD in .env using an editor.
php artisan key:generate
npm ci
npm run build
php artisan app:check-database
php artisan migrate
php artisan db:seed
php artisan serve
```

Open http://127.0.0.1:8000. Public pages and /blog/{slug} articles read database content in English/Bangla; use ?locale=en or ?locale=bn for reproducible language URLs. Language preference persists in the file session. Blog/gallery accept validated ?category= database slugs; original .html links redirect to named routes. /up is Laravel's health route. /admin redirects guests to /admin/login; authenticated admins see the dashboard. No default account is seeded. Do not regenerate an existing APP_KEY during updates.

## Create your admin account

After database setup and asset building, run this **locally from the project root**:

```powershell
php artisan admin:create
# Or explicitly select this machine's XAMPP PHP:
& 'E:\xampp8.2\php\php.exe' artisan admin:create
```

Enter your own name, a unique email and password at the prompts. Password and confirmation are hidden, with no visible fallback. Use at least 12 characters with uppercase, lowercase, a number and a symbol; the UTF-8 limit is 72 bytes to avoid bcrypt truncation. Credentials cannot be passed as command arguments or supplied with --no-interaction. The command creates a hashed admin account atomically and never overwrites/promotes an existing account. No owner account has been created during implementation; only disposable test accounts were used in the isolated test database.

Visit http://127.0.0.1:8000/admin/login. Dashboard, Website Settings, Page Content, Navigation, Home Care Cards, Services, Consultation Options, Categories, Articles, Gallery, Media and Profile are available; other sidebar destinations are marked Soon without dead links. Website Settings provides twelve groups; Page Content covers home/about/services/diagnostics/blog/gallery/contact/article. Use English / বাংলা tabs and save normally; both translations remain in one form, and public pages read changes on the next request. Shared facts are edited once. Optional section visibility and homepage counts are supported; layout/order/styles remain fixed. See docs/CMS_EDITORS.md for routes, exact controls, safe links and metadata fallback. OG values can be stored now; public OG/remaining SEO rendering is Step 13.

Profile changes and password changes require your current password. Logout is POST-only; password changes invalidate other sessions on their next admin request. There is no registration, password-reset email service or user-management module. See docs/ADMIN_INTERFACE.md for security and reusable editor controls. For an existing initialized installation, run `php artisan migrate` and `npm run build` after updating; for the Step 11 update also run php artisan db:seed once to initialize five new feedback/help labels without replacing existing content. The additive article OG/slug-redirect migration has already run on both dedicated local databases. Existing content and credentials are retained.

Start **MySQL** from XAMPP Control Panel. XAMPP here exposes **MariaDB 10.4.32** at 127.0.0.1:3306. The dedicated `doctor_portfolio` database is connected and explicitly initialized with the full original bilingual content. For another machine, create only this dedicated database with utf8mb4/utf8mb4_unicode_ci in phpMyAdmin and set the matching local credentials in private `.env`. Never use an unrelated database. The Step 3 non-destructive migrations and import have run locally; no fake inquiries or default users exist.

Defaults: DB_CONNECTION=mysql, APP_TIMEZONE=Asia/Dhaka, SESSION_DRIVER=file, CACHE_STORE=file, QUEUE_CONNECTION=sync and MAIL_MAILER=log. `config/app.php` reads APP_TIMEZONE. `.env.example` contains placeholders, no real login or application key. After changing .env:

```powershell
php artisan config:clear
php artisan app:check-database
```

If connection fails, start the existing XAMPP service, verify this database and local DB_* values, then rerun those two commands. Do not substitute SQLite or reset databases. Future database tests must use a separate `doctor_portfolio_test` schema; never run destructive test migrations against the development schema.

## Assets

```powershell
npm ci
npm run build
# Optional, only while developing admin assets (second terminal):
npm run dev
```

Pinned Vite 7.3.7 and Tailwind 4.3.3 compile `resources/css/admin.css` and `resources/js/admin.js`. Tailwind scans admin views, admin Blade components, admin JS and Laravel's Tailwind pagination template only. Admin app/guest layouts include these entries via @vite; public pages never extend them. Plain Vite uses a manifest, with no client-framework or Laravel-specific Vite plugins. A small development runner creates/removes `public/hot` for @vite; Node is a temporary asset development tool, not a production runtime. Stop it with Ctrl+C. If forcibly killed, remove only its stale public/hot file before serving built assets.

Public pages load public/assets/style.css (an identical reference copy), tiny public/assets/interactions.css for GET controls/focus, and interaction-only public/assets/public.js; initial images retain legacy external URLs. `frontend/` remains an unchanged reference and its app.js is never loaded by Laravel. No Tailwind/JavaScript CDN, Axios, framework starter kit, client router, queue worker, scheduler or Docker tooling is installed. Use `php artisan serve`; Composer's default multi-process dev/setup commands were removed.

## Directory structure and checks

```text
app/                     Laravel models, controllers and providers
bootstrap/app.php        Laravel 12 routing/middleware configuration
config/                  Environment-driven application settings
routes/                  web.php and read-only DB check in console.php
resources/views/admin/   Admin login, dashboard/profile, layouts and shared parts
resources/views/components/admin/ Reusable private form/table/editor controls
app/Http/Controllers/Admin/ Guarded base controller, auth/dashboard/profile
app/Console/Commands/    Interactive admin:create; never a credential seeder
resources/views/public/  Database-backed public pages/shared parts
database/seeders/data/   UTF-8 initialization snapshot; never runtime content
config/*content*.php     Typed content controls; values live in the database
resources/css/admin.css  Admin-only Tailwind entry
resources/js/admin.js    Vanilla admin enhancement
scripts/vite-dev.mjs     Asset development runner
public/                  Only production web document root
public/build/            Generated manifest and hashed admin CSS/JS
storage/                 Private data, sessions/cache/logs
public/uploads/          Configurable public image disk, safety rules; no symlink
frontend/                Unmodified original reference; not deployed
docs/                    Audit, architecture, matrix and status
```

```powershell
composer validate --strict
composer check-platform-reqs
php artisan about
php artisan app:check-database
npm run build
```

`composer.json` restricts Laravel to ^12.0 and Composer resolves packages for PHP 8.2.12; composer.lock and package-lock.json preserve exact versions. Install from these lockfiles rather than upgrading dependencies casually. Bootstrap temp directories, caches, dependencies, private .env/storage, runtime uploads and generated build files are ignored.

## cPanel artifacts

Build locally and deploy **public/build/manifest.json plus every referenced file under public/build/assets/** even though public/build is Git-ignored. Deploy production Composer vendor (`composer install --no-dev --optimize-autoloader`) and the application outside the web root; expose only public/. Keep frontend/, docs/, .env, private storage and temporary directories outside public_html. Do not deploy node_modules or public/hot. Production has its own private .env, preserved APP_KEY, APP_DEBUG=false, HTTPS/session settings, and writable storage/bootstrap/cache. Public images use the configured public-media disk with tracked upload safety rules; no storage link is required. See docs/MEDIA_MANAGER.md and docs/ARCHITECTURE.md for the shared-hosting strategy.

## Image library

Run `php artisan migrate` to apply the new nullable media caption column, then use `/admin/media`. Upload JPEG/PNG/WebP up to 5 MiB, 6000 pixels per edge and 24 million pixels. Edit English/Bangla title, alt, caption and provenance/stock notes; copy public URLs, inspect usage and explicitly replace references before deleting an in-use image. Replacing/removing one reference never deletes a shared file. Original external records remain URLs until you choose real uploads; no demo images were downloaded in Step 6.

The **public-media** disk defaults to `public/uploads` and `APP_URL/uploads`, without `storage:link`. For split cPanel layouts set private `PUBLIC_MEDIA_ROOT` to the account's absolute public_html/uploads path and `PUBLIC_MEDIA_URL` to its HTTPS /uploads URL, deploy public/uploads/.htaccess there and verify host overrides/MIME/handler rules. Runtime files are ignored; the safety .htaccess/.gitignore are tracked. Do not store private documents/inquiries on this disk. See docs/MEDIA_MANAGER.md for setup, PHP limits, shared-picker usage and synchronous failure recovery. Public external images and Google Fonts remain network dependencies.

## Content initialization and tests

Step 8 activates `/admin/care-cards`, `/admin/services` and `/admin/consultation-options`, with independent bilingual CRUD, accessible up/down ordering and confirmed disable/delete. Their initial **4 cards / 5 service blocks / 4 appointment choices** already exist. Card links are limited to Services/Diagnostics; service ordinals follow enabled order. Options retain stable keys and existing inquiry snapshots even after deletion. Diagnostics stays at `/admin/pages/diagnostics/edit`, with shared clinic/CTA sources and its original availability caveat. See [CARE_CONTENT](docs/CARE_CONTENT.md) and CONTENT_MATRIX. No new migration or seed is required for this step on the initialized local database.

On a new dedicated MySQL/MariaDB database run `php artisan migrate`, then `php artisan db:seed`. The completed import marker makes later normal seeding skip existing website content, including deleted originals. No default admin/password or fake inquiry is created. Seed files are explicit initialization input only; production needs the snapshot but does not need frontend/ or Node to seed.

Only to deliberately restore missing original demo records run `php artisan website:repair-initial-content`. It preserves all existing edits/publication/visibility/passwords, but recreates missing demo rows in their original enabled/visible/published state and checks the original snapshot checksum. Do not use it as a routine update command or use migrate:fresh. See docs/SEED_CONTENT_REPORT.md for actual counts/mappings and remote-photo limitations.

Focused tests use only `doctor_portfolio_test`, with an explicit guard and per-test DML rollback. Create that dedicated schema with utf8mb4, apply migrations without seeding, then run tests (PowerShell):

```powershell
$env:DB_DATABASE = 'doctor_portfolio_test'
php artisan migrate
Remove-Item Env:DB_DATABASE
php artisan test
```

Keep .env set to doctor_portfolio for development; phpunit.xml selects the isolated test schema. No RefreshDatabase/migrate:fresh or test records in the development database. Admin provisioning is an explicit interactive command, separate from initialization. The contact form saves appointment requests/general messages locally before success; the protected Appointments/Contact Messages inboxes manage those same saved records.

Run interaction checks without extra packages: `node --test tests/JavaScript/public-interactions.test.mjs`. See docs/PUBLIC_VERIFICATION.md for Step 4 route/structure results and the manual desktop/mobile checklist. Browser automation was unavailable, so screenshots are not claimed. Article CRUD must reuse resources/views/admin/partials/paragraph-repeater.blade.php and plain localized paragraph arrays; no rich-text editor is installed.

Admin interaction checks: `node --test tests/JavaScript/admin-interactions.test.mjs`. This Windows automation environment denies Laravel's atomic replacement of existing compiled Blade files, including an outside-sandbox retry of `php artisan view:cache`. Generated views were refreshed in place with the existing local QA helper (`php .local-artifacts/refresh-views.php`); application/vendor code and owner content were unchanged. That ignored helper is local QA tooling, not a deployment requirement. On the actual writable production host, verify Laravel's normal view-cache command during the hosting step.


## Categories and articles

Use `/admin/categories` for stable bilingual category identities/applicability/order and explicit reassignment before in-use deletion. Use `/admin/articles` for bilingual title/excerpt/independent plain-text paragraph lists, topic badge, category/images/alt/metadata, order/homepage and draft/publication controls. No title autoslugging: explicit slug changes require confirmation and retain old-link redirects. Empty Bangla falls back to English; omitted translations remain unchanged. Shared media files survive article deletion.

Saved drafts/future articles can be previewed in both languages through authenticated `/admin/articles/{id}/preview`; preview is private/noindex/no-store. Public blog filters applicable database categories before numbered pagination (9/page initially), preserves validated locale/category/page, and rejects invalid article categories with 404. Future timestamps stay non-public through request-time queries. All four original articles and first-three homepage previews remain initialized. Post OG selection is stored/protected; complete public OG rendering is Step 13. See [EDITORIAL_CMS](docs/EDITORIAL_CMS.md) for routes, controls, repair behavior and manual review.


## Gallery editing

Use `/admin/gallery` for image/category, bilingual caption/alt, independent public/homepage flags, search/filter/pagination and accessible Move up/down controls. Moves use the entire collection, including hidden entries and other categories/pages. Gallery categories must have gallery applicability; in-use category/media deletion is blocked. Removing an entry retains shared image records/files. All eight original entries and their Bengali captions remain unchanged; no Step 10 migration or reseed is required.

Browse library → Upload image → Choose image now retains picker mode and returns the local asset to the unsaved editor. Without JavaScript, upload first, then open/reload the form and select it. Public gallery applies filters/visibility before pagination (9/page initially), retains original classes/850px and 550px breakpoints/lightbox and the shared stock-image notice. Empty states use existing localized UI copy; Home remains first three visible homepage-selected images, with count controlled by Page Content. See [GALLERY_CMS](docs/GALLERY_CMS.md) for exact fields/routes/tests and manual mobile/lightbox review.


## Appointment and General contact submissions (Step 11)

Both modes use the existing contact card and POST /inquiries. Appointment CTAs link to /contact?kind=appointment&locale=en|bn; General can use kind=contact. Name/phone are required, source limits stay 80/40/500, General message is required, and only Appointment accepts an active consultation choice. Bengali digits/leading zeros and plain message line breaks are retained. The kind selector works without JavaScript; JS enhances appointment-only hiding and pending feedback. Separate Facebook links are ordinary independent links. Save commits before truthful success; requests do not confirm visits.

For an existing installation run these explicit, non-destructive commands:

```powershell
php artisan migrate
php artisan db:seed
```

Migration 000005 adds nullable private duplicate-protection columns. The original completed import skips and a separate create-missing-only marker initializes five localized feedback/help fields. Subsequent normal seeds preserve edits/deletions; no credentials or inquiries are seeded. Edit copy under Website Settings → Contact form & feedback copy. Keep file sessions/cache writable: CSRF, a separate session-bound unique submission token, honeypot and 10/minute file-cache limiter protect native POST. No SMTP or email notification is used; database/private staff inbox is the notification mechanism. Step 12 verified actual en/bn × both-kind POSTs in the correct protected inbox/details, including persisted staff notes/read status/history. See docs/INQUIRY_WORKFLOW.md for rules, actual localhost commit/retry/CSRF checks, failure behavior and manual review.


## Staff inboxes and dashboard (Step 12)

Run php artisan migrate and npm run build for the additive status-history table and current admin assets. No Step 12 reseed/new credential is needed. Log in with the existing owner-created account, then open /admin/appointments or /admin/contact-messages. Both use the same saved public Inquiry rows; each kind is enforced on list/detail/read/save/delete. Search name/reference/phone and status/received dates stay in the private session, with paginated results and no personal data in links.

Opening a request does not mark it read. Use Mark read or explicitly save status/private notes. Appointment has optional manually confirmed time; General has no confirmation controls. Sensitive changes and permanent deletion require confirmation; stale forms are rejected. Changes record staff/time/status history and send no email/Facebook notification. Deletion affects only the active record/local history, not backups. Dashboard counts and recent private links are actual database results; quick links open content editors and public EN/BN preview.

All admin responses are private/no-store/noindex, with sensitive debug/log protection. Do not share screenshots of real submissions/notes/credentials. See [ADMIN_INQUIRIES](docs/ADMIN_INQUIRIES.md) for exact transitions, time rules, filters, failure/backup boundaries and manual desktop/mobile review. Full PHP suite: 118 tests pass; real localhost bilingual public-submit → staff-review integration passed in the isolated MySQL test database and disposable fixtures/account were cleaned. Public SEO is the next owner step.


## Content editing and SEO (Step 13)

Read [ADMIN_CONTENT_GUIDE](docs/ADMIN_CONTENT_GUIDE.md) for every public section's editor, shared facts, English/Bangla fallback, stock-photo/visibility/publication and inquiry rules. [CONTENT_TRACE](docs/CONTENT_TRACE.md) closes every matrix row through actual validation/database/render consumers. All active copy is database-backed; layout/CSS/system controls stay fixed. Public 404/metadata now use the same localized CMS. No source/seed content fallback or JS catalog exists.

Existing installations explicitly run php artisan db:seed for the four missing-only department/404 copy fields, then npm run build. Separate completion marker preserves repeated-import edits/deletions; no migration/new owner credential is needed. Set private APP_URL to the absolute deployed public root, without query/fragment/credentials. Server tags include localized title/description/OG/image/canonical/reciprocal en/bn alternates; article context uses the actual title/excerpt. English/Bangla canonical URLs remain distinct. Valid collection category/page are normalized; contact kind/tracking do not create indexed duplicates.

Dynamic /robots.txt and /sitemap.xml use current database pages/publications and actual update times, exclude private/internal/legacy/filter URLs and recompute synchronously with immediate revalidation. Do not deploy a static public/robots.txt that shadows Laravel. Admin/preview/error privacy/index headers are enforced. No medical structured data, fake original dates, queue or external service is added.

Verified 126 PHP tests/9 JS checks/build; real protected editor changes reached public HTML immediately in both locales. A separate release without frontend or initialization snapshots matched all 22 page-locale responses and read those same database changes. Scoped test fixtures/account/private copied env were cleaned. Source CSS/DOM remain preserved; browser pixel/mobile and real cPanel review are still manual/later.
