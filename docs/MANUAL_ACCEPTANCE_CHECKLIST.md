# Manual visual and browser acceptance — Step 14

**Pending, not executed.** Browser automation returned no surfaces and `Browser is not available: iab`. Source/HTTP/DOM/security results are in [TEST_REPORT](TEST_REPORT.md); these checkboxes require actual render inspection. Do not infer pixel parity from the automated DOM comparison.

## Safe setup

Use only doctor_portfolio_testing or an explicitly isolated staging installation, never the owner's development/production data. Follow README's separate-shell testing setup and non-destructive migrations. For this manual session initialize the clean testing schema explicitly with php artisan db:seed. Ordinary subsequent seeding must skip completed imports. Do not run the rollback suite concurrently while committed manual fixtures are present. Record fixture IDs/token hashes and remove only those records afterwards; do not reset/truncate a database.

For real browser CSRF use a separate PowerShell window with these local HTTP overrides (private credentials still come from .env):

```powershell
$env:APP_ENV = 'local'
$env:APP_DEBUG = 'false'
$env:DB_CONNECTION = 'mysql'
$env:DB_DATABASE = 'doctor_portfolio_testing'
$env:DB_URL = ''
$env:APP_URL = 'http://127.0.0.1:8784'
$env:SESSION_COOKIE = 'doctorportfolio_testing'
php artisan serve --host=127.0.0.1 --port=8784 --no-reload
```

Do not use APP_ENV=testing for the live browser CSRF check; Laravel exempts that environment. Build assets first with npm run build; no npm dev/queue/Redis/SMTP process is needed. Use php artisan admin:create only with your chosen disposable testing credentials, typed interactively; never screenshot/store a password in this guide.

The unmodified reference is private beneath the project-root Apache access rule. In a separate window run `php -S 127.0.0.1:8786 -t frontend` strictly for localhost reference QA. Stop both QA servers when finished. The dynamic public layout must never load that reference app.js.

## Exact paired routes

For **each row below**, capture English and Bangla at **1440×1000** and **390×844**:44 reference/dynamic pairs (88 images if all are captured separately). Select the reference language with its original English/বাংলা control; dynamic URLs must explicitly include ?locale=en or ?locale=bn (append &locale where appropriate). Clear only the reference site's language preference when needed. Match zoom100%, viewport, font readiness and image/network state; wait for document.fonts.ready and image completion. Record failed requests, not substituted assets. Use full-page and focused screenshots where necessary. Keep patient/staff data out of shareable captures.

| Page | Reference on127.0.0.1:8786 | Dynamic on127.0.0.1:8784 |
| --- | --- | --- |
| Home | /index.html | /?locale=en or bn |
| About | /about.html | /about?locale=en or bn |
| Services | /services.html | /services?locale=en or bn |
| Diagnostics | /diagnostics.html | /diagnostics?locale=en or bn |
| Blog | /blog.html | /blog?locale=en or bn |
| Gallery | /gallery.html | /gallery?locale=en or bn |
| Contact | /contact.html | /contact?locale=en or bn |
| Pregnancy article | /article.html?id=pregnancy-care | /blog/pregnancy-care?locale=en or bn |
| PCOS article | /article.html?id=pcos | /blog/pcos?locale=en or bn |
| Fertility article | /article.html?id=fertility-first-visit | /blog/fertility-first-visit?locale=en or bn |
| Menopause article | /article.html?id=menopause | /blog/menopause?locale=en or bn |

- [ ] Compare every pair: topbar/brand/menu, typography, line breaks, hero/image crop and ratios, card spacing/icons, section order, all five numbered service blocks, medical/stock/diagnostic notices, overlays/hover zoom, CTA/footer/copyright and responsive stacking. Inspect horizontal overflow at390px and around1050/850/550px boundaries. Check Bengali wrapping and focus outlines. No public redesign or Tailwind reset.
- [ ] Home has four cards and first three eligible source posts/gallery; Services five visible blocks; Gallery eight initial entries. Original badges stay topic labels, and source Bengali captions/paragraphs remain unchanged.
- [ ] Record only approved differences: contact mode selector/truthful submit/privacy/help/success/error copy, server locale/filter links and invalid-ID404, keyboard/accessibility additions, metadata/SEO. Report any other DOM/style/layout difference as a defect, not an improvement.

## Public interactions and language

- [ ] Mobile menu: open with Enter/Space, inspect links, Escape/outside close, resize restoration and focused trigger. Confirm active navigation on every page/article and no clipping.
- [ ] Start /blog?locale=bn&category=clinical; switch language and verify same category/article identity. Follow navigation/article/back/filter/pagination links; reload without locale then paste a copied ?locale=bn URL into a separate session. Validate server html lang and selected preference. Unsupported locale must resolve safely.
- [ ] Blog filters clinical/wellness; gallery facility/clinical/wellness; All label is editable. Add enough disposable entries to exceed configured pagination, then verify filter-before-pagination/locale links across pages2+. Invalid category yields defined safe handling; no unrelated content/drafts.
- [ ] Gallery: activate by Enter/Space and pointer; exact caption/alt/image, overlay and original framing. Tab stays in dialog, Escape/outside/close work, focus returns and page scroll restores. Replace an asset in admin; card/lightbox/OG resolve the same new reference without changing proportions or implying it is verified clinic imagery.
- [ ] Disable JavaScript and reload: all primary headings/cards/bodies/notices remain. Filters/locale/navigation use real links. Submit either inquiry kind using the native selector; no client success is required. General ignores consultation; appointment validates it server-side.
- [ ] Check /index.html,/about.html,/services.html,/diagnostics.html,/blog.html,/gallery.html,/contact.html with locale and validated filters; they redirect internally. /article.html?id=pcos&locale=bn goes to the published Bengali article. Unknown/draft/future article URLs404 with styled database-copy error. Explicitly changed published slug redirects safely to its current article.

