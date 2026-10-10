# Initial website content report

Step 3, verified 2026-10-10. The non-destructive migrations and explicit import **actually ran** against **doctor_portfolio**, **10.4.32-MariaDB**. Connection charset **utf8mb4**, collation **utf8mb4_unicode_ci**. No SQLite substitution, database reset, fixed credentials, or fake inquiries.

## Actual database inventory

| Table | Actual records | Initial state |
| --- | ---: | --- |
| media_assets | 9 | 9 legacy external image URLs; no fabricated local files |
| site_settings | 1 | Database-owned bilingual content |
| pages | 8 | Database-owned bilingual content |
| content_fields | 88 | Database-owned bilingual content |
| page_sections | 20 | 20 visible in original fixed order |
| navigation_items | 11 | 7 header + 4 footer, all visible |
| care_cards | 4 | 4 enabled |
| services | 5 | 5 enabled, independent of care cards |
| consultation_options | 4 | 4 active original form choices |
| categories | 3 | clinical/wellness for blog and gallery; facility for gallery |
| posts | 4 | 4 published; first 3 on homepage; 2 original paragraphs per locale each |
| gallery_images | 8 | 8 visible; first 3 on homepage |
| users | 0 | No default account/password; interactive owner provisioning belongs to Step 5 |
| inquiries | 0 | No development/production test submissions |
| content_initializations | 1 | 1 completed import marker; normal db:seed skips website import |

**165 content records**, plus one completion marker. Verified **266 localized JSON pairs** against the snapshot, with non-empty English and Bangla. All 16 original article paragraph values, service bodies, gallery captions, card copy, image URLs and link targets are included. Optional unknown upload metadata remains null.

Import completed_at: **2026-10-10 18:36:12 Asia/Dhaka**. Post published_at uses that initialization time; it is **not an original article date** and is not rendered in place of the editorial badge.

Snapshot: `database/seeders/data/initial-website.json`, valid UTF-8, SHA-256 `f9b83ae17b0c94d661188b31833d585e2dd9a246871c2110eddb40d62742b9d8`. Source app.js SHA-256 `41c24a8f4166a8b41103d947eba897405dd8f63b49dc5d4d7725ed40bcbe1611`. A development-only literal parser extracted data without running app.js, page renderers or event handlers. The snapshot includes original values, a destination mapping, explicit imported records and intentional changes. PHP initialization needs neither frontend/ nor Node. Web controllers/views/public JavaScript never read this file.

## All 81 source key destinations

Every row below was resolved in the actual database in both languages, including shared-fact format expansion. Original en/bn values remain in the snapshot’s provenance dictionary; the changed form/CTA values listed below are intentionally used in the database.

