# DoctorPortfolio

Standalone Laravel 12 / PHP 8.2 application with Blade, Eloquent and vanilla JavaScript. Steps 3–4 supply the schema, bilingual initialization and server-rendered public pages/interactions. Private admin screens are later steps. Read AGENTS.md and docs/IMPLEMENTATION_STATUS.md before continuing.

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

Open http://127.0.0.1:8000. Public pages and /blog/{post:slug} articles read database content in English/Bangla; use ?locale=en or ?locale=bn for reproducible language URLs. Language preference persists in the file session. Blog/gallery accept validated ?category= database slugs; original .html links redirect to named routes. /up is Laravel's health route. Private admin/login/editors are not implemented yet; no default account is seeded. Do not regenerate an existing APP_KEY during updates.

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

Pinned Vite 7.3.7 and Tailwind 4.3.3 compile `resources/css/admin.css` and `resources/js/admin.js`. Tailwind scans only admin Blade/JS. Only `resources/views/admin/layouts/app.blade.php` includes these entries via @vite; public pages must never extend it. Plain Vite uses a manifest, with no client-framework or Laravel-specific Vite plugins. A small development runner creates/removes `public/hot` for @vite; Node is a temporary asset development tool, not a production runtime. Stop it with Ctrl+C. If forcibly killed, remove only its stale public/hot file before serving built assets.

Public pages load public/assets/style.css (an identical reference copy), tiny public/assets/interactions.css for GET controls/focus, and interaction-only public/assets/public.js; initial images retain legacy external URLs. `frontend/` remains an unchanged reference and its app.js is never loaded by Laravel. No Tailwind/JavaScript CDN, Axios, framework starter kit, client router, queue worker, scheduler or Docker tooling is installed. Use `php artisan serve`; Composer's default multi-process dev/setup commands were removed.

## Directory structure and checks

```text
app/                     Laravel models, controllers and providers
bootstrap/app.php        Laravel 12 routing/middleware configuration
config/                  Environment-driven application settings
routes/                  web.php and read-only DB check in console.php
resources/views/admin/   Isolated admin layout, ready for later auth/screens
resources/views/public/  Database-backed public pages/shared parts
database/seeders/data/   UTF-8 initialization snapshot; never runtime content
config/*content*.php     Typed content controls; values live in the database
resources/css/admin.css  Admin-only Tailwind entry
resources/js/admin.js    Vanilla admin enhancement
scripts/vite-dev.mjs     Asset development runner
public/                  Only production web document root
public/build/            Generated manifest and hashed admin CSS/JS
storage/                 Private data, local media, sessions/cache/logs
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

Build locally and deploy **public/build/manifest.json plus every referenced file under public/build/assets/** even though public/build is Git-ignored. Deploy production Composer vendor (`composer install --no-dev --optimize-autoloader`) and the application outside the web root; expose only public/. Keep frontend/, docs/, .env, private storage and temporary directories outside public_html. Do not deploy node_modules or public/hot. Production has its own private .env, preserved APP_KEY, APP_DEBUG=false, HTTPS/session settings, and writable storage/bootstrap/cache. Local uploaded public media and its storage link/host-compatible alternative are handled in later steps. See docs/ARCHITECTURE.md for the shared-hosting strategy.

## Content initialization and tests

On a new dedicated MySQL/MariaDB database run `php artisan migrate`, then `php artisan db:seed`. The completed import marker makes later normal seeding skip existing website content, including deleted originals. No default admin/password or fake inquiry is created. Seed files are explicit initialization input only; production needs the snapshot but does not need frontend/ or Node to seed.

Only to deliberately restore missing original demo records run `php artisan website:repair-initial-content`. It preserves all existing edits/publication/visibility/passwords, but recreates missing demo rows in their original enabled/visible/published state and checks the original snapshot checksum. Do not use it as a routine update command or use migrate:fresh. See docs/SEED_CONTENT_REPORT.md for actual counts/mappings and remote-photo limitations.

Focused tests use only `doctor_portfolio_test`, with an explicit guard and per-test DML rollback. Create that dedicated schema with utf8mb4, apply migrations without seeding, then run tests (PowerShell):

```powershell
$env:DB_DATABASE = 'doctor_portfolio_test'
php artisan migrate
Remove-Item Env:DB_DATABASE
php artisan test
```

Keep .env set to doctor_portfolio for development; phpunit.xml selects the isolated test schema. No RefreshDatabase/migrate:fresh or test records in the development database. Secure interactive admin provisioning belongs to Step 5. The contact form now saves appointment requests/general messages locally before success; private staff management is still pending.

Run interaction checks without extra packages: `node --test tests/JavaScript/public-interactions.test.mjs`. See docs/PUBLIC_VERIFICATION.md for Step 4 route/structure results and the manual desktop/mobile checklist. Browser automation was unavailable, so screenshots are not claimed. Article CRUD must reuse resources/views/admin/partials/paragraph-repeater.blade.php and plain localized paragraph arrays; no rich-text editor is installed.
