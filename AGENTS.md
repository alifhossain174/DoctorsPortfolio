# DoctorPortfolio project rules

- Laravel 12.x, PHP 8.2, XAMPP MySQL for local development, `php artisan serve` from the project root, cPanel shared hosting for production.
- Plain Blade/Eloquent/vanilla JavaScript for BOTH public and admin screens; compiled Tailwind for admin only. No React, Vue, Angular, Svelte, Next.js, Alpine.js, Livewire, Inertia, SPA, or JS component framework, including dependencies introduced by starter kits. Preserve the public design and existing CSS/DOM classes. Do not load admin Tailwind into public pages.
- Admin entry `/admin`; no public registration, patient accounts, or third-party auth.
- MySQL/MariaDB is the runtime source of truth for all managed website content. Blade reads database content; public JavaScript only enhances interactions. No JavaScript content arrays, source-file reads, or seed-file content fallback at runtime.
- All public content editable from admin in English and Bangla using localized JSON `{en,bn}` fields and English / বাংলা editor controls. One shared localization helper implements fallback; no translation API. Fixed page layouts and CSS are code-owned; this is a content CMS, not a visual page builder.
- Import the full original bilingual demo content into the database during development and show it immediately. Seed files are for explicit initialization only. Repeat initialization must preserve admin edits and publication/visibility decisions.
- Keep `frontend/` as an unmodified reference. Do not execute or publish the original `app.js` as the dynamic site's content renderer. Audited source is directly at `frontend/`, not `frontend/demo3/`.
- No queue workers, scheduled jobs, Redis, Docker, WebSockets, payment/booking engine, or runtime Node service. Use ordinary synchronous request processing, file sessions/cache, and local uploaded media.
- Every appointment/contact form submits to Laravel, validates, saves a database record, and becomes visible in the private admin panel. Never launch Facebook/another site or copy a message as part of submission. Standalone social links may remain. An appointment request is not a confirmed booking.
- Use ordinary POST/Redirect/GET for inquiries and save before showing success. Language, filter, search, and login forms serve their own purposes and must not be mistaken for appointment/contact records.
- Preserve original stock-image and medical-information caveats. Never invent medical claims, credentials, tests, or contact details.
- Use Laravel 12 conventions, including middleware configuration in `bootstrap/app.php`. Do not assume an older `app/Http/Kernel.php` exists.
- No destructive database resets or overwrite of owner's content. Use migrations and non-destructive imports. Do not touch unrelated databases or global services.
- Work incrementally and reuse existing components; do not re-audit the whole source or rewrite completed modules in every step. Avoid unnecessary packages, abstractions, and repeated tests. Each step is implemented and verified before moving on. At the start of later steps read the project docs. If a documentation artifact is missing, reconstruct it from the actual project and this contract instead of stopping merely for the missing document. Real missing dependencies or inaccessible infrastructure must be reported accurately.
- Do not spawn sub-agents unless the owner explicitly authorizes delegation.
- Automated application tests must use MySQL/MariaDB `doctor_portfolio_testing`, with `APP_ENV=testing`, no `DB_URL`, and the central configuration/actual-schema guard in `tests/TestCase.php`. Use per-test DML rollback and non-destructive test migrations; no RefreshDatabase, migrate:fresh, owner-schema fixtures or patient cleanup outside that testing schema. Live HTTP checks use `APP_ENV=local` with debug off to exercise CSRF while explicitly retaining the same isolated database.

## Content ownership and inquiry contract

Use the modest relational design in `docs/ARCHITECTURE.md`: shared doctor/clinic settings, a code-defined registry of typed named page/section fields, navigation items, care cards, services, categories, posts, gallery images, media assets, inquiries, and one admin user model. Keep registry keys/validation in code and content values in the database. Growing collections and inquiries use dedicated relational models. Do not duplicate shared doctor/address/hours values across pages or expose an arbitrary unvalidated JSON editor.

Keep the existing contact-page form card and styling. Add a small Appointment / General contact mode selector; consultation type is displayed and accepted only for appointments. No new appointment page. Use truthful localized submit/privacy/CTA wording for server-stored inquiries and explicitly state that an appointment request is not a confirmed booking.

## Required project documents

Read `docs/IMPLEMENTATION_STATUS.md`, `docs/ARCHITECTURE.md`, `docs/CONTENT_MATRIX.md`, and the relevant portion of `docs/FRONTEND_AUDIT.md` before later steps. Update the status and matrix as each module is implemented. The source audit is a one-time baseline, not a reason to repeat the entire audit. Step 1 is documentation only: do not scaffold Laravel until a later authorized step.