| Original content.en/bn key | Actual destination | Verification |
| --- | --- | --- |
| brand | SiteSetting(primary).doctor_name | PASS en/bn database values |
| subtitle | SiteSetting(primary).subtitle | PASS en/bn database values |
| doctorText | SiteSetting(primary).doctor_biography | PASS en/bn database values |
| credential | SiteSetting(primary).credential | PASS en/bn database values |
| department | SiteSetting(primary).department | PASS en/bn database values |
| place | SiteSetting(primary).institution | PASS en/bn database values |
| hours | SiteSetting(primary).consultation_hours | PASS en/bn database values |
| friday | SiteSetting(primary).friday_note | PASS en/bn database values |
| address | SiteSetting(primary).address | PASS en/bn database values |
| footertext | SiteSetting(primary).footer_description | PASS en/bn database values |
| galleryNote | SiteSetting(primary).stock_notice; MediaAsset(all nine).stock_note | PASS en/bn database values |
| disclaimer | SiteSetting(primary).medical_disclaimer | PASS en/bn database values |
| ctatitle | SiteSetting(primary).cta_title | PASS en/bn database values |
| ctadesc | SiteSetting(primary).cta_description | PASS en/bn database values |
| ctabtn | SiteSetting(primary).cta_label | PASS en/bn database values |
| home | NavigationItem(header:home).label; Page(home).label | PASS en/bn database values |
| about | NavigationItem(header:about).label; Page(about).label; NavigationItem(footer:about).label | PASS en/bn database values |
| services | NavigationItem(header:services).label; Page(services).label; NavigationItem(footer:services).label | PASS en/bn database values |
| diagnostics | NavigationItem(header:diagnostics).label; Page(diagnostics).label | PASS en/bn database values |
| blog | NavigationItem(header:blog).label; Page(blog).label; NavigationItem(footer:blog).label; Page(article).label | PASS en/bn database values |
| gallery | NavigationItem(header:gallery).label; Page(gallery).label; NavigationItem(footer:gallery).label | PASS en/bn database values |
| contact | NavigationItem(header:contact).label; Page(contact).label | PASS en/bn database values |
| appointment | ContentField(home.appointment).value | PASS en/bn database values |
| eyebrow | ContentField(home.eyebrow).value | PASS en/bn database values |
| herotitle | ContentField(home.herotitle).value | PASS en/bn database values |
| herodesc | ContentField(home.herodesc).value | PASS en/bn database values |
| visit | ContentField(home.visit).value | PASS en/bn database values |
| servicesTitle | ContentField(home.servicesTitle).value | PASS en/bn database values |
| servicesDesc | ContentField(home.servicesDesc).value | PASS en/bn database values |
| doctorEyebrow | ContentField(home.doctorEyebrow).value | PASS en/bn database values |
| doctorTitle | ContentField(home.doctorTitle).value | PASS en/bn database values |
| blogtitle | ContentField(home.blogtitle).value | PASS en/bn database values |
| explore | ContentField(ui.explore).value | PASS en/bn database values |
| read | ContentField(ui.read).value | PASS en/bn database values |
| readmore | ContentField(ui.readmore).value | PASS en/bn database values |
| all | ContentField(ui.filter_all).value | PASS en/bn database values |
| qualification | ContentField(shared.qualification).value | PASS en/bn database values |
| hoursTitle | ContentField(shared.hoursTitle).value | PASS en/bn database values |
| visitTitle | ContentField(shared.visitTitle).value | PASS en/bn database values |
| profession | ContentField(shared.profession).value | PASS en/bn database values |
| visitText | ContentField(shared.visitText).value | PASS en/bn database values |
| gallerytitle | ContentField(shared.gallerytitle).value | PASS en/bn database values |
| gallerydesc | ContentField(shared.gallerydesc).value | PASS en/bn database values |
| blogheading | ContentField(blog.blogheading).value | PASS en/bn database values |
| blogintro | ContentField(blog.blogintro).value | PASS en/bn database values |
| tagtitle | ContentField(blog.tagtitle).value | PASS en/bn database values |
| blogdesc | ContentField(blog.blogdesc).value | PASS en/bn database values |
| fb | ContentField(contact.fb).value | PASS en/bn database values |
| contacttitle | ContentField(contact.contacttitle).value | PASS en/bn database values |
| contactdesc | ContentField(contact.contactdesc).value | PASS en/bn database values |
| formtitle | ContentField(contact.formtitle).value | PASS en/bn database values |
| name | ContentField(contact.name).value | PASS en/bn database values |
| phone | ContentField(contact.phone).value | PASS en/bn database values |
| topic | ContentField(contact.topic).value | PASS en/bn database values |
| message | ContentField(contact.message).value | PASS en/bn database values |
| prepare | ContentField(contact.prepare).value | PASS en/bn database values |
| privacy | ContentField(contact.privacy).value | PASS en/bn database values |
| addressLabel | ContentField(contact.addressLabel).value | PASS en/bn database values |
| hoursLabel | ContentField(contact.hoursLabel).value | PASS en/bn database values |
| openmap | ContentField(contact.openmap).value | PASS en/bn database values |
| abouttitle | ContentField(about.abouttitle).value | PASS en/bn database values |
| aboutdesc | ContentField(about.aboutdesc).value | PASS en/bn database values |
| diagtitle | ContentField(diagnostics.diagtitle).value | PASS en/bn database values |
| diagdesc | ContentField(diagnostics.diagdesc).value | PASS en/bn database values |
| diagnotice | ContentField(diagnostics.diagnotice).value | PASS en/bn database values |
| diagtags | ContentField(diagnostics.diagtags).value | PASS en/bn database values |
| diagtagsdesc | ContentField(diagnostics.diagtagsdesc).value | PASS en/bn database values |
| servicePageTitle | ContentField(services.servicePageTitle).value | PASS en/bn database values |
| servicePageDesc | ContentField(services.servicePageDesc).value | PASS en/bn database values |
| clinical | Category(clinical).label | PASS en/bn database values |
| wellness | Category(wellness).label | PASS en/bn database values |
| facility | Category(facility).label | PASS en/bn database values |
| obtitle | CareCard(obstetrics).title; ConsultationOption(obstetrics).label | PASS en/bn database values |
| obdesc | CareCard(obstetrics).description | PASS en/bn database values |
| gytitle | CareCard(gynecology).title; ConsultationOption(gynecology).label | PASS en/bn database values |
| gydesc | CareCard(gynecology).description | PASS en/bn database values |
| iuititle | CareCard(fertility).title; ConsultationOption(fertility).label | PASS en/bn database values |
| iuidesc | CareCard(fertility).description | PASS en/bn database values |
| diatitle | CareCard(diagnostics).title; ConsultationOption(diagnostics).label | PASS en/bn database values |
| diadesc | CareCard(diagnostics).description | PASS en/bn database values |
| learn | CareCard(all four).link_label | PASS en/bn database values |

