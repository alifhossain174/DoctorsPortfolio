# Step 5 admin interface and authentication

Step 5 implemented 2026-10-10; Step 6 extends it with the media library and shared picker (see MEDIA_MANAGER). Public templates, assets, original frontend and initialized content remain unchanged. The private UI uses standard Laravel routes/controllers/FormRequests/Blade, locally compiled Tailwind and vanilla JavaScript; no frontend framework or new dependency.

Current Step 8: Home Care Cards, Services and Consultation Options are active sidebar destinations with separate CRUD/order/visibility screens. CARE_CONTENT documents these 24 guarded routes and snapshot behavior. Article/category/gallery and staff inbox destinations stay non-links until implemented; prior module-availability descriptions below are historical.

## Available routes

Step 7 also activates Website Settings, Page Content and Navigation: GET /admin/settings + PUT /admin/settings/{group}; GET /admin/pages + GET/PUT /admin/pages/{page:key} (edit screen ends /edit); standard /admin/navigation resource except show. These use the same protected controller/Gate/session/CSRF boundary. CMS_EDITORS documents the twelve groups, all eight pages, safe targets, localized controls, shared-source links, atomic saves and metadata scope; CONTENT_MATRIX lists exact fields/renderers. Collection/inbox destinations remain Soon.

| Method | Path | Name | Guard/purpose |
| --- | --- | --- | --- |
| GET | /admin | admin.dashboard | auth, admin, auth.session; real database overview |
| GET | /admin/login | admin.login | guest; private sign-in form |
| POST | /admin/login | admin.login.store | guest, web CSRF; session sign-in with throttling |
| POST | /admin/logout | admin.logout | auth, admin, auth.session, CSRF; invalidate session/token |
| GET | /admin/profile | admin.profile.edit | auth, admin, auth.session; account/password forms |
| PATCH | /admin/profile | admin.profile.update | same guards/CSRF; current-password challenge and whitelist |
| PUT | /admin/profile/password | admin.password.update | same guards/CSRF; verified current password and strong new hash |

bootstrap/app.php configures admin guest/authenticated redirects and RequireAdmin alias. The middleware calls access-admin Gate. Future CRUD controllers **must extend App\Http\Controllers\Admin\AdminController** and belong in the existing protected admin group. The base implements HasMiddleware, so even an accidentally ungrouped future controller still requires auth/admin/auth.session. No public registration, password-reset service, patient accounts, third-party auth or user-management routes exist.

Login canonicalizes email and requires is_admin=true in Auth::attempt. Failures are generic for nonexistent users, wrong passwords and non-admins. Limits are 5 failed attempts per email+IP and 20 per IP for 60 seconds; keys hash identities and use the configured file cache. Success clears the identity bucket, regenerates the session and redirects only to the implemented dashboard. The IP bucket retains other identities' failed attempts. Validation/throttle errors return internally to admin.login, never a visitor-supplied URL. Passwords/current passwords are not flashed or rendered.

Logout invalidates the session and regenerates CSRF. Profile changes require current-password verification and only name/email may change. Password updates enforce confirmation, difference from current, at least 12 characters with upper/lowercase/number/symbol, and <=72 UTF-8 bytes/no null bytes to prevent bcrypt truncation/invalid hashing. User's existing hashed cast stores passwords; no plaintext column is introduced. The current session is regenerated, remember_token rotated, and auth.session rejects other sessions' stale password hashes on their next request.

## Owner provisioning

Run `php artisan admin:create` interactively from the project root after migrations. On this machine, explicitly selecting PHP is `& 'E:\xampp8.2\php\php.exe' artisan admin:create`. Supply your own name, unique email, password and confirmation. Both password prompts are hidden with visible fallback disabled. No credential arguments/defaults; --no-interaction fails. Email is trimmed/lowercased; uniqueness/strength are validated before an atomic create. Existing accounts are never overwritten or promoted; failed insertion output omits raw SQL/password hashes.

No owner account was created as part of this implementation. Tests used random disposable credentials only in doctor_portfolio_test and rolled back. Neither database seeder, migration, application boot nor deployment auto-creates an admin.

## Interface and reusable controls

The jade/slate workspace has a responsive header, desktop sidebar/mobile overlay navigation, system fonts, consistent cards/table/form fields, badges, focus outlines, flash/validation messages and honest empty states. All 13 requested destinations are present: Dashboard; Website Settings; Page Content; Navigation; Home Care Cards; Services; Categories; Articles; Gallery; Media; Appointments; Contact Messages; Profile. Dashboard, Profile and Media now have routes/links (Step 6); other modules remain non-interactive Soon labels. Dashboard counts actual published posts, visible gallery entries and new appointment/contact inquiries; page/media totals describe existing records. It contains no sample staff activity or invented bookings. Further dashboard work remains a later step.

