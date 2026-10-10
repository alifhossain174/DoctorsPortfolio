# Step 4 public verification

Verified 2026-10-10 against the initialized local `doctor_portfolio` database. All public primary content is server-rendered Blade, sourced from its assigned database owner. No migrations, seeding, owner edits, new dependencies or reference-file changes occurred in this step.

## Implemented pages and structural comparison

The audited pure template functions were isolated in a development-only QA script using the original initialization snapshot. The reference's draw()/event handlers were never invoked, and neither source functions nor snapshot input are used by Laravel at runtime. Compared those generated structures with HTML fetched through `php artisan serve`.

| Page type | Dynamic route | Original/dynamic structure nodes | Both locales |
| --- | --- | --- | --- |
| Home | / | 170 | Match; four cards, first three posts/gallery in source order |
| About | /about | 77 | Match |
| Services | /services | 80 | Match; five numbered blocks |
| Diagnostics | /diagnostics | 68 | Match; diagnostic caveat retained |
| Blog | /blog | 103 | Match; four initial published articles |
| Gallery | /gallery | 88 | Match; eight initial visible entries |
| Contact | /contact | 77 original-equivalent nodes | Match after documented form-control normalization |
| Article | /blog/pcos (all four slugs also HTTP-tested) | 65 | Match; editorial badge and two plain paragraphs |

Comparison checks element hierarchy, classes and inline styles, ignoring text/whitespace/attribute order. Normalization permits only language/filter button-to-anchor semantics, active-link state, the intentional contact GET mode selector/hidden CSRF/kind/locale inputs, and accessibility-only lightbox markup. It is not a pixel/layout measurement. No unexpected structural differences were found. Public baseline CSS is byte-identical to frontend/assets/style.css (SHA-256 `163874ac9dd4afe72b8b7635634b6c813c8a37d6fbdf76d61ae509c345a50f49`), including image dimensions, hover rules and 1050/850/550px breakpoints.

## Actual checks

- `php artisan test`: **16 tests passed, 260 assertions** in `doctor_portfolio_test`, rollback transactions only. Covers pages/articles in en/bn, database-backed edited content, fallback/escaped paragraphs, relation eager-loading, exact initial counts/order, filtering, legacy URLs, draft/future/missing 404s, locale/query validation, internal form redirects and paragraph-repeater escaping.
- `node --test tests/JavaScript/public-interactions.test.mjs`: **3 passed, 0 failed**. Dependency-free DOM doubles check observable menu, native filter navigation, GET mode switching and lightbox keyboard/pointer/focus/scroll/safe URL behavior. Real browser execution is not claimed.
- Actual HTTP at `http://127.0.0.1:8000`: **22 200 responses** (seven main pages + four article slugs × en/bn), **22 301 redirects** (seven .html pages + four article IDs × en/bn), **3 missing article 404s** and **5 category checks**. Blog clinical/wellness: 2 each. Gallery clinical/wellness/facility: 3/2/3. All page HTML excludes the original app.js and admin build assets.
- `npm run build` passed, producing admin-only `public/build/manifest.json`, `assets/admin-DNhfp8M4.css` and `assets/admin-CNeu1D7d.js`. These generated deployment artifacts remain ignored; build/deploy the complete directory. JS/PHP syntax checks, Blade compilation, Pint and git whitespace checks passed.
- Original eight HTML shells, app.js and CSS match all ten original audit hashes. Initialization snapshot checksum unchanged. Actual database still contains 165 website-content records plus one completion marker; users/inquiries remain zero. Test posts/users/inquiries/markers remain zero after rollback. No HTTP inquiry submissions were sent to the development database.
- Source/HTTP checks confirm primary content and category links are usable without JS; mobile no-JS navigation gets a noscript-only display rule. Lightbox enhancement requires JS. No actual JS-disabled browser was available.

Local QA artifacts are ignored under `.local-artifacts/step4/`: reference and dynamic HTML for all eight page types in both locales. They are development comparison files, not deployment input or runtime content.

## Intentional functional and wording differences