## Intentional request/contact wording

The original form only copied text and launched Facebook. The existing contact card now has a small Appointment request / General contact selector. It uses ordinary POST/Redirect/GET, server validation and a synchronous database save before success. Consultation type is shown only for appointments; the four ConsultationOption records are independent of the five Service records. A phone is required for follow-up; optional email is supported by the private model but is not shown initially. There is no requested visit-time field or confirmed booking promise. The separate Facebook label/target is retained as an ordinary social link; appointment CTAs validate to the internal contact route.

| Database field | English initial value | বাংলা initial value |
| --- | --- | --- |
| contact.formtitle | Send an appointment request or message | অ্যাপয়েন্টমেন্টের অনুরোধ বা বার্তা পাঠান |
| contact.name | Patient / contact name | রোগী / যোগাযোগকারীর নাম |
| contact.contactdesc | Find chamber information and send an appointment request or message. | চেম্বারের তথ্য দেখুন এবং অ্যাপয়েন্টমেন্টের অনুরোধ বা বার্তা পাঠান। |
| contact.prepare | Submit appointment request | অ্যাপয়েন্টমেন্টের অনুরোধ পাঠান |
| contact.privacy | Your details will be stored and reviewed by chamber staff for follow-up. An appointment request does not confirm a visit. | আপনার তথ্য সংরক্ষণ করা হবে এবং যোগাযোগের জন্য চেম্বারের কর্মীরা তা পর্যালোচনা করবেন। অ্যাপয়েন্টমেন্টের অনুরোধ পাঠানো মানেই সাক্ষাতের সময় নিশ্চিত হওয়া নয়। |
| home.appointment | Request an Appointment | অ্যাপয়েন্টমেন্টের অনুরোধ |
| SiteSetting.cta_description | Send an appointment request through this website. Chamber staff will follow up. | এই ওয়েবসাইটের মাধ্যমে অ্যাপয়েন্টমেন্টের অনুরোধ পাঠান। চেম্বারের কর্মীরা পরে আপনার সঙ্গে যোগাযোগ করবেন। |
| contact.mode_appointment | Appointment request | অ্যাপয়েন্টমেন্টের অনুরোধ |
| contact.mode_general | General contact | সাধারণ যোগাযোগ |
| contact.submit_contact | Send message | বার্তা পাঠান |
| contact.success_appointment | Your appointment request has been received. Chamber staff will contact you to confirm the next step. | আপনার অ্যাপয়েন্টমেন্টের অনুরোধ পাওয়া গেছে। পরবর্তী ধাপ নিশ্চিত করতে চেম্বারের কর্মীরা আপনার সঙ্গে যোগাযোগ করবেন। |
| contact.success_contact | Your message has been received. | আপনার বার্তা পাওয়া গেছে। |

`contact.prepare` is the appointment submit label; `contact.submit_contact` is the contact submit label. The seeded source ctabtn already says “Request an Appointment”; home.appointment changes “Book” to “Request”. Diagnostic/stock/medical caveats are retained verbatim in both languages. Clipboard claims and “Copied! Paste it into a Facebook message” are kept only as historical audit provenance and are never used for submission or success. No public patient/reference/PII listings exist.

## Newly localized literal UI text

