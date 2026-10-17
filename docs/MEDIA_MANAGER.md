# Media manager — Step 6

## Current simplified workflow — 2026-10-17

In Website Settings, Page Content, Articles or Gallery, use **Upload from device**, choose a JPEG/PNG/WebP, wait for **Image uploaded and selected**, then save that content. The current editor stays open and its unsaved translations are retained. A pending upload blocks form saving; validation/session/server-size failures appear beside the file input and leave the previous image selected. The preview explains unavailable external images. Existing library selection and Browse library remain alternatives. Without JavaScript, upload in Media first, then reopen the content editor and choose from its native list.

Uploads use the existing protected POST /admin/media with CSRF, authorization, raster/size/dimension rules, disk cleanup and random local filenames. An Accept: application/json request receives only id/url/title/alt with HTTP201 after persistence. `quick_upload=1` supplies a filename-based English library title when blank; it does not infer image alt, caption, provenance or authenticity. Explicit bilingual metadata is still validated and editable. Uploading does not replace content until its normal save (or explicit confirmed replace-all). Unused uploads remain in Media if the editor is abandoned.

The Media upload page now requires only the file; image name is optional and descriptions/source notes are expandable. Image details explain that original demo addresses were imported into database records, identify the external host, and distinguish local uploads whose addresses are generated automatically. The signed Facebook CDN address comes from the original demo hero URL documented in FRONTEND_AUDIT; no download occurs at runtime. **Image address (advanced)** contains optional URL copying. For in-use images, **Change image everywhere it is used** accepts an inline upload or an existing image and retains explicit confirmation/all-six-owner replacement protection. Existing per-slot descriptions and stock caveats are not overwritten.

This change does not alter the public design, frontend reference, database schema, imported source URLs, hosting rules or release archives. Build output is regenerated locally; a future deployment needs the rebuilt assets and updated application files.

Verification: 52 focused PHP tests / 1,392 assertions on guarded doctor_portfolio_testing with DML rollback and fake disk cleanup; 10 admin JavaScript interaction checks; admin build, targeted Pint and whitespace checks passed. File-only JSON and redirect uploads, authorization, malformed/unsafe files, unchanged references until content save, shared-image preservation, pending-save blocking, CSRF payload, error/session/size handling and network retry are covered. The compiled-view Windows rename access error was cleared with view:clear/view:cache before successful rendering checks. No connected browser was available for visual QA; no owner database or release archive was changed.

Current owner Step 10: Gallery CRUD reuses the shared picker/upload manager. Validated picker context now persists through upload/details so Choose image can return a new raster without discarding unsaved gallery text. Gallery entry removal retains media/files; usage still includes hidden entries. See [GALLERY_CMS](GALLERY_CMS.md).


Current owner Step 9: Post OG media is now the sixth ownership path; library usage/counts, delete protection and atomic replace-all include it. Category/article forms reuse the existing picker, with no remote download service or image substitution. See [EDITORIAL_CMS](EDITORIAL_CMS.md). Older five-path statements below describe Step 7.


Implemented 2026-10-10. Standard protected Laravel controllers/FormRequests/Blade, locally compiled admin Tailwind and small vanilla enhancements. No new packages, queue, thumbnails, remote downloader, storage link or runtime Node service.

## Library and ownership

Step 7 extends usage to a fifth owner: `Page.og_media_asset_id` / `Page::ogImage` / `MediaAsset::pages`. Library usage counts/details, unused-delete protection and atomic reference replacement include these records, even though public OG tags are deferred to Step 13. Shared settings/page editors now use this same picker for hero/page/OG image references. Slot-specific alt text remains with its existing owner.

Media is active in the sidebar at `/admin/media`. It provides 24-image pagination, title search (English/Bangla), local/external filters, original-ratio previews, upload, bilingual descriptions, public URL copying, usage list and unused deletion. Dashboard availability now identifies Media as implemented. Protected resource routes use `auth`, `admin`, `auth.session` and the inherited AdminController guard; writes use CSRF and normal POST/PUT/PATCH/DELETE with internal redirects.

`MediaAsset` stores localized `title`, `alt_text`, **caption** (new nullable JSON column), `provenance_note`, `stock_note`, plus code-owned identity/source/disk/path/MIME/size/dimensions/original-filename metadata. Registry definitions are in `config/media_fields.php`, referenced by `config/cms_models.php`. Required English title, optional Bangla fallback and all language-key/length validation reuse ContentRegistry and AdminContentWriter. English-only updates retain existing Bangla. Unknown technical fields, raw paths and source URLs cannot be changed by an editor request. External URLs are retained from explicit initialization; this manager cannot add arbitrary remote fetch targets.