1. Language switching is a normal same-route `?locale=en|bn` GET link. It preserves validated category/page or contact mode and discards unsupported queries. Preference persists in session; a copied Bangla URL reproduces Bangla. Source localStorage redraw is removed. Empty/missing Bangla falls back to English through LocalizedContent; missing both renders a safe empty value. New simple Bangla literal UI translations from Step 3 remain database-owned.
2. Language and category controls are anchors with the original classes; interactions.css matches the source buttons' default Arial/normal line-height and adds keyboard focus outlines. There is no public reset or Tailwind. Category filtering queries all matching records on the server, rather than hiding only the currently loaded cards. No pagination is introduced; future pagination must use the filtered query.
3. Contact retains the original two-column form card, with the agreed small Appointment / General contact selector. Its separate GET submits on change with JS and has an Apply action without JS. Consultation choices appear only for appointments. Hidden locale/CSRF/kind, names/required phone and localized errors support Laravel requests; language/filter/mode forms never create inquiries.
4. Step 3 truthful contact heading, submit, privacy, success and shared CTA wording is retained exactly as database initialization content. Appointment requests are not confirmed visits. Facebook/Maps remain standalone links; Facebook is never a submit action/redirect or appointment CTA target. The original clipboard/prepared-message behavior is removed. This step adds no fake success: the existing handler saves before flashing success; full workflow completion remains Step 11.
5. Unknown/draft/future article URLs return 404. `article.html?id=...` maps only the requested published slug; the source's first-article fallback is removed. Original editorial badges are retained; initialization timestamps are never presented as original dates.
6. Lightbox focus trapping/restoration, inert background and scroll locking are accessibility/interaction additions. Escape, click outside and the original close control are retained. No lightbox redesign or new prev/next content feature was added.

## Browser limitation and manual visual checklist

The computer-use tool returned no browser surfaces. Opening both the in-app browser and Chrome returned **Browser is not available**. Consequently there are no reference/dynamic desktop/mobile screenshots, remote image/font visual verification, or pixel-perfect claims. Use the following checklist when a browser is available; retain identical sizes for each reference/dynamic pair.

- [ ] Desktop **1440×1000**, mobile **390×844**; also inspect **1050/850/550px** breakpoint boundaries. Open the unmodified frontend reference separately and Laravel routes; compare each page in English and Bangla. For original Bangla, use its own language button. Do not publish the reference app.js with Laravel.
- [ ] Topbar, sticky header, brand/subtitle, link gaps, English/বাংলা button size, footer columns/copyright and shared CTA match the source. Compare font loading, line wraps, colors, radii, shadows and vertical spacing; Bangla headings newly localized intentionally differ from English-only source literals.
- [ ] Home hero/portrait/floating card/stats, four care cards and six sections remain in original order; first-three article/gallery previews match the source. Confirm card/button hover and gallery image zoom behavior.
- [ ] About/diagnostics split images, five services blocks and notices, article feature image/category/badge/two paragraphs preserve source geometry. Article body contains escaped text only.
- [ ] Image dimensions remain hero **560/440/340px**, split **530px**, article card **220px**, gallery **260/250px**, feature **400px**. No fake replacement photos; identify any failed legacy image/font requests separately from CSS differences.
- [ ] Mobile menu opens/closes by pointer, Escape/outside and desktop resize; aria-expanded/labels track state. With JS disabled all navigation and server-rendered content remain available.
- [ ] Blog/gallery category links work in both locales and with JS disabled; selected state, empty state and copied query URL match results. Language switching keeps valid filters and future page parameters, discarding unknown values.
- [ ] Open gallery by click, Enter and Space. Check localized caption/alt, Escape/backdrop/close-button, Tab/Shift+Tab trapping, focus return, background inertness and restored scroll without unexpected movement.
- [ ] Contact mode switches its GET form, topic appears only for appointment, and original field/card styling remains. No false success on page load; verify errors and actual saved submission only against the isolated test database during workflow testing, never seed fake visitor records into development.
- [ ] Test all original .html URLs, each original article id, an unknown id and a draft article. Confirm validated locale in canonical redirects, no external redirects, no public Tailwind, source JS or content-array renderer.

The nine remote media URLs and Google Fonts import remain fragile external dependencies. Their current availability, identity and licensing were not re-verified here. Stock/medical/diagnostic caveats remain visible; no doctor facts, contacts, tests, credentials or photos were invented.
