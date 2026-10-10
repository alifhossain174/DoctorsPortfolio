# Inquiry submission — owner Step 11

Implemented and verified 2026-10-10. The existing contact card submits to named `inquiries.store`, `POST /inquiries`. No new public page, booking calendar, patient account, email requirement or notification service was added. The independent official Facebook and Maps links retain their original targets. The reference frontend and baseline public CSS remain unchanged.

## Form and validation

One compact `kind` selector lives inside the existing POST form. Its options and every visible label/help/error/status are database-owned `{en,bn}` values, edited through Website Settings → Contact form & feedback copy. English fallback uses the existing LocalizedContent service. No visitor text is translated or stored in bilingual duplicate fields.

| Field | Appointment request | General contact |
| --- | --- | --- |
| kind | appointment, required enum | contact, required enum |
| name | Required string, max 80 | Required string, max 80 |
| phone | Required string, max 40 | Required string, max 40 |
| consultation_option_id | Required existing active option | Excluded, even when submitted |
| message | Optional plain string, max 500 | Required plain string, max 500 |
| locale | Required en or bn; valid URL locale wins for stored/response context | Same |

Phone validation permits an initial `+`, Unicode decimal digits (including Bengali), spaces, parentheses, periods and hyphens; at least one digit is required. It preserves leading zeros, digits and separators instead of converting to a numeric/country-specific value. Laravel trims outer whitespace; internal message line breaks remain plain text. Malformed arrays, invalid modes/locales, oversized text and inactive/missing consultation options fail server validation. No public email input exists; forged email/status/reference/internal-notes values are outside the write whitelist.

Without JavaScript, the same selector can submit either kind immediately. The appointment-only control is present with an explanatory database-backed note and no unconditional HTML required constraint, so it cannot block General contact. Laravel requires it only for appointments and removes it entirely from General data. JavaScript enhances the form by hiding/disabling appointment-only controls for General, applying corresponding required flags, changing the button from rendered database attributes, and disabling an appointment submission when no active choices remain. It does not submit on mode change or discard typed text. Pending copy never claims success; back/forward restoration resets the pending button/status. All native POST/CSRF/idempotency behavior remains functional without JavaScript.

## Save and response boundary

StoreInquiryRequest whitelists fields and supplies localized validation. InquirySubmission synchronously commits inside a transaction, under the same website anchor lock as consultation edits. An active choice is checked again under the lock. Inquiry generates a unique ULID reference and new status; its appointment snapshot contains the stable key and both readable en/bn labels using shared fallback. General stores null consultation relation/key/label. Option rename/disable/delete never removes the inquiry or its snapshot. Names/messages are single submitted-language strings. Staff-only fields, PII, references and technical hashes remain hidden from ordinary model serialization.

After commit, the controller flashes database success copy and the reference only to the submitter's session, then redirects to `/contact?locale=en|bn&kind=appointment|contact`. There is no public reference lookup. Failures preserve only safe scalar name/phone/message/choice/kind inputs, with escaped Blade output and mode/locale restored; no token/CSRF/honeypot/private-field dump is flashed. PDO/save failures roll back and use `contact.save_failure`; SQL bindings, complete request bodies and secrets are not logged. A database outage that also prevents reading website content cannot render a database-backed site; the controlled save-failure test specifically verifies insertion rollback while content remains available.

Canonical contact query is `kind`. Invalid values safely select Appointment; a valid old `mode` query is accepted only when `kind` is absent, and switching produces canonical `kind` links. Appointment hero/shared/diagnostics CTAs explicitly link to the internal contact route with `kind=appointment` and selected locale. Navigation/social/filter/language/login behavior stays independent. Referer/redirect/action values supplied by visitors cannot select an external destination.

## Retry and abuse controls

- Each GET form receives a cryptographically random 64-character submission token distinct from CSRF. Only its SHA-256 hash/issue time is registered in the session. Up to 20 recent forms are supported; entries expire after two hours.
- The database has a nullable unique `submission_token_hash` (older records remain compatible) and private `submission_fingerprint`. The HMAC fingerprints the normalized validated payload using its random token, without a guessable plain hash of PII. The token remains session-recognizable for identical retry acknowledgment, but can claim only one database row. An identical retry returns the existing acknowledgment/reference; changed data with that token is rejected without claiming it was saved. A new deliberate request requires a new form token.
- `company_website` is a hidden, non-focusable honeypot; nonempty/malformed values fail with honest localized feedback, never fake success.
- Named `throttle:inquiries` permits 10 POST attempts/minute per hashed IP key. Runtime `CACHE_STORE=file` makes it shared across synchronous requests without Redis. Invalid attempts also count. Rate responses retain Laravel retry headers and use same-site PRG/database copy rather than a misleading success.
- CSRF remains Laravel's web middleware. Inquiry-only 419 handling returns the same internal form with localized expiry feedback and safe old input; admin CSRF handling is unchanged.