Media captions/alt are defaults available to later content editors. Existing Post.image_alt, GalleryImage.caption/alt_text and named page alt fields keep their own ownership and exact demo values; library edits do not overwrite those fields. Public templates and lightbox already call the same `MediaAsset::url()` as admin previews. It returns an approved HTTP(S) external URL or the configured public-media disk URL for a strict local raster path; unsafe paths/disks/URL schemes return an empty safe state. Public CSS/classes/ratios and all frontend reference files remain unchanged.

## Configurable local storage, without symlinks

`config/filesystems.php` defines the **public-media** local disk:

- Default root: `public_path('uploads')` (here `E:/xampp8.2/htdocs/DoctorPortfolio/public/uploads`).
- Default URL: `APP_URL` + `/uploads`.
- Override with absolute `PUBLIC_MEDIA_ROOT` and full HTTP(S) `PUBLIC_MEDIA_URL` in private `.env` for a split cPanel layout. Do not point this root at frontend, private storage or a broad web/application directory.
- Files: `images/YYYY/MM/{32 random hexadecimal characters}.jpg|png|webp`. JPEG is normalized to `.jpg`; client names are display metadata only. No client directory/path is used.
- Exceptions are enabled so failed writes cannot silently become successful uploads. Private sessions, cache, inquiries and other private data stay off this disk.

For example, on the actual host replace placeholders with its real account/domain paths:

```dotenv
PUBLIC_MEDIA_ROOT=/home/account/public_html/uploads
PUBLIC_MEDIA_URL=https://your-domain.example/uploads
```

Deploy the tracked `public/uploads/.htaccess` into that exact configured root, create only that upload directory and make it writable by the site's PHP user. Keep the application, frontend, .env and private storage outside public_html. The first successful upload installs the code-owned rules if the destination lacks them; it does not overwrite an existing host configuration. Check an existing rule file against the supplied policy before enabling uploads. After changing private environment/configuration run `php artisan config:clear` (or rebuild the deployment's config cache). No `storage:link` is needed. Keep the entire generated public/build manifest/assets directory in the deployment alongside public CSS/JS.

Application limits are 5 MiB (5120 KiB), 6000 pixels maximum edge, and 24 million total pixels (`config/media.php`). On cPanel ensure PHP upload_max_filesize allows at least 5M and post_max_size has room for image plus form fields (e.g. 8M or higher); the application's 5 MiB limit still applies. Local PHP reports 40M/40M; global PHP configuration was not changed. Very large requests can be rejected by PHP/web-server limits before Laravel receives a file.

## Validation and Apache policy

Only JPEG, PNG and WebP are accepted. Server checks original extension, actual file bytes via fileinfo, JPEG/PNG/RIFF-WEBP signature, getimagesize image type/dimensions, byte size and pixel limits. SVG/GIF/HTML/PHP, extension/MIME mismatches, truncated fake signatures and oversized images fail validation. Stored filenames never retain double extensions or user paths. No upload text is evaluated or inserted as raw HTML. GD was used only to generate QA fixtures; production validation uses fileinfo/getimagesize, without a GD processing or thumbnail dependency.

