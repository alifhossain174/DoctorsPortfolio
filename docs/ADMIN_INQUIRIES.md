# Private inquiry inboxes — owner Step 12

Appointments and Contact Messages use the existing Inquiry rows saved by POST /inquiries. No parallel inbox, public patient API, export, notification service or CRM was added. Public content/design/source is unchanged. This module uses the existing Blade/Tailwind components and vanilla sidebar/dialog behavior; it adds no framework/package or runtime worker.

## Staff usage

Open `/admin/appointments` for appointment requests or `/admin/contact-messages` for General contact. Both lists show reference, submitted name/phone, status and received time; only appointments show the readable consultation snapshot. “Open request” links use an internal numeric database ID, keeping names, phones and references out of URLs. Each inbox scopes every list, detail, save, Mark read and delete query by kind. An ID belonging to the other kind returns 404.

Search accepts a literal substring of name/reference/phone. Search, status and inclusive received-date filters submit a CSRF-protected POST and live only in the authenticated file session, separately for each inbox. Pagination contains only `page`; search terms are never appended to links. Reset clears that inbox's filter session. Invalid filters preserve the previous valid filter. `%`, `_` and `!` search as literal characters, with bound SQL parameters. Dates and displayed timestamps use Asia/Dhaka. The code limit is 20 rows/page, with newest received/ID first; filtering precedes pagination.

Details show the actual submitted fields, submitted locale, received time, original stable consultation key/both localized snapshot labels where applicable, staff handling and history. Submitted personal data is read-only. English/Bangla messages remain single plain strings with their original internal line breaks; no translation is invented. All Blade output is escaped. Missing Bangla in an option snapshot uses the existing LocalizedContent English fallback.

Opening any list/detail/dashboard performs no inquiry write. Use “Mark read” on a New request, or select an available status and “Save handling”. Private internal notes accept up to 15,000 characters; an explicit blank clears them, and a Mark read action preserves them. A real change records the authenticated handler and handled_at. No-op saves add no history or handler change. Concurrent/stale forms are rejected using a server HMAC version over current handling fields, checked again under a transaction row lock. Reload and review before retrying a stale save/deletion.

## Status rules

The selected current status is always available for notes-only saves. Other choices follow these code-owned transitions in config/inquiries.php; clients cannot override them.

| Current | Appointment next statuses | General contact next statuses |
| --- | --- | --- |
| new | read, confirmed, cancelled, spam | read, completed, spam |
| read | new, confirmed, completed, cancelled, spam | new, completed, spam |
| confirmed | read, completed, cancelled, spam | Not applicable |
| completed | read, spam | read, spam |
| cancelled | read, spam | Not applicable |
| spam | read | read |

Changing to confirmed/cancelled/spam or reopening from completed/cancelled/spam requires the explicit status-confirmation checkbox. Confirm only after staff have agreed the visit with the person. General has no confirmed/cancelled status or visit-time control, and server/model validation rejects those values even if forged. An appointment confirmation is a private staff record; it is never announced publicly and sends no email/Facebook notification.

The manually confirmed appointment time is optional, in Asia/Dhaka. It may be stored only with confirmed/completed/cancelled status, must parse exactly as a local date/time, follow the received timestamp, and not exceed year 2100. Past times relative to now are allowed for recording actual completed history, provided they follow receipt. Returning to an unconfirmed state requires clearing that field. This is a manual record, not a calendar, availability system or confirmed public booking.

A small inquiry_status_changes table records from/to status, actor ID and change timestamp atomically with the actual update. It does not duplicate patient text or private notes. Initial New is established by the inquiry's received time; notes-only saves update the last handler/time without fabricating status events. A removed staff account leaves a nullable actor and the UI says “Staff account removed”.

## Deletion, privacy and failures

