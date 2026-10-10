# DoctorPortfolio

Standalone Laravel 12 / PHP 8.2 application with Blade, Eloquent and vanilla JavaScript. Step 2 supplies the framework and asset build; the public CMS and private admin screens are implemented in later steps. Read AGENTS.md and docs/IMPLEMENTATION_STATUS.md before continuing.

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
php artisan serve
```

Open http://127.0.0.1:8000. The root is currently a minimal boot view; /up is Laravel's health route. No admin/auth routes or CMS are implemented yet. Do not regenerate an existing APP_KEY during updates.

Start **MySQL** from XAMPP Control Panel. XAMPP here exposes **MariaDB 10.4.32** at 127.0.0.1:3306. The dedicated `doctor_portfolio` database was already present and empty; the read-only Laravel connection check succeeds. For another machine, create only this dedicated database with utf8mb4/utf8mb4_unicode_ci in phpMyAdmin and set the matching local credentials in private `.env`. Never use an unrelated database. No migrations have been run; the schema is Step 3.

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

Public stylesheet/media will have separate assets during the public-layout step. `frontend/` remains an unchanged reference and its app.js is never loaded by Laravel. No Tailwind/JavaScript CDN, Axios, framework starter kit, client router, queue worker, scheduler or Docker tooling is installed. Use `php artisan serve`; Composer's default multi-process dev/setup commands were removed.

## Directory structure and checks

```text
app/                     Laravel models, controllers and providers
bootstrap/app.php        Laravel 12 routing/middleware configuration
config/                  Environment-driven application settings
routes/                  web.php and read-only DB check in console.php
resources/views/admin/   Isolated admin layout, ready for later auth/screens
resources/views/         Temporary scaffold boot view
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