| Named field | English | বাংলা |
| --- | --- | --- |
| contact.mode_label | Message type | বার্তার ধরন |
| contact.mode_appointment | Appointment request | অ্যাপয়েন্টমেন্টের অনুরোধ |
| contact.mode_general | General contact | সাধারণ যোগাযোগ |
| contact.submit_contact | Send message | বার্তা পাঠান |
| contact.success_appointment | Your appointment request has been received. Chamber staff will contact you to confirm the next step. | আপনার অ্যাপয়েন্টমেন্টের অনুরোধ পাওয়া গেছে। পরবর্তী ধাপ নিশ্চিত করতে চেম্বারের কর্মীরা আপনার সঙ্গে যোগাযোগ করবেন। |
| contact.success_contact | Your message has been received. | আপনার বার্তা পাওয়া গেছে। |
| contact.mode_apply | Apply | প্রয়োগ করুন |
| contact.validation_required | This field is required. | এই তথ্যটি প্রয়োজন। |
| contact.validation_invalid | Please enter a valid value. | সঠিক তথ্য লিখুন। |
| contact.validation_max | This value is too long. | এই তথ্যটি বেশি দীর্ঘ। |
| contact.save_failure | Your message could not be saved. Please try again. | আপনার বার্তাটি সংরক্ষণ করা যায়নি। আবার চেষ্টা করুন। |
| contact.submit_pending | Submitting… | পাঠানো হচ্ছে… |
| ui.empty_filter_results | No items found. | কোনো তথ্য পাওয়া যায়নি। |
| ui.menu_close | Close navigation | নেভিগেশন বন্ধ করুন |
| ui.switch_to_bn | বাংলা | বাংলা |
| ui.switch_to_en | English | English |
| ui.open_navigation | Open navigation | নেভিগেশন খুলুন |
| ui.gallery_preview | Gallery image preview | গ্যালারির ছবির পূর্বরূপ |
| ui.close_lightbox | Close | বন্ধ করুন |
| footer.social_label | Facebook ↗ | ফেসবুক ↗ |
| footer.copyright_format | © {year} {doctor_name} · {stock_notice} | © {year} {doctor_name} · {stock_notice} |
| meta.title_format | {page_label} \| {doctor_name} | {page_label} \| {doctor_name} |
| home.care_eyebrow | OUR AREAS OF CARE | আমাদের সেবার ক্ষেত্র |
| home.journal_eyebrow | THE JOURNAL | স্বাস্থ্য বিষয়ক লেখা |
| home.gallery_eyebrow | VISUAL STORIES | ছবির গল্প |
| home.hero_alt | Illustrative healthcare portrait — not the actual doctor | স্বাস্থ্যসেবার প্রতীকী প্রতিকৃতি — প্রকৃত চিকিৎসকের ছবি নয় |
| home.doctor_alt | Healthcare consultation illustration | স্বাস্থ্যসেবা পরামর্শের প্রতীকী ছবি |
| about.image_alt | Illustrative consultation photo | পরামর্শের প্রতীকী ছবি |
| diagnostics.image_alt | Illustrative lab stock photograph | ল্যাবের প্রতীকী স্টক ছবি |
| SiteSetting.clinic_name | DEEPAN IUI LAB | দীপন আইইউআই ল্যাব |
| SiteSetting.location_label | BARISHAL | বরিশাল |
| Post(pregnancy-care).editorial_badge | PREGNANCY | গর্ভাবস্থা |
| Post(pcos).editorial_badge | GYNECOLOGY | স্ত্রীরোগ |
| Post(fertility-first-visit).editorial_badge | FERTILITY | প্রজননস্বাস্থ্য |
| Post(menopause).editorial_badge | WOMEN’S HEALTH | নারীস্বাস্থ্য |

Language switch labels preserve their own language names. Format placeholders are plain-text interpolation of shared settings, never executable Blade. Hero/biography/profession/chamber prose, clinic headings and metadata reference canonical doctor/clinic fields; address/hours are not copied into per-page values. English metadata remains based on the original HTML description and now has an accurate Bangla equivalent referencing the same facts. Page/post metadata is individually editable. The original “Browse by tag” label remains, but filters and the schema use categories; there is no tagging subsystem. Source blogdesc is retained as blog.blogdesc; it was unused in the original template and remains available as an editable educational notice.

## Original articles and previews

| Original slug | Category | Original editorial badge | Initial publication | Homepage |
| --- | --- | --- | --- | --- |
| pregnancy-care | wellness | PREGNANCY | published at import time | yes |
| pcos | clinical | GYNECOLOGY | published at import time | yes |
| fertility-first-visit | clinical | FERTILITY | published at import time | yes |
| menopause | wellness | WOMEN’S HEALTH | published at import time | no |

All four article routes /blog/{slug} are readable in English and Bangla, with both original body paragraphs. Unknown/draft slugs return 404. Homepage previews are pregnancy-care, pcos, fertility-first-visit, and gallery items 1–3 (clinic, consult, lab), exactly in source order.

## External image limitations