Private views/components are under resources/views/admin and resources/views/components/admin. Public layout never loads admin Vite entries. Admin CSS scans private views/components, admin.js and Laravel's built-in Tailwind pagination view. Admin pagination explicitly calls pagination::tailwind; it does not globally change future public pagination or include Bootstrap.

| Component | Contract |
| --- | --- |
| x-admin.input / textarea | Escaped current/old values, labels/hints/errors, accessible state; password inputs never echo values; malformed old arrays safely redisplay |
| x-admin.localized-tabs | Both en/bn inputs present in one form, English required as configured; missing-Bangla badge and fallback help; tab switches hide panels without clearing/disabling inputs |
| x-admin.paragraph-repeater | Existing Post.body plain arrays; uses the shared paragraph partials; add/remove/up/down only changes DOM order; names body[en][]/body[bn][] |
| x-admin.table / pagination | Accessible headers/caption and horizontally scrollable table; explicit Laravel Tailwind pagination view |
| x-admin.validation-summary / badge / button / breadcrumbs / empty-state | Consistent escaped private UI; named error bags for account/password forms |
| admin.partials.flash | Status message, live region and optional vanilla dismissal |
| x-admin.delete-confirmation | CSRF+DELETE form, native dialog with cancel/Escape/backdrop and explicit confirmation; without JS, required confirmation checkbox remains. Future action validates confirm_delete=accepted, authorizes deletion and protects usage relations |
| x-admin.media-picker | Step 6: native existing-asset select/preview, paginated same-origin library popup; ordinary parent form saves a validated FK; no shared-file deletion on reference removal |

Without JavaScript both translation panels remain visible; normal forms still submit. With JS, tabs have keyboard arrows/Home/End and selected/panel semantics; invalid hidden controls reveal their panel. Unsaved values remain unchanged and both translations submit. Mobile sidebar temporarily traps focus/inerts background, restores focus/overflow on close and supports Escape/backdrop/desktop resize. Delete dialog and double-submit enhancements retain standard form semantics. No SPA/client router, content API, JavaScript translation dictionary or raw HTML editor exists.

Future editors pass approved validated fields into `AdminContentWriter::update`. It authorizes via Gate, rejects unknown/non-editable keys, permits only en/bn language keys, safely trims strings and plain paragraph arrays, locks the current record, merges omitted languages unchanged, validates the complete record through CmsModel/ContentRegistry and saves in a transaction. Both-language changes are atomic; an English-only update retains Bangla, including deliberate empty values. Required English cannot become empty. Blank Bangla paragraph arrays normalize to null and use the shared public English fallback. Multi-record forms need an outer transaction; create requests must use the same registry validation/whitelists. CRUD endpoints are intentionally not created ahead of their modules.

## Verification and local limitation

The status document records final test/build counts. Auth tests cover guests, non-admins, an ungrouped inherited future controller, CSRF, success/failure/malformed input, both throttle buckets, retry expiry, session regeneration, logout/token invalidation, profile whitelists, password verification/strength/hash/stale sessions and interactive command repeat/uniqueness/non-interaction/weak/mismatched/overlong inputs. Content-control tests cover atomic bilingual updates, omitted Bangla, invalid JSON/paragraph/field rejection, escaping and actual dashboard counts. Six dependency-free JS tests (public + admin) use DOM doubles for observable behavior; they are not browser screenshots.

Actual local HTTP verifies guest /admin and /admin/profile redirects, login 200 with CSRF/local compiled assets, and public en/bn pages without admin CSS. Authenticated screens are verified through the Laravel test HTTP kernel with disposable test users; no owner credentials/account or development inquiry was created for testing. Browser inventory remains empty; no desktop/mobile screenshot or pixel-perfect claim is made.

`php artisan view:cache` failed here with Windows Access denied on atomic replacement of existing generated compiled views, including an outside-sandbox retry. The existing ignored QA helper refreshed all 50 project Blade files in place; subsequent rendering/tests passed. Application/vendor code, permissions/global services and database content were not changed to bypass it. Verify the ordinary view-cache command on the actual writable cPanel host in the deployment step; no claim that that command passed locally.

When an owner account is provisioned, manually check desktop/mobile login, dashboard/profile spacing, sidebar keyboard/focus/scroll behavior, both error bags and password fields without echoed values. In future editor screens, verify unsaved tab text, paragraph order, Tailwind pagination and delete confirmation with and without JavaScript. Build locally and deploy the complete public/build manifest/assets directory; no runtime Node is needed.