The upload `.htaccess` targets **Apache 2.4**, including cPanel PHP-FPM setups. It disables indexes, CGI, SSI and MultiViews, rejects path-info, explicitly selects the static default handler, removes executable handlers/types, denies every filename except generated raster names, denies deletion quarantine paths, forces the three image MIME types and adds nosniff when mod_headers is available. It does not rely on mod_php-only php_flag directives. These directives follow [Apache core handler/FilesMatch/Options documentation](https://httpd.apache.org/docs/2.4/mod/core.html), [mod_mime handler documentation](https://httpd.apache.org/docs/2.4/mod/mod_mime.html), and Laravel's [local filesystem](https://laravel.com/docs/12.x/filesystem) and [file validation](https://laravel.com/docs/12.x/validation) conventions.

The host must honor `.htaccess` with the corresponding Options, FileInfo and AuthConfig overrides (or install equivalent virtual-host rules), mod_mime/mod_authz_core and preferably mod_headers. Laravel's `php artisan serve` is a local development server and does not apply Apache rules; file/type validation and generated raster filenames still apply. On a cPanel Apache/LiteSpeed host, validate the actual configuration before opening production uploads; nginx requires equivalent server rules. cPanel infrastructure has not been accessed or claimed verified here.

## Picking and replacement

`x-admin.media-picker` receives `name`, `label`, `selected`, `assets`, and optional `required`. Later controllers supply the existing choices (loaded once, no queries in Blade loops) and independently validate the chosen FK with `exists:media_assets,id`. Its native select submits with the ordinary parent form and works without JavaScript. Pass the current asset even if it is not included in the supplied choices. Clearing an optional selection changes only the reference when the form is saved.

The Browse library link opens the paginated `?picker=1` library. A small same-origin popup message chooses a record and previews it without submitting the parent form. The receiver checks both origin and the exact opened window, ID/type and safe HTTP(S) URL, and uses text-only DOM options. Untrusted popup messages cannot choose an image. Blocked popups retain the ordinary link. With JavaScript disabled, use the native image list; no SPA/API content renderer is involved.

An in-use image's detail screen lists **every** Post, GalleryImage, ContentField and SiteSetting portrait reference, including hidden/draft content. These are the current four media FK owners. Future media-bearing models must extend the usage, deletion and replacement checks together. Until those content editors exist, usage records are plain labels/stable IDs rather than dead editor links.

“Replace all references” requires another existing valid asset plus explicit confirmation. It locks both media records and changes all four owners' FKs in one database transaction, leaving both media records/files intact. Missing local target files and identical source/target IDs are rejected. Review every affected slot after replacing; it deliberately does not rewrite localized slot-specific caption/alt or fabricate the replacement's identity. An individual content-screen reference removal also never deletes shared bytes.

## Deletion and failures

Deletion requires explicit consent and repeats usage checks under a database row lock. In-use assets are blocked. Unused external records remove only the database row; no remote request/delete is performed. An unused local file is moved to `.deleting/{random}.extension` while its row is deleted transactionally; database failure restores the file, and commit is followed by synchronous removal of the temporary file. Apache denies that quarantine path.

Upload/database failure removes the newly generated file and reports a friendly error. Failed storage staging prevents database deletion. Reference-update failure rolls back earlier FK updates. Post-commit quarantine cleanup failure reports that the record was removed but a temporary image needs cleanup; it does not falsely claim all bytes were removed. File and SQL transactions cannot form one distributed transaction: simultaneous storage/restoration failure or a process crash can need manual recovery. Sanitized log entries record controlled relative paths only, without credentials/inquiry data. Hosting support should inspect the indicated `.deleting` file and current media rows, restore a retained row's missing original file or remove an orphan after verifying it has no row/reference; never perform a blanket upload/database reset. No queue/scheduler is required for recovery.

## Original external assets and verification

Development currently contains **9 external image URL records and 0 local media records**. No original asset was downloaded or substituted during Step 6. The initial Facebook CDN hero remains signed/fragile, eight Unsplash photos remain external stock images; identity/permissions and current remote availability are unverified in this step. Existing stock/diagnostic/medical caveats remain. Public CSS still imports Google Fonts CSS (DM Sans, Manrope, Noto Sans Bengali), with fonts.gstatic.com supplying binaries. Admin uses system fonts and local compiled assets. Maps/Facebook remain ordinary outbound links. No bundled verified doctor/clinic photo set, logo/favicon or font files was invented.

Verification: full PHP suite **48 tests / 675 assertions** passed, then the final focused media suite rechecked the safe-URL/readability refinements (final counts in IMPLEMENTATION_STATUS). Fifteen media tests cover permitted formats, signatures/MIME/extensions, size/dimension/path/language rejection, all guest/non-admin endpoints, omitted Bangla, pagination/filters, all four owners, shared-file preservation, URL/lightbox consistency and injected filesystem/database/recovery failures. Eight JS tests passed including popup origin/source checks and clipboard fallback. Assets built locally; public/source/seed/dependency files remain unchanged.

**12 actual XAMPP Apache 2.4.58 checks passed** with temporary GD raster fixtures: JPEG/PNG/WebP 200 with exact MIME; PHP/double-extension/HTML/SVG 403; directory/root listing and .htaccess 403; quarantine image 403; image path-info 404; all responses had nosniff. Fixtures were removed; Apache/global configuration/services were not changed. A separate live Artisan HTTP check returned the exact PNG bytes from `/uploads` with 200/image/png while public/storage did not exist. These QA files were never seeded/local-media records. Actual migrations applied non-destructively to doctor_portfolio and its isolated test schema; no resets, demo reference changes, new owner accounts or production uploads.

Manual visual checks remain: desktop/mobile library grid, original-ratio previews, filter pagination, bilingual tabs/errors, library popup/selection with and without JS, long URL copying, usage list/confirmation and local image display in all affected public slots. Authenticated route rendering was tested with rolled-back test admins, not owner credentials; no browser screenshots are claimed.