No queue, email notification, SMTP, CAPTCHA service, runtime LLM or external submission dependency exists. Database storage and the upcoming private inbox are the staff notification mechanism. An appointment request never confirms a visit.

## Initialization and owner updates

Run `php artisan migrate` then `php artisan db:seed` explicitly when installing/updating this step. Migration 000005 only adds nullable guard columns/unique index. DatabaseSeeder keeps the original importer and calls InquiryFormContentSeeder for five new feedback/help fields from UTF-8 `database/seeders/data/inquiry-form.json`. Its separate completion marker is `inquiry-form-v1`; create-missing-only writes occur in a transaction and mark completion after success. Later normal seeds skip this supplement, preserve owner edits and do not restore intentional deletions. The original snapshot, 81 source destinations, truthful owner-specified submit/privacy/success/CTA values, publication and visibility remain untouched. No boot/request/login/deploy auto-seeding exists. The original repair command concerns original demo content only; it does not silently restore this supplement.

The obsolete `contact.mode_apply` copy remains stored/editable for source/history compatibility, but its former GET Apply control is retired. The two POST modes need no separate switching request.

## Actual verification and next integration

`php artisan test --compact`: **104 passed / 2,496 assertions, 66.61 s**. The ten new InquirySubmissionTest cases cover both real database modes/snapshots; actual web middleware with CSRF enabled; same-site PRG after save and identical retry; source limits/required mode rules/invalid types/inactive options; Bengali/line breaks/plain markup; option deletion snapshot retention; hidden-field whitelists; session-bound/expired/changed/unique tokens; honeypot; file-cache rate limit; late insertion failure rollback and successful recovery; direct no-JS selector operation; database-edited feedback; repeat import preserving edit/deletion. Other suites cover auth, relationships, public routes, CMS fields and original content. Feature fixtures use only doctor_portfolio_test and roll back.

A separate actual localhost PHP HTTP server used guarded doctor_portfolio_test, real file sessions and CSRF, without executing JavaScript. **Six POSTs produced six same-site 302 redirects**: appointment, identical appointment retry, invalid General message, valid General contact, invalid CSRF and honeypot. An independent database connection observed exactly **two durable committed rows: one appointment and one contact**, with Bengali strings, new status and correct/null snapshots. Invalid/CSRF/honeypot/retry created no extra rows. The server was stopped and only its tracked fixture IDs/token hashes were removed; all isolated content/inquiry/user/marker/history tables returned to zero. No reset, foreign-key bypass or development inquiry was used.

All nine JavaScript interaction checks passed after the final back/forward-status enhancement. DOM doubles verify mode visibility/required/button behavior and native submission, and assert no clipboard/window.open/Facebook submission handler. Admin asset build passed (unchanged 34.15 kB CSS / 6.94 kB JS). Sixteen HTTP page/locale structures match the saved reference after explicitly normalizing the intentional selector/help/technical controls; this is not a screenshot or pixel claim. All ten reference hashes, public baseline CSS and original snapshot checksum match. Development has **170 content rows + two markers**, **zero inquiries**, nine external/zero local media and one unchanged existing owner user. Both dedicated databases have migration 000005 applied.

**Step 12 is implemented and verified:** protected /admin/appointments and /admin/contact-messages use the same Inquiry rows with enforced kind scopes, read-only GET, explicit handling/history and confirmed local deletion. Actual localhost en/bn × appointment/contact submissions created exactly four durable test rows; authenticated staff details displayed their exact fields, opposite-kind/guest access was denied, and read/notes/handler/history persisted after refresh. Scoped fixtures/random test account were removed. See [ADMIN_INQUIRIES](ADMIN_INQUIRIES.md) for usage/privacy/time/transition rules and IMPLEMENTATION_STATUS for exact results. No application/database blocker remains for Step 11. Browser viewport/device screenshots remain unavailable; manual checks are 1190px desktop/390px mobile contact card spacing, selector and error wrapping, keyboard form navigation, no-JS mode changes, pending/back behavior, locale/error recovery and independent Facebook click. Original nine external images/fonts remain network dependencies.


Step 12 strengthens the existing submission lifecycle with a bounded personal-data-free saved-token session receipt: after staff permanently delete an active inquiry, the same used form token is rejected instead of resurrecting it or falsely claiming persistence. Existing live-row identical retry remains idempotent. Contact/POST response caches are private/no-store; staff routes have noindex/no-store/no-referrer and sensitive error/log protection. No submit-copy, external redirect, notification, public lookup or secondary inbox was introduced.