| Media stable key | Legacy external URL | Unresolved limitation |
| --- | --- | --- |
| demo-photo-hero | https://scontent.fbzl7-1.fna.fbcdn.net/v/t39.30808-6/386655458_7165314940145758_7620333021630825497_n.jpg?stp=dst-jpg_tt6&cstp=mx1000x1000&ctp=s1000x1000&_nc_cat=100&_nc_map=urlgen_bucketless&ccb=1-7&_nc_sid=6ee11a&_nc_eui2=AeFk06JpC1BCOqXB7Ww4XxpSVfliVC9zBhtV-WJUL3MGGzhkPPiAClVm7pHgliW73nPbdqWxTcs2VrJ6W29L-hve&_nc_ohc=5X-VwDdwBmkQ7kNvwHDqX_o&_nc_oc=AdqY9Q9GpH-2u9D72wKp-6opk0YZ1EJ9GmR6TAcBFHMbCjqZ6qzXLwWhYGyShGfMokg&_nc_zt=23&_nc_ht=scontent.fbzl7-1.fna&_nc_gid=pGNeFVAGY4ZJbQHZGxS27w&_nc_ss=7b2a8&oh=00_AQM_1gBcuzQau2hyZHx4dKA60e8r8-HRPxkPlbfDw6hLYw&oe=6ACD61B1 | Signed Facebook CDN URL can expire; identity and rights unverified |
| demo-photo-doctor | https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=1000&q=85 | Remote Unsplash source can change/become unavailable; stock identity and rights unverified |
| demo-photo-consult | https://images.unsplash.com/photo-1579684385127-1ef15d508118?w=900&q=85 | Remote Unsplash source can change/become unavailable; stock identity and rights unverified |
| demo-photo-clinic | https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?w=1000&q=85 | Remote Unsplash source can change/become unavailable; stock identity and rights unverified |
| demo-photo-lab | https://images.unsplash.com/photo-1579154204601-01588f351e67?w=900&q=85 | Remote Unsplash source can change/become unavailable; stock identity and rights unverified |
| demo-photo-pregnancy | https://images.unsplash.com/photo-1493894473891-10fc1e5dbd22?w=900&q=85 | Remote Unsplash source can change/become unavailable; stock identity and rights unverified |
| demo-photo-care | https://images.unsplash.com/photo-1559757148-5c350d0d3c56?w=900&q=85 | Remote Unsplash source can change/become unavailable; stock identity and rights unverified |
| demo-photo-interior | https://images.unsplash.com/photo-1551076805-e1869033e561?w=900&q=85 | Remote Unsplash source can change/become unavailable; stock identity and rights unverified |
| demo-photo-team | https://images.unsplash.com/photo-1551601651-2a8555f1a136?w=900&q=85 | Remote Unsplash source can change/become unavailable; stock identity and rights unverified |

No local images, verified doctor/facility photographs, logo/favicon or font files were supplied. Initial media records intentionally use these external URLs. Step 1 HEAD checks returned 200; this step does not claim a new availability/identity/rights check or download. Google Fonts remains the original external CSS dependency. Replace legacy media through the future validated local upload screen when verified files are available; do not manufacture photographs or medical facts. MIME/size/dimensions are null for legacy URLs because no bytes were acquired.

## Repeatability, repair and verification

Explicit first setup: `php artisan migrate`, then `php artisan db:seed`. DatabaseSeeder calls InitialWebsiteContentSeeder without a default-user seeder. A locked initialization marker and one transaction protect a coherent import; completion is committed with all content. Failure rolls back all content and the marker. Normal later db:seed skips the completed website import entirely, so edited translations, drafts, hidden records, passwords and deliberately deleted originals stay untouched. Other explicitly added seeders may still run.

Only if the owner deliberately wants missing original demo rows restored: `php artisan website:repair-initial-content`. It creates missing records by stable keys, preserves all existing records and requires the original snapshot checksum. This explicit repair may restore an intentionally deleted original as visible/published demo content; it never changes an existing draft/hidden row. No migrate:fresh, automatic startup/web/deploy/login import, or runtime source fallback.

During initial implementation, unchanged demo text slots were refined into canonical fact placeholders under exact-value and original-checksum guards, before any users or inquiries existed. That development-only adjustment is not part of normal repeat initialization and cannot overwrite owner edits.

