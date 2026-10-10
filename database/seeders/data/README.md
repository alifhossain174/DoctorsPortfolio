# Initialization input

`initial-website.json` is verified UTF-8 input for the **explicit** InitialWebsiteContentSeeder. It contains the full original bilingual source data, its SHA-256 provenance, all 81 destination mappings, deterministic records, new literal translations and intentional inquiry/CTA wording changes.

First setup: `php artisan migrate`, then `php artisan db:seed`. Completion commits with all imported records. Normal repeat seeding skips the completed website import, preserving edits, publication/visibility and deliberately deleted originals. It creates no accounts/passwords or fake inquiries.

Only deliberately restore missing original demo rows with `php artisan website:repair-initial-content`. Repair preserves all existing rows, checks the original snapshot checksum and restores missing rows in their initial state. Do not edit this snapshot as a way to update an initialized website; use future admin controls. No runtime controller/view/public script may read it or fall back to it. Production initialization requires neither frontend/ nor Node.

See [SEED_CONTENT_REPORT](../../../docs/SEED_CONTENT_REPORT.md) for actual local import counts, all source destinations, wording changes and unresolved legacy external media.