Permanent deletion requires the existing accessible delete dialog or no-JS confirmation checkbox, valid CSRF and a current record version. It deletes only the active Inquiry and its local status history. It does not delete an option, content, media, account or unrelated inquiry. Existing backups are not changed or claimed erased. Option deletion continues to null the relation while preserving the readable inquiry snapshot; no content edit cascade deletes an inquiry.

The submitter's existing token session now keeps a bounded boolean saved receipt without personal data. If staff delete that active record, the same already-used form token is rejected rather than recreating it or reporting a false saved success. Existing identical retries still acknowledge the existing row. Receipts are pruned with the 20-token/two-hour registry; this is not a second inbox or PII archive. A new deliberate request uses a fresh form.

Global PrivateResponse middleware runs before auth/binding/validation. Every `/admin` response, including login/redirects/403/404/419/500, is private/no-store with noindex/nofollow/nosnippet and no-referrer; admin layouts also have a robots meta tag. Inquiry POST and public contact/error-restoration responses have no-store protection. Debug rendering is disabled on these sensitive paths even with local APP_DEBUG=true. Exception reporting for private paths logs only a generic event and exception class, without message/SQL/body/notes; automatic sensitive flashing is excluded, while the specific inbox/public handlers restore only validated-purpose scalar fields into the private session. Details use a generic page title and numeric URL rather than patient data. There are no analytics scripts, public caches or public reference lookup.

Do not share screenshots containing real submissions, notes or credentials. Use the empty dashboard or disposable isolated data for reviews. The workspace is private; ordinary access logs should not be augmented with bodies or session/search data. Staff-only personal data is intentionally visible to authenticated admins inside the protected inbox, never in public listings.

Server writes whitelist status/internal_notes/optional appointment time/version/confirmation. Name/phone/message/email/kind/locale/snapshot/handler overrides are prohibited or excluded. Auth, explicit admin Gate/middleware and auth.session guard every action. Manager authorization and fresh kind checks provide a second boundary. Status/history/notes/handler writes commit together; a PDO failure rolls everything back and shows a generic friendly error with safe private editing input. No raw SQL/exception text is shown. Deletion failure reports failure rather than success.

## Routes, setup and dashboard

Each inbox has six named admin routes: GET index, POST filter, GET `{inquiry}` detail, PATCH `{inquiry}` handling, PATCH `{inquiry}/read`, DELETE `{inquiry}`. No bulk actions/export/public endpoint exist. Sidebar entries now activate both implemented destinations. Public mode/filter/language forms continue their own purposes and never write staff data.

On an existing installation run `php artisan migrate` and `npm run build`; no reseed or new owner credential is needed. Migration 000006 adds only the indexed status-history table with inquiry FK and nullable user FK. The dedicated local and test databases have this migration applied. The account remains the existing securely provisioned owner account; implementation tests create disposable random credentials only in doctor_portfolio_test, never a default production account.

The finished dashboard queries actual New appointment/New General counts, currently published articles (including request-time timestamp rule), visible gallery images and the six most recent saved inquiries with their correctly scoped detail links. Quick links open settings/pages/articles/gallery/media and public English/Bangla preview. Responsive Tailwind cards, horizontal table scrolling, empty states and existing sidebar provide desktop/mobile navigation. No fabricated totals, example patients, charts or claimed activity appear.

Verification results are recorded in IMPLEMENTATION_STATUS. The acceptance suite writes real guarded MySQL test fields, not JavaScript fixtures: guest/non-admin and all cross-kind routes, both locale/kind public submissions → private details, explicit read/no GET mutation, persistent notes/history/handler, transition/confirmation/time rules, session search/literal matching/status/date pagination/reset, stale forms, deletion/local relationships, real dashboard counts, CSRF, escaping, no public endpoint, privacy/error/log handling and atomic failure. Source hashes/public DOM/CSS remain the prior baseline. Browser visual captures have not been claimed; manual review should check 390px/1190px viewport tables/sidebar, keyboard filter/reset/status/date controls, no-JS confirmation, long Bengali message/note wrapping, stale-edit errors and private-only headers. Use disposable data for that review.