- Focused suite: **10 tests, 161 assertions, passed** (final results also recorded in IMPLEMENTATION_STATUS). Tests use only doctor_portfolio_test with DML transactions/rollback, no RefreshDatabase/migrate:fresh.
- Readability: all 7 public URLs + all 4 article routes in each locale; no original app.js or admin build assets in public HTML.
- Fallback: selected locale → non-empty English → empty/safe state; null, whitespace, empty arrays and recursively empty structured bodies covered.
- Import: initial counts, relationships, unique slugs, failed transaction, repeated seed after edits/draft/hide/delete, explicit missing-only repair and password preservation.
- Validation: unknown keys/fields, missing English and unsafe targets rejected; JSON casts, FK and unique constraints verified.
- Request tests: both inquiry kinds save before success; invalid input saves nothing; general strips consultation; GET mode/language/category forms create no inquiries. All fake submissions roll back in the dedicated test DB.
- Source preserved: frontend SHA-256 hashes and copied public stylesheet match the original audit. No original app.js loaded or published as the dynamic renderer.

Full public visual/browser QA, legacy URL compatibility, admin authentication/editors/inquiry management and upload controls remain later build work. The database-backed Blade foundation was implemented now to satisfy Step 3’s populated/readable-site acceptance.

Final development-database verification (separate from test fixtures): **22 HTTP kernel responses returned 200** across the seven main URLs and four articles in each language. All test fixture tables checked after rollback had zero rows; development users/inquiries remained zero. The last database comparison still matched all 266 locale pairs and 81 mappings. Source preservation checks matched all ten original hashes and the byte-identical public stylesheet. Composer validation, Pint, Blade compilation and admin asset build passed.


## Owner Step 10 gallery preservation check

No new seed/migration was necessary. Read-only verification on doctor_portfolio confirmed all eight original GalleryImage rows match initialization input for English/Bengali captions and alt, category/media references, source order and visible/homepage flags. Source Bengali literals are preserved, not retranslated. Category totals remain facility 3, clinical 3, wellness 2; first three remain homepage-selected. Ten reference hashes and snapshot checksum match the audit. Development remains nine external/zero local media, with no fake inquiries or owner account/content changes. Gallery CRUD tests run only in doctor_portfolio_test rollback transactions; normal seeding continues to preserve edits and intentional deletions. See GALLERY_CMS and IMPLEMENTATION_STATUS for exact tests.


## Owner Step 11 supplemental form initialization

Migration 000005 ran on doctor_portfolio and doctor_portfolio_test. Explicit db:seed on the development database skipped the completed original importer and created only five missing ContentField values with both English/Bangla translations through InquiryFormContentSeeder. The initialization-only UTF-8 input is database/seeders/data/inquiry-form.json; separate marker inquiry-form-v1 completes transactionally and later seeds skip it, preserving edits/intentional deletions. Original initial-website.json checksum remains f9b83ae17b0c94d661188b31833d585e2dd9a246871c2110eddb40d62742b9d8; all 81 mappings and owner-specified exact form/privacy/success/CTA copy remain unchanged.

| New key → ContentField.value {en,bn} | English | Bangla |
| --- | --- | --- |
| contact.submission_expired | Please reload the form and try again. | ফর্মটি আবার খুলে চেষ্টা করুন। |
| contact.rate_limited | Too many requests. Please wait a minute and try again. | অনেকবার অনুরোধ পাঠানো হয়েছে। এক মিনিট অপেক্ষা করে আবার চেষ্টা করুন। |
| contact.submission_rejected | Your request could not be accepted. Please review the form and try again. | আপনার অনুরোধটি গ্রহণ করা যায়নি। ফর্মটি দেখে আবার চেষ্টা করুন। |
| contact.reference_label | Reference | রেফারেন্স |
| contact.consultation_help | For appointment requests only. General contact does not need a consultation type. | শুধু অ্যাপয়েন্টমেন্টের অনুরোধের জন্য। সাধারণ যোগাযোগের জন্য পরামর্শের ধরন প্রয়োজন নেই। |

Actual development counts after this import: site_settings1, pages8, content_fields93, page_sections20, navigation_items11, care_cards4, services5, consultation_options4, categories3, posts4, gallery_images8, media_assets9 = **170 content records + two completion markers**. Existing users1 unchanged; inquiries0. All four cards/five services/four options/eight gallery mappings retain source values/order/visibility/Bangla. Nine images remain external URL records, zero local replacements; original external fonts/assets remain unresolved network dependencies, with no new availability/download claim. Real HTTP test submissions were made only in doctor_portfolio_test and cleaned by owned fixture IDs/hashes; test database content/users/inquiries/markers/history returned to zero. INQUIRY_WORKFLOW records the actual results and Step12 inbox integration boundary.
