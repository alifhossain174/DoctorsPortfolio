# Category and article editors — owner Step 9

Implemented 2026-10-10 using the existing registry, LocalizedContent, atomic localized writer, Blade controls, paragraph repeater and media picker. All writes use authenticated admin routes, authorization, CSRF and server-side whitelists. No framework, Markdown/rich-text package, worker, seed-on-request or runtime source read was added. See IMPLEMENTATION_STATUS for exact verification results and CONTENT_MATRIX for individual controls.

## Categories

`/admin/categories` provides searchable, applicability-filtered, paginated CRUD. Category fields are `slug`, `label {en,bn}`, `sort_order`, `for_blog`, `for_gallery`. The unique lowercase slug is chosen explicitly at creation and immutable afterward; changing its label does not change URLs. Numeric order is 0–65535, ascending, with ID as a deterministic tie-breaker. At least one applicability must remain enabled.

Original clinical/wellness categories apply to articles and gallery; facility applies only to gallery. Pregnancy-care and menopause belong to wellness; pcos and fertility-first-visit belong to clinical. Existing gallery counts remain facility 3, clinical 3, wellness 2. These are categories despite the source's “Browse by tag” wording; no tagging subsystem exists.

Usage counts include drafts and hidden gallery entries. In-use applicability cannot be removed and in-use categories cannot be deleted. The edit screen offers explicit, confirmed reassignment to another appropriate article category and/or gallery category. Both collections move in one transaction; an invalid destination rolls back every move. Reassignment does not delete the old category. Delete is available only after it becomes unused and requires confirmation. Foreign-key restrictions also protect content.

## Articles and bilingual paragraphs

`/admin/articles` provides search, category and draft/public/future filters before private pagination. Create/edit fields are slug, title, excerpt, body, category, editorial badge, featured image, image alt, status, homepage flag, numeric order, publication timestamp, localized meta title/description and OG image. All localized fields use the same `{en,bn}` format. Metadata limits are 200/500 characters; OG images have a nullable media FK and usage protection.

Body is `{en: [plain strings], bn: [plain strings]}`. Each language has independent add/remove/up/down paragraph controls; both remain in one form. Blank paragraphs are removed, text is trimmed safely, and nested values/unsupported language keys are rejected. English requires at least one paragraph, at most 100, each at most 15,000 characters. Bangla may be null/empty and uses the shared English fallback. Omitted languages are retained when saving partial updates. An explicitly cleared Bangla body falls back to English. Tabs preserve unsaved text. All paragraphs, titles, badges, captions and metadata are escaped; script-looking text is harmless displayed text, never executed or silently converted to rich HTML.

The listing shows missing-Bangla indicators for meaningful English fields; the editor explains fallback and shows both saved translations. Changing a title never changes its slug. Slugs allow lowercase letters/numbers separated by hyphens and are unique across current articles and other articles' reserved old slugs. An explicit edit of an existing slug requires confirmation. `post_slug_redirects` retains old identities pointing to the article record, so repeated renames redirect directly to its current slug without chains. Returning to the same article's own old slug is allowed. Deleting an article removes its redirect rows but retains shared media records/files.

Publication status and timestamp are separate from the original topic badge such as PREGNANCY. Publishing without a timestamp uses now; an existing timestamp is retained. Published records with a future timestamp remain private until the request-time query permits them. Unpublish changes status to draft without discarding content or timestamp. No scheduled task or worker is involved. Initial original timestamps remain initialization time, never asserted historical publication dates or displayed as the badge.

## Public URLs, filters and preview

- `/blog` filters applicable database categories before pagination, then sorts by order/ID. Default public page size is 9; private lists use 20, defined in `config/editorial.php`. Public numbered links reuse `.filters`, `.filter`, `.selected`, spacing and other original classes; pagination appears only when required. No admin CSS is loaded.
- A supplied invalid/non-article/array/empty blog category returns 404 instead of silently selecting unrelated results. `all` and applicable database slugs are accepted. Only validated category/page/locale parameters propagate. Existing gallery invalid-filter handling remains unchanged. Filter links reset pagination; locale links retain a valid page/category.
- Home uses its database count (initially 3), homepage flag, public scope and configured order. Draft/future articles are excluded. Original first three remain pregnancy-care, pcos, fertility-first-visit.
- `/blog/{slug}` and `article.html?id={slug}` resolve a current article or its retained identity only if currently public. Old identities redirect permanently to the canonical current slug with validated locale. Missing, draft and future targets return 404; there is no first-article fallback. Article category links select the corresponding blog filter.
- `/admin/articles/{id}/preview?locale=en|bn` renders the actual public template for saved content, including drafts/future records, only for authenticated admins. A private banner links back to editing. Responses carry `X-Robots-Tag: noindex, nofollow, nosnippet`, a robots meta tag and `Cache-Control: private, no-store`. Guests/non-admins cannot view it. Saving is required before previewing edits; no temporary public preview token exists.
- Article meta title resolves localized Post metadata → localized article title in the shared database title format. Description retains Post metadata → page metadata → excerpt/shared safe fallback. Remaining public OG/SEO output is reserved for Step 13; Post OG selection is already stored and protected.

## Schema, import and media boundaries

Additive migration `2026_10_10_000004_add_article_og_image_and_slug_redirects` adds the nullable Post OG FK and unique old-slug table. It ran on both dedicated local databases. Existing source content, category flags, publication decisions and owner credentials were not changed or reseeded.

Media resolution stays in MediaAsset::url for public images, lightbox and admin previews. Post featured and OG references are separate; the library usage list, delete protection and explicit replace-all now cover both, alongside gallery, content fields, settings and Page OG references (six ownership paths). No image bytes are downloaded or fabricated. All nine initial assets remain legacy external URLs; Google Fonts remains external. Actual cPanel overrides and remote availability/provenance still require deployment review.

Normal `php artisan db:seed` skips the completed website marker, preserving edits and intentional deletions. Explicit `php artisan website:repair-initial-content` remains the only original-record repair operation; it recognizes a renamed original via its retained slug identity and does not recreate/republish it. This is initialization-only snapshot input, never a runtime content fallback. No default credentials or fake inquiries are added.

## Routes and manual review

Categories use the standard resource routes except show, plus PUT `/{category}/reassign` (seven endpoints). Articles use the standard resource routes except show, plus PATCH `/{post}/publication` and GET `/{post}/preview` (eight endpoints). Every endpoint is private, including preview. Dashboard/sidebar/contextual page links now activate Categories and Articles; pending Gallery/inbox modules remain truthful non-links.

Automated tests use real MySQL/MariaDB fields inside guarded `doctor_portfolio_test` rollback transactions. They cover bilingual independent bodies/fallback, escaping, stable and redirected slugs, repair preservation, applicability/reassignment/delete safety, media/OG usage, atomic failure rollback, all private endpoints, metadata, publication boundaries, multi-page category filters, homepage ordering and all four originals. Source hashes, database inventory and normalized public HTTP markup are checked separately without owner credentials.

Browser screenshots/pixel-level checks were not performed. Manual review remains: desktop/mobile sidebar and tables; keyboard EN/বাংলা tabs; independent paragraph add/remove/reorder and unsaved-text retention; media picker and delete dialogs; preview both locales; public article images/badges/body/caveats; blog filters and numbered pages with JavaScript disabled. Initial four-article blog has no pagination bar. Windows's existing compiled-Blade atomic replacement limitation remains documented; local verification refreshes compiled project views in place without changing vendor code/global permissions.