## Admin/content acceptance (disposable fixtures only)

- [ ] Log in through /admin/login, observe generic failure/throttle; guest/non-admin direct CRUD/detail/preview access denied. Logout is POST, profile/password requires current password; other sessions are invalidated as documented. Sidebar/tables/forms/dialogs/media picker fit390px and keyboard use.
- [ ] Follow [ADMIN_CONTENT_GUIDE](ADMIN_CONTENT_GUIDE.md) and [CONTENT_TRACE](CONTENT_TRACE.md) for every public section/control. Change a shared biography/address/hours/doctor fact once; all consumers update on next request. Change heading/CTA/nav/disclaimer/SEO/alt/category/article/gallery data; inspect public output, not merely editor save messages.
- [ ] Save distinct EN/BN home titles, reload both locale URLs, make an English-only edit, verify Bangla retained, clear Bangla, verify English fallback, restore it. Repeat with different paragraph counts/order; plain markup must display escaped. Tab switches must keep unsaved text.
- [ ] Add/remove/move/disable card/service/option/article/gallery/category fixtures independently; verify source4/5/4 distinction and correct ordinals. Category/media use protection and explicit reassignment/replacement work. Published/current/homepage flags/counts/order control previews independently; future/draft preview stays private/noindex.
- [ ] Upload valid JPEG/PNG/WebP under5MiB and limits6000px/24million pixels. Reject SVG/HTML/PHP/disguised raster/oversize/traversal. In-use deletion blocked; removing one entry retains shared media. Original external images and stock caveats stay truthful. Safe schemes/named destinations only; no arbitrary HTML/embed/template/CSS editor.
- [ ] On the testing schema change/delete/hide/unpublish demo records, rerun db:seed and verify those decisions and test password retained. A clean isolated initialization populates all original content immediately; never use migrate:fresh to perform this exercise.

## Inquiries, privacy and SEO

- [ ] At /contact?locale=en&kind=appointment and bn equivalents submit disposable name/phone/topic/message. General contact hides consultation with JS and requires message. Source limits: name80/phone40/message500; allow sensible phone strings/Bengali digits. Valid submission saves first, then same-site302/GET feedback. Missing required fields/invalid disabled option/CSRF/honeypot are honest errors with old input/locale, no row or false success.
- [ ] In browser Network verify POST only to /inquiries, no Facebook redirect/new window/clipboard call. A separate social-link click is independent. Retry an identical token and refresh: exactly one row. Token belongs to that session. Check modest rate limit with testing data only, after capturing owned hashes for cleanup.
- [ ] /admin/appointments and /admin/contact-messages show the exact saved fields in correct kind scopes. GET detail does not mark read. Explicit read/notes/status/manual confirmation-time writes persist after reload and history identifies handler. Invalid transitions/time and stale writes rejected. Destructive actions require confirmation; no email/Facebook/public confirmation. Guest/opposite-kind/public lookup denied.
- [ ] Verify /admin,/admin/login and preview have no-store/noindex; no patient data in URLs/log bodies/shareable screenshots. Verify /.env,/composer.json,/storage/logs and source/seed URLs inaccessible. Deploy only public/. Root/private Apache access must be checked on the actual cPanel host separately.
- [ ] View source on all eight types/both locales: escaped actual localized title/description/OG/image/alt, html lang, distinct en/bn canonical and reciprocal hreflang. Articles use their actual title/excerpt; valid category/page canonicals normalize all/page1/unrelated queries.
- [ ] /robots.txt advertises the configured absolute sitemap and excludes private/internal paths. /sitemap.xml initially has22 URLs/44 reciprocal alternates; no drafts/future/admin/login/previews/inquiries/legacy/filter variants. Edit content/unpublish and re-fetch: sitemap/ETag change synchronously, lastmod reflects real content changes.
- [ ] Repeat all primary page fetches from an isolated release without frontend/ or seed snapshots; no npm dev server/worker/scheduler/Redis/SMTP. Only explicitly initializing a fresh database requires bundled seed input.

Record screenshot filenames, viewport/locale/font/image state, exact route/action, pass/fail and fixes in TEST_REPORT. Remove only recorded testing submissions/staff/content fixture IDs afterwards; retain migration schema. Stop QA/reference servers, close override shells and remove temporary private env/credential copies. Mark visual acceptance complete only after inspecting the renders.
