# Public content ownership matrix

Baseline 2026-10-10. `frontend/` is the unmodified source. Status `Audited; planned` means the source is read and ownership decided, but no database/admin/Blade implementation exists. `New; planned` means a required functional copy addition with no original value. No row is implemented in this step. All managed textual fields (including alt, metadata and labels) use `{en,bn}` and the one LocalizedContent service. URL targets use validated locale pairs; enum/identity/media-path data are technical. CSS, layout classes, numbering and fixed control glyphs are code-owned. Rows referring to canonical settings share one record, never per-page clones.

The first table contains every one of the 81 dictionary keys, including unused blogdesc, with individual source, DB owner, admin screen, rendering use and status. Subsequent tables enumerate collection fields and extra literal/structural copy. Exact bilingual values and slugs are preserved in FRONTEND_AUDIT. Shared fact prose uses allowlisted format placeholders as specified in ARCHITECTURE.


## Dictionary fields: 81 rows

| Public field | Exact source | Proposed database owner | Future admin screen | Public rendering location | Status |
| --- | --- | --- | --- | --- | --- |
| home | frontend/assets/app.js L2 content.en/bn.home | NavigationItem(header:home).label | Navigation; Page labels | Header; matching page hero/title; footer subset via footer items | Audited; planned |
| about | frontend/assets/app.js L2 content.en/bn.about | NavigationItem(header:about).label | Navigation; Page labels | Header; matching page hero/title; footer subset via footer items | Audited; planned |
| services | frontend/assets/app.js L2 content.en/bn.services | NavigationItem(header:services).label | Navigation; Page labels | Header; matching page hero/title; footer subset via footer items | Audited; planned |
| diagnostics | frontend/assets/app.js L2 content.en/bn.diagnostics | NavigationItem(header:diagnostics).label | Navigation; Page labels | Header; matching page hero/title; footer subset via footer items | Audited; planned |
| blog | frontend/assets/app.js L2 content.en/bn.blog | NavigationItem(header:blog).label | Navigation; Page labels | Header; matching page hero/title; footer subset via footer items | Audited; planned |
| gallery | frontend/assets/app.js L2 content.en/bn.gallery | NavigationItem(header:gallery).label | Navigation; Page labels | Header; matching page hero/title; footer subset via footer items | Audited; planned |
| contact | frontend/assets/app.js L2 content.en/bn.contact | NavigationItem(header:contact).label | Navigation; Page labels | Header; matching page hero/title; footer subset via footer items | Audited; planned |
| appointment | frontend/assets/app.js L2 content.en/bn.appointment | ContentField.home.appointment | Home page fields | Corresponding home hero/care/doctor/journal slot; herodesc permits canonical fact placeholders | Audited; planned |
| brand | frontend/assets/app.js L2 content.en/bn.brand | SiteSetting.doctor_name | Shared settings | Brand/header/footer; about; page-title format | Audited; planned |
| subtitle | frontend/assets/app.js L2 content.en/bn.subtitle | SiteSetting.subtitle | Shared settings | Header brand subtitle | Audited; planned |
| eyebrow | frontend/assets/app.js L2 content.en/bn.eyebrow | ContentField.home.eyebrow | Home page fields | Corresponding home hero/care/doctor/journal slot; herodesc permits canonical fact placeholders | Audited; planned |
| herotitle | frontend/assets/app.js L2 content.en/bn.herotitle | ContentField.home.herotitle | Home page fields | Corresponding home hero/care/doctor/journal slot; herodesc permits canonical fact placeholders | Audited; planned |
| herodesc | frontend/assets/app.js L2 content.en/bn.herodesc | ContentField.home.herodesc | Home page fields | Corresponding home hero/care/doctor/journal slot; herodesc permits canonical fact placeholders | Audited; planned |
| credential | frontend/assets/app.js L2 content.en/bn.credential | SiteSetting.credential | Shared doctor settings | Home stats/bullets; about bullets; canonical prose placeholders | Audited; planned |
| department | frontend/assets/app.js L2 content.en/bn.department | SiteSetting.department | Shared doctor settings | Home stats/bullets; about bullets; canonical prose placeholders | Audited; planned |
| place | frontend/assets/app.js L2 content.en/bn.place | SiteSetting.institution | Shared doctor settings | Home stats/bullets; about bullets; canonical prose placeholders | Audited; planned |
| visit | frontend/assets/app.js L2 content.en/bn.visit | ContentField.home.visit | Home page fields | Corresponding home hero/care/doctor/journal slot; herodesc permits canonical fact placeholders | Audited; planned |
| explore | frontend/assets/app.js L2 content.en/bn.explore | ContentField.ui.explore | Shared UI copy | explore: home services link/footer heading; read: article action; readmore: home about/blog/gallery links | Audited; planned |
| servicesTitle | frontend/assets/app.js L2 content.en/bn.servicesTitle | ContentField.home.servicesTitle | Home page fields | Corresponding home hero/care/doctor/journal slot; herodesc permits canonical fact placeholders | Audited; planned |
| servicesDesc | frontend/assets/app.js L2 content.en/bn.servicesDesc | ContentField.home.servicesDesc | Home page fields | Corresponding home hero/care/doctor/journal slot; herodesc permits canonical fact placeholders | Audited; planned |
| obtitle | frontend/assets/app.js L2 content.en/bn.obtitle | CareCard(obstetrics).title | Homepage care cards | Homepage card; appointment topic option | Audited; planned |
| obdesc | frontend/assets/app.js L2 content.en/bn.obdesc | CareCard(obstetrics).description | Homepage care cards | Homepage card description | Audited; planned |
| gytitle | frontend/assets/app.js L2 content.en/bn.gytitle | CareCard(gynecology).title | Homepage care cards | Homepage card; appointment topic option | Audited; planned |
| gydesc | frontend/assets/app.js L2 content.en/bn.gydesc | CareCard(gynecology).description | Homepage care cards | Homepage card description | Audited; planned |
| iuititle | frontend/assets/app.js L2 content.en/bn.iuititle | CareCard(fertility).title | Homepage care cards | Homepage card; appointment topic option | Audited; planned |
| iuidesc | frontend/assets/app.js L2 content.en/bn.iuidesc | CareCard(fertility).description | Homepage care cards | Homepage card description | Audited; planned |
| diatitle | frontend/assets/app.js L2 content.en/bn.diatitle | CareCard(diagnostics).title | Homepage care cards | Homepage card; appointment topic option | Audited; planned |
| diadesc | frontend/assets/app.js L2 content.en/bn.diadesc | CareCard(diagnostics).description | Homepage care cards | Homepage card description | Audited; planned |
| learn | frontend/assets/app.js L2 content.en/bn.learn | CareCard.link_label (four initialized copies for independent cards) | Homepage care cards | Each home care-card action label | Audited; planned |
| doctorEyebrow | frontend/assets/app.js L2 content.en/bn.doctorEyebrow | ContentField.home.doctorEyebrow | Home page fields | Corresponding home hero/care/doctor/journal slot; herodesc permits canonical fact placeholders | Audited; planned |
| doctorTitle | frontend/assets/app.js L2 content.en/bn.doctorTitle | ContentField.home.doctorTitle | Home page fields | Corresponding home hero/care/doctor/journal slot; herodesc permits canonical fact placeholders | Audited; planned |
| doctorText | frontend/assets/app.js L2 content.en/bn.doctorText | SiteSetting.doctor_biography | Shared doctor settings | Home doctor split; about biography | Audited; planned |
| qualification | frontend/assets/app.js L2 content.en/bn.qualification | ContentField.shared.qualification | Shared copy | qualification: about; visitTitle: about/diagnostics; hoursTitle: about | Audited; planned |
| profession | frontend/assets/app.js L2 content.en/bn.profession | ContentField.shared.profession (validated format) | Shared copy | Profession: home stats; visitText: home floating/about/diagnostics; references canonical settings | Audited; planned |
| visitTitle | frontend/assets/app.js L2 content.en/bn.visitTitle | ContentField.shared.visitTitle | Shared copy | qualification: about; visitTitle: about/diagnostics; hoursTitle: about | Audited; planned |
| visitText | frontend/assets/app.js L2 content.en/bn.visitText | ContentField.shared.visitText (validated format) | Shared copy | Profession: home stats; visitText: home floating/about/diagnostics; references canonical settings | Audited; planned |
| hoursTitle | frontend/assets/app.js L2 content.en/bn.hoursTitle | ContentField.shared.hoursTitle | Shared copy | qualification: about; visitTitle: about/diagnostics; hoursTitle: about | Audited; planned |
| hours | frontend/assets/app.js L2 content.en/bn.hours | SiteSetting.consultation_hours | Shared clinic settings | Topbar; about hours card; contact; footer | Audited; planned |
| friday | frontend/assets/app.js L2 content.en/bn.friday | SiteSetting.friday_note | Shared clinic settings | Topbar; about hours card; contact; footer | Audited; planned |
| blogtitle | frontend/assets/app.js L2 content.en/bn.blogtitle | ContentField.home.blogtitle | Home page fields | Corresponding home hero/care/doctor/journal slot; herodesc permits canonical fact placeholders | Audited; planned |
| blogdesc | frontend/assets/app.js L2 content.en/bn.blogdesc | ContentField.blog.education_notice | Blog page fields | Not rendered in original; reserved editable educational copy | Audited; planned |
| gallerytitle | frontend/assets/app.js L2 content.en/bn.gallerytitle | ContentField.shared.gallerytitle | Gallery and shared notices | Gallery hero/home preview; galleryNote: gallery/about/footer copyright | Audited; planned |
| gallerydesc | frontend/assets/app.js L2 content.en/bn.gallerydesc | ContentField.shared.gallerydesc | Gallery and shared notices | Gallery hero/home preview; galleryNote: gallery/about/footer copyright | Audited; planned |
| all | frontend/assets/app.js L2 content.en/bn.all | ContentField.ui.filter_all | Shared UI copy | Blog and gallery filters | Audited; planned |
| clinical | frontend/assets/app.js L2 content.en/bn.clinical | Category(code=clinical).name | Categories | Filter buttons/category tags; facility gallery only | Audited; planned |
| wellness | frontend/assets/app.js L2 content.en/bn.wellness | Category(code=wellness).name | Categories | Filter buttons/category tags; facility gallery only | Audited; planned |
| facility | frontend/assets/app.js L2 content.en/bn.facility | Category(code=facility).name | Categories | Filter buttons/category tags; facility gallery only | Audited; planned |
| read | frontend/assets/app.js L2 content.en/bn.read | ContentField.ui.read | Shared UI copy | explore: home services link/footer heading; read: article action; readmore: home about/blog/gallery links | Audited; planned |
| ctatitle | frontend/assets/app.js L2 content.en/bn.ctatitle | ContentField.cta.ctatitle | Shared CTA | CTA on seven page types; desc/label intentionally revised for stored inquiry | Audited; planned |
| ctadesc | frontend/assets/app.js L2 content.en/bn.ctadesc | ContentField.cta.ctadesc | Shared CTA | CTA on seven page types; desc/label intentionally revised for stored inquiry | Audited; planned |
| ctabtn | frontend/assets/app.js L2 content.en/bn.ctabtn | ContentField.cta.ctabtn | Shared CTA | CTA on seven page types; desc/label intentionally revised for stored inquiry | Audited; planned |
| address | frontend/assets/app.js L2 content.en/bn.address | SiteSetting.address | Shared clinic settings | Topbar; contact; footer; visitText placeholder | Audited; planned |
| fb | frontend/assets/app.js L2 content.en/bn.fb | ContentField.contact.fb | Contact page fields | Contact information card | Audited; planned |
| footertext | frontend/assets/app.js L2 content.en/bn.footertext | SiteSetting.footer_description | Shared settings | Footer brand column | Audited; planned |
| galleryNote | frontend/assets/app.js L2 content.en/bn.galleryNote | ContentField.shared.galleryNote | Gallery and shared notices | Gallery hero/home preview; galleryNote: gallery/about/footer copyright | Audited; planned |
| blogheading | frontend/assets/app.js L2 content.en/bn.blogheading | ContentField.blog.blogheading | Blog page fields | Blog hero/tag-filter heading | Audited; planned |
| blogintro | frontend/assets/app.js L2 content.en/bn.blogintro | ContentField.blog.blogintro | Blog page fields | Blog hero/tag-filter heading | Audited; planned |
| tagtitle | frontend/assets/app.js L2 content.en/bn.tagtitle | ContentField.blog.tagtitle | Blog page fields | Blog hero/tag-filter heading | Audited; planned |
| contacttitle | frontend/assets/app.js L2 content.en/bn.contacttitle | ContentField.contact.contacttitle | Contact form/copy | Contact hero/form; contactdesc/formtitle/name/prepare/privacy intentionally revised | Audited; planned |
| contactdesc | frontend/assets/app.js L2 content.en/bn.contactdesc | ContentField.contact.contactdesc | Contact form/copy | Contact hero/form; contactdesc/formtitle/name/prepare/privacy intentionally revised | Audited; planned |
| formtitle | frontend/assets/app.js L2 content.en/bn.formtitle | ContentField.contact.formtitle | Contact form/copy | Contact hero/form; contactdesc/formtitle/name/prepare/privacy intentionally revised | Audited; planned |
| name | frontend/assets/app.js L2 content.en/bn.name | ContentField.contact.name | Contact form/copy | Contact hero/form; contactdesc/formtitle/name/prepare/privacy intentionally revised | Audited; planned |
| phone | frontend/assets/app.js L2 content.en/bn.phone | ContentField.contact.phone | Contact form/copy | Contact hero/form; contactdesc/formtitle/name/prepare/privacy intentionally revised | Audited; planned |
| topic | frontend/assets/app.js L2 content.en/bn.topic | ContentField.contact.topic | Contact form/copy | Contact hero/form; contactdesc/formtitle/name/prepare/privacy intentionally revised | Audited; planned |
| message | frontend/assets/app.js L2 content.en/bn.message | ContentField.contact.message | Contact form/copy | Contact hero/form; contactdesc/formtitle/name/prepare/privacy intentionally revised | Audited; planned |
| prepare | frontend/assets/app.js L2 content.en/bn.prepare | ContentField.contact.prepare | Contact form/copy | Contact hero/form; contactdesc/formtitle/name/prepare/privacy intentionally revised | Audited; planned |
| privacy | frontend/assets/app.js L2 content.en/bn.privacy | ContentField.contact.privacy | Contact form/copy | Contact hero/form; contactdesc/formtitle/name/prepare/privacy intentionally revised | Audited; planned |
| addressLabel | frontend/assets/app.js L2 content.en/bn.addressLabel | ContentField.contact.addressLabel | Contact page fields | Contact information card | Audited; planned |
| hoursLabel | frontend/assets/app.js L2 content.en/bn.hoursLabel | ContentField.contact.hoursLabel | Contact page fields | Contact information card | Audited; planned |
| openmap | frontend/assets/app.js L2 content.en/bn.openmap | ContentField.contact.openmap | Contact page fields | Contact information card | Audited; planned |
| abouttitle | frontend/assets/app.js L2 content.en/bn.abouttitle | ContentField.about.abouttitle | About page fields | About hero | Audited; planned |
| aboutdesc | frontend/assets/app.js L2 content.en/bn.aboutdesc | ContentField.about.aboutdesc | About page fields | About hero | Audited; planned |
| diagtitle | frontend/assets/app.js L2 content.en/bn.diagtitle | ContentField.diagnostics.diagtitle | Diagnostics page fields | Diagnostics hero/split/inquiry topics/verified-menu caveat | Audited; planned |
| diagdesc | frontend/assets/app.js L2 content.en/bn.diagdesc | ContentField.diagnostics.diagdesc | Diagnostics page fields | Diagnostics hero/split/inquiry topics/verified-menu caveat | Audited; planned |
| diagnotice | frontend/assets/app.js L2 content.en/bn.diagnotice | ContentField.diagnostics.diagnotice | Diagnostics page fields | Diagnostics hero/split/inquiry topics/verified-menu caveat | Audited; planned |
| diagtags | frontend/assets/app.js L2 content.en/bn.diagtags | ContentField.diagnostics.diagtags | Diagnostics page fields | Diagnostics hero/split/inquiry topics/verified-menu caveat | Audited; planned |
| diagtagsdesc | frontend/assets/app.js L2 content.en/bn.diagtagsdesc | ContentField.diagnostics.diagtagsdesc | Diagnostics page fields | Diagnostics hero/split/inquiry topics/verified-menu caveat | Audited; planned |
| servicePageTitle | frontend/assets/app.js L2 content.en/bn.servicePageTitle | ContentField.services.servicePageTitle | Services page fields | Services hero | Audited; planned |
| servicePageDesc | frontend/assets/app.js L2 content.en/bn.servicePageDesc | ContentField.services.servicePageDesc | Services page fields | Services hero | Audited; planned |
| disclaimer | frontend/assets/app.js L2 content.en/bn.disclaimer | ContentField.shared.medical_disclaimer | Shared notices | Services grid and every article; required caveat | Audited; planned |
| readmore | frontend/assets/app.js L2 content.en/bn.readmore | ContentField.ui.readmore | Shared UI copy | explore: home services link/footer heading; read: article action; readmore: home about/blog/gallery links | Audited; planned |

## Navigation labels, destinations and collection controls

| Public field / collection member | Source location | Proposed database owner | Future admin screen | Public rendering location | Status |
| --- | --- | --- | --- | --- | --- |
| header home.label | app.js L6 nav(); content.home | NavigationItem(header:home).label {en,bn} | Navigation | header navigation | Audited; planned |
| header home.target | app.js L6 index.html | NavigationItem.target {en,bn}; known route validation | Navigation | header link href | Audited; planned |
| header about.label | app.js L6 nav(); content.about | NavigationItem(header:about).label {en,bn} | Navigation | header navigation | Audited; planned |
| header about.target | app.js L6 about.html | NavigationItem.target {en,bn}; known route validation | Navigation | header link href | Audited; planned |
| header services.label | app.js L6 nav(); content.services | NavigationItem(header:services).label {en,bn} | Navigation | header navigation | Audited; planned |
| header services.target | app.js L6 services.html | NavigationItem.target {en,bn}; known route validation | Navigation | header link href | Audited; planned |
| header diagnostics.label | app.js L6 nav(); content.diagnostics | NavigationItem(header:diagnostics).label {en,bn} | Navigation | header navigation | Audited; planned |
| header diagnostics.target | app.js L6 diagnostics.html | NavigationItem.target {en,bn}; known route validation | Navigation | header link href | Audited; planned |
| header blog.label | app.js L6 nav(); content.blog | NavigationItem(header:blog).label {en,bn} | Navigation | header navigation | Audited; planned |
| header blog.target | app.js L6 blog.html | NavigationItem.target {en,bn}; known route validation | Navigation | header link href | Audited; planned |
| header gallery.label | app.js L6 nav(); content.gallery | NavigationItem(header:gallery).label {en,bn} | Navigation | header navigation | Audited; planned |
| header gallery.target | app.js L6 gallery.html | NavigationItem.target {en,bn}; known route validation | Navigation | header link href | Audited; planned |
| header contact.label | app.js L6 nav(); content.contact | NavigationItem(header:contact).label {en,bn} | Navigation | header navigation | Audited; planned |
| header contact.target | app.js L6 contact.html | NavigationItem.target {en,bn}; known route validation | Navigation | header link href | Audited; planned |
| footer about.label | app.js L7 footer(); content.about | NavigationItem(footer:about).label {en,bn} | Navigation | footer navigation | Audited; planned |
| footer about.target | app.js L7 about.html | NavigationItem.target {en,bn}; known route validation | Navigation | footer link href | Audited; planned |
| footer services.label | app.js L7 footer(); content.services | NavigationItem(footer:services).label {en,bn} | Navigation | footer navigation | Audited; planned |
| footer services.target | app.js L7 services.html | NavigationItem.target {en,bn}; known route validation | Navigation | footer link href | Audited; planned |
| footer blog.label | app.js L7 footer(); content.blog | NavigationItem(footer:blog).label {en,bn} | Navigation | footer navigation | Audited; planned |
| footer blog.target | app.js L7 blog.html | NavigationItem.target {en,bn}; known route validation | Navigation | footer link href | Audited; planned |
| footer gallery.label | app.js L7 footer(); content.gallery | NavigationItem(footer:gallery).label {en,bn} | Navigation | footer navigation | Audited; planned |
| footer gallery.target | app.js L7 gallery.html | NavigationItem.target {en,bn}; known route validation | Navigation | footer link href | Audited; planned |
| Navigation ordering/visibility | L6/L7 array order | NavigationItem.sort_order/is_visible | Navigation | Header/footer ordering | Audited; planned |

## Homepage care-card fields

| Public field / collection member | Source location | Proposed database owner | Future admin screen | Public rendering location | Status |
| --- | --- | --- | --- | --- | --- |
| Card 1 obstetrics.title | app.js L9 cards(); obtitle | CareCard(obstetrics).title | Homepage care cards | Home .service-card; title also appointment option | Audited; planned |
| Card 1 obstetrics.description | app.js L9 cards(); obdesc | CareCard(obstetrics).description | Homepage care cards | Home .service-card; title also appointment option | Audited; planned |
| Card 1 obstetrics.icon | app.js L9 cards(); 🤰 | CareCard(obstetrics).icon_code (allowlist) | Homepage care cards | Home .service-card; title also appointment option | Audited; planned |
| Card 1 obstetrics.link label | app.js L9 cards(); learn | CareCard(obstetrics).link_label {en,bn} | Homepage care cards | Home .service-card; title also appointment option | Audited; planned |
| Card 1 obstetrics.target | app.js L9 cards(); services.html | CareCard(obstetrics).target {en,bn} | Homepage care cards | Home .service-card; title also appointment option | Audited; planned |
| Card 2 gynecology.title | app.js L9 cards(); gytitle | CareCard(gynecology).title | Homepage care cards | Home .service-card; title also appointment option | Audited; planned |
| Card 2 gynecology.description | app.js L9 cards(); gydesc | CareCard(gynecology).description | Homepage care cards | Home .service-card; title also appointment option | Audited; planned |
| Card 2 gynecology.icon | app.js L9 cards(); ✿ | CareCard(gynecology).icon_code (allowlist) | Homepage care cards | Home .service-card; title also appointment option | Audited; planned |
| Card 2 gynecology.link label | app.js L9 cards(); learn | CareCard(gynecology).link_label {en,bn} | Homepage care cards | Home .service-card; title also appointment option | Audited; planned |
| Card 2 gynecology.target | app.js L9 cards(); services.html | CareCard(gynecology).target {en,bn} | Homepage care cards | Home .service-card; title also appointment option | Audited; planned |
| Card 3 fertility.title | app.js L9 cards(); iuititle | CareCard(fertility).title | Homepage care cards | Home .service-card; title also appointment option | Audited; planned |
| Card 3 fertility.description | app.js L9 cards(); iuidesc | CareCard(fertility).description | Homepage care cards | Home .service-card; title also appointment option | Audited; planned |
| Card 3 fertility.icon | app.js L9 cards(); ♡ | CareCard(fertility).icon_code (allowlist) | Homepage care cards | Home .service-card; title also appointment option | Audited; planned |
| Card 3 fertility.link label | app.js L9 cards(); learn | CareCard(fertility).link_label {en,bn} | Homepage care cards | Home .service-card; title also appointment option | Audited; planned |
| Card 3 fertility.target | app.js L9 cards(); services.html | CareCard(fertility).target {en,bn} | Homepage care cards | Home .service-card; title also appointment option | Audited; planned |
| Card 4 diagnostics.title | app.js L9 cards(); diatitle | CareCard(diagnostics).title | Homepage care cards | Home .service-card; title also appointment option | Audited; planned |
| Card 4 diagnostics.description | app.js L9 cards(); diadesc | CareCard(diagnostics).description | Homepage care cards | Home .service-card; title also appointment option | Audited; planned |
| Card 4 diagnostics.icon | app.js L9 cards(); 🔬 | CareCard(diagnostics).icon_code (allowlist) | Homepage care cards | Home .service-card; title also appointment option | Audited; planned |
| Card 4 diagnostics.link label | app.js L9 cards(); learn | CareCard(diagnostics).link_label {en,bn} | Homepage care cards | Home .service-card; title also appointment option | Audited; planned |
| Card 4 diagnostics.target | app.js L9 cards(); diagnostics.html | CareCard(diagnostics).target {en,bn} | Homepage care cards | Home .service-card; title also appointment option | Audited; planned |
| Care-card ordering/visibility/topic code | L9 source order; L22 topic option titles | CareCard.sort_order/is_visible/import_key | Homepage care cards | Home order; appointment-topic allowlist | Audited; planned |

## Services-page fields (separate collection)

| Public field / collection member | Source location | Proposed database owner | Future admin screen | Public rendering location | Status |
| --- | --- | --- | --- | --- | --- |
| Service 1.title | app.js L16 serviceDetails.en/bn[0][0] | Service(import_key=demo-service-1).title {en,bn} | Services | Services .info-box 1 | Audited; planned |
| Service 1.description | app.js L16 serviceDetails.en/bn[0][1] | Service(import_key=demo-service-1).description {en,bn} | Services | Services .info-box 1 | Audited; planned |
| Service 2.title | app.js L16 serviceDetails.en/bn[1][0] | Service(import_key=demo-service-2).title {en,bn} | Services | Services .info-box 2 | Audited; planned |
| Service 2.description | app.js L16 serviceDetails.en/bn[1][1] | Service(import_key=demo-service-2).description {en,bn} | Services | Services .info-box 2 | Audited; planned |
| Service 3.title | app.js L16 serviceDetails.en/bn[2][0] | Service(import_key=demo-service-3).title {en,bn} | Services | Services .info-box 3 | Audited; planned |
| Service 3.description | app.js L16 serviceDetails.en/bn[2][1] | Service(import_key=demo-service-3).description {en,bn} | Services | Services .info-box 3 | Audited; planned |
| Service 4.title | app.js L16 serviceDetails.en/bn[3][0] | Service(import_key=demo-service-4).title {en,bn} | Services | Services .info-box 4 | Audited; planned |
| Service 4.description | app.js L16 serviceDetails.en/bn[3][1] | Service(import_key=demo-service-4).description {en,bn} | Services | Services .info-box 4 | Audited; planned |
| Service 5.title | app.js L16 serviceDetails.en/bn[4][0] | Service(import_key=demo-service-5).title {en,bn} | Services | Services .info-box 5 | Audited; planned |
| Service 5.description | app.js L16 serviceDetails.en/bn[4][1] | Service(import_key=demo-service-5).description {en,bn} | Services | Services .info-box 5 | Audited; planned |
| Service order/visibility and displayed number | L17 services() index 01–05 | Service.sort_order/is_visible; number derived in Blade | Services | Services detail block order/number | Audited; planned |

## Categories / tags

One category per demo item; hashtag prefix is code punctuation. Posts relate only to clinical/wellness in the original.

| Public field / collection member | Source location | Proposed database owner | Future admin screen | Public rendering location | Status |
| --- | --- | --- | --- | --- | --- |
| Category clinical.name | app.js L2 clinical; L3/L4 cat; L12 filter order | Category.name {en,bn} | Categories | Blog/gallery category filters/tags; facility gallery only | Audited; planned |
| Category clinical.code | app.js L2 clinical; L3/L4 cat; L12 filter order | Category.code (stable) | Categories | Blog/gallery category filters/tags; facility gallery only | Audited; planned |
| Category clinical.order/visibility | app.js L2 clinical; L3/L4 cat; L12 filter order | Category.sort_order/is_visible | Categories | Blog/gallery category filters/tags; facility gallery only | Audited; planned |
| Category wellness.name | app.js L2 wellness; L3/L4 cat; L12 filter order | Category.name {en,bn} | Categories | Blog/gallery category filters/tags; facility gallery only | Audited; planned |
| Category wellness.code | app.js L2 wellness; L3/L4 cat; L12 filter order | Category.code (stable) | Categories | Blog/gallery category filters/tags; facility gallery only | Audited; planned |
| Category wellness.order/visibility | app.js L2 wellness; L3/L4 cat; L12 filter order | Category.sort_order/is_visible | Categories | Blog/gallery category filters/tags; facility gallery only | Audited; planned |
| Category facility.name | app.js L2 facility; L3/L4 cat; L12 filter order | Category.name {en,bn} | Categories | Blog/gallery category filters/tags; facility gallery only | Audited; planned |
| Category facility.code | app.js L2 facility; L3/L4 cat; L12 filter order | Category.code (stable) | Categories | Blog/gallery category filters/tags; facility gallery only | Audited; planned |
| Category facility.order/visibility | app.js L2 facility; L3/L4 cat; L12 filter order | Category.sort_order/is_visible | Categories | Blog/gallery category filters/tags; facility gallery only | Audited; planned |

## Every article field and paragraph

| Public field / collection member | Source location | Proposed database owner | Future admin screen | Public rendering location | Status |
| --- | --- | --- | --- | --- | --- |
| Post pregnancy-care.slug | app.js L3 articles[id=pregnancy-care].id | Post.slug | Posts | Blog/home card; article detail | Audited; planned |
| Post pregnancy-care.title | app.js L3 articles[id=pregnancy-care].en/bn.title | Post.title {en,bn} | Posts | Blog/home card; article detail | Audited; planned |
| Post pregnancy-care.excerpt | app.js L3 articles[id=pregnancy-care].en/bn.tease | Post.excerpt {en,bn} | Posts | Blog/home card; article detail | Audited; planned |
| Post pregnancy-care.eyebrow | app.js L3 articles[id=pregnancy-care].date (topic label) | Post.eyebrow {en,bn} | Posts | Blog/home card; article detail | Audited; planned |
| Post pregnancy-care.category | app.js L3 articles[id=pregnancy-care].cat | Post.category_id FK | Posts | Blog/home card; article detail | Audited; planned |
| Post pregnancy-care.photo | app.js L3 articles[id=pregnancy-care].photo → PHOTOS.pregnancy | Post.media_asset_id FK | Posts | Blog/home card; article detail | Audited; planned |
| Post pregnancy-care.alt | app.js L3 articles[id=pregnancy-care].localized title, L10/L21 | Post.image_alt {en,bn} | Posts | Blog/home card; article detail | Audited; planned |
| Post pregnancy-care.body paragraph 1 | L3 articles[id=pregnancy-care].en/bn.body[0] | Post.body {en,bn} plain text paragraph 1 | Posts | Article .prose paragraphs | Audited; planned |
| Post pregnancy-care.body paragraph 2 | L3 articles[id=pregnancy-care].en/bn.body[1] | Post.body {en,bn} plain text paragraph 2 | Posts | Article .prose paragraphs | Audited; planned |
| Post pregnancy-care.meta_title | L23 blog label \| brand; article title L3 | Post.meta_title {en,bn} | Posts; metadata | Article head; initial defaults derived explicitly at import | Audited; planned |
| Post pregnancy-care.meta_description | All HTML shared English description; L3 excerpt available | Post.meta_description {en,bn} | Posts; metadata | Article head; initial defaults derived explicitly at import | Audited; planned |
| Post pcos.slug | app.js L3 articles[id=pcos].id | Post.slug | Posts | Blog/home card; article detail | Audited; planned |
| Post pcos.title | app.js L3 articles[id=pcos].en/bn.title | Post.title {en,bn} | Posts | Blog/home card; article detail | Audited; planned |
| Post pcos.excerpt | app.js L3 articles[id=pcos].en/bn.tease | Post.excerpt {en,bn} | Posts | Blog/home card; article detail | Audited; planned |
| Post pcos.eyebrow | app.js L3 articles[id=pcos].date (topic label) | Post.eyebrow {en,bn} | Posts | Blog/home card; article detail | Audited; planned |
| Post pcos.category | app.js L3 articles[id=pcos].cat | Post.category_id FK | Posts | Blog/home card; article detail | Audited; planned |
| Post pcos.photo | app.js L3 articles[id=pcos].photo → PHOTOS.care | Post.media_asset_id FK | Posts | Blog/home card; article detail | Audited; planned |
| Post pcos.alt | app.js L3 articles[id=pcos].localized title, L10/L21 | Post.image_alt {en,bn} | Posts | Blog/home card; article detail | Audited; planned |
| Post pcos.body paragraph 1 | L3 articles[id=pcos].en/bn.body[0] | Post.body {en,bn} plain text paragraph 1 | Posts | Article .prose paragraphs | Audited; planned |
| Post pcos.body paragraph 2 | L3 articles[id=pcos].en/bn.body[1] | Post.body {en,bn} plain text paragraph 2 | Posts | Article .prose paragraphs | Audited; planned |
| Post pcos.meta_title | L23 blog label \| brand; article title L3 | Post.meta_title {en,bn} | Posts; metadata | Article head; initial defaults derived explicitly at import | Audited; planned |
| Post pcos.meta_description | All HTML shared English description; L3 excerpt available | Post.meta_description {en,bn} | Posts; metadata | Article head; initial defaults derived explicitly at import | Audited; planned |
| Post fertility-first-visit.slug | app.js L3 articles[id=fertility-first-visit].id | Post.slug | Posts | Blog/home card; article detail | Audited; planned |
| Post fertility-first-visit.title | app.js L3 articles[id=fertility-first-visit].en/bn.title | Post.title {en,bn} | Posts | Blog/home card; article detail | Audited; planned |
| Post fertility-first-visit.excerpt | app.js L3 articles[id=fertility-first-visit].en/bn.tease | Post.excerpt {en,bn} | Posts | Blog/home card; article detail | Audited; planned |
| Post fertility-first-visit.eyebrow | app.js L3 articles[id=fertility-first-visit].date (topic label) | Post.eyebrow {en,bn} | Posts | Blog/home card; article detail | Audited; planned |
| Post fertility-first-visit.category | app.js L3 articles[id=fertility-first-visit].cat | Post.category_id FK | Posts | Blog/home card; article detail | Audited; planned |
| Post fertility-first-visit.photo | app.js L3 articles[id=fertility-first-visit].photo → PHOTOS.consult | Post.media_asset_id FK | Posts | Blog/home card; article detail | Audited; planned |
| Post fertility-first-visit.alt | app.js L3 articles[id=fertility-first-visit].localized title, L10/L21 | Post.image_alt {en,bn} | Posts | Blog/home card; article detail | Audited; planned |
| Post fertility-first-visit.body paragraph 1 | L3 articles[id=fertility-first-visit].en/bn.body[0] | Post.body {en,bn} plain text paragraph 1 | Posts | Article .prose paragraphs | Audited; planned |
| Post fertility-first-visit.body paragraph 2 | L3 articles[id=fertility-first-visit].en/bn.body[1] | Post.body {en,bn} plain text paragraph 2 | Posts | Article .prose paragraphs | Audited; planned |
| Post fertility-first-visit.meta_title | L23 blog label \| brand; article title L3 | Post.meta_title {en,bn} | Posts; metadata | Article head; initial defaults derived explicitly at import | Audited; planned |
| Post fertility-first-visit.meta_description | All HTML shared English description; L3 excerpt available | Post.meta_description {en,bn} | Posts; metadata | Article head; initial defaults derived explicitly at import | Audited; planned |
| Post menopause.slug | app.js L3 articles[id=menopause].id | Post.slug | Posts | Blog/home card; article detail | Audited; planned |
| Post menopause.title | app.js L3 articles[id=menopause].en/bn.title | Post.title {en,bn} | Posts | Blog/home card; article detail | Audited; planned |
| Post menopause.excerpt | app.js L3 articles[id=menopause].en/bn.tease | Post.excerpt {en,bn} | Posts | Blog/home card; article detail | Audited; planned |
| Post menopause.eyebrow | app.js L3 articles[id=menopause].date (topic label) | Post.eyebrow {en,bn} | Posts | Blog/home card; article detail | Audited; planned |
| Post menopause.category | app.js L3 articles[id=menopause].cat | Post.category_id FK | Posts | Blog/home card; article detail | Audited; planned |
| Post menopause.photo | app.js L3 articles[id=menopause].photo → PHOTOS.doctor | Post.media_asset_id FK | Posts | Blog/home card; article detail | Audited; planned |
| Post menopause.alt | app.js L3 articles[id=menopause].localized title, L10/L21 | Post.image_alt {en,bn} | Posts | Blog/home card; article detail | Audited; planned |
| Post menopause.body paragraph 1 | L3 articles[id=menopause].en/bn.body[0] | Post.body {en,bn} plain text paragraph 1 | Posts | Article .prose paragraphs | Audited; planned |
| Post menopause.body paragraph 2 | L3 articles[id=menopause].en/bn.body[1] | Post.body {en,bn} plain text paragraph 2 | Posts | Article .prose paragraphs | Audited; planned |
| Post menopause.meta_title | L23 blog label \| brand; article title L3 | Post.meta_title {en,bn} | Posts; metadata | Article head; initial defaults derived explicitly at import | Audited; planned |
| Post menopause.meta_description | All HTML shared English description; L3 excerpt available | Post.meta_description {en,bn} | Posts; metadata | Article head; initial defaults derived explicitly at import | Audited; planned |
| Post collection ordering/publication | L3 source order; no actual dates/publication flags | Post.sort_order/status/published_at(nullable) | Posts | Home first 3 published; blog listing; article lookup; demo initially published | Audited; planned |
| Article category/back/read link labels and targets | L10/L21 read/blog; article.html?id=slug; blog.html | ContentField.ui.read; NavigationItem blog.label; route generated from Post.slug/category | Shared UI; Posts; Navigation | Article cards/detail; route shape code-owned | Audited; planned |

## Every gallery item field

| Public field / collection member | Source location | Proposed database owner | Future admin screen | Public rendering location | Status |
| --- | --- | --- | --- | --- | --- |
| Gallery 1.caption | L4 galleries[0].en/bn | GalleryImage(demo-gallery-1).caption {en,bn} | Gallery | Gallery grid/lightbox; home items 1–3 | Audited; planned |
| Gallery 1.alt | L4 galleries[0].caption reused L11 | GalleryImage(demo-gallery-1).alt_text {en,bn} | Gallery | Gallery grid/lightbox; home items 1–3 | Audited; planned |
| Gallery 1.accessible label / lightbox caption | L4 galleries[0].caption reused L11/L23 | GalleryImage(demo-gallery-1).caption {en,bn} (same row) | Gallery | Gallery grid/lightbox; home items 1–3 | Audited; planned |
| Gallery 1.category | L4 galleries[0].cat=facility | GalleryImage(demo-gallery-1).category_id FK | Gallery | Gallery grid/lightbox; home items 1–3 | Audited; planned |
| Gallery 1.photo/lightbox URL | L4 galleries[0].photo=clinic | GalleryImage(demo-gallery-1).media_asset_id FK → local media URL | Gallery | Gallery grid/lightbox; home items 1–3 | Audited; planned |
| Gallery 2.caption | L4 galleries[1].en/bn | GalleryImage(demo-gallery-2).caption {en,bn} | Gallery | Gallery grid/lightbox; home items 1–3 | Audited; planned |
| Gallery 2.alt | L4 galleries[1].caption reused L11 | GalleryImage(demo-gallery-2).alt_text {en,bn} | Gallery | Gallery grid/lightbox; home items 1–3 | Audited; planned |
| Gallery 2.accessible label / lightbox caption | L4 galleries[1].caption reused L11/L23 | GalleryImage(demo-gallery-2).caption {en,bn} (same row) | Gallery | Gallery grid/lightbox; home items 1–3 | Audited; planned |
| Gallery 2.category | L4 galleries[1].cat=clinical | GalleryImage(demo-gallery-2).category_id FK | Gallery | Gallery grid/lightbox; home items 1–3 | Audited; planned |
| Gallery 2.photo/lightbox URL | L4 galleries[1].photo=consult | GalleryImage(demo-gallery-2).media_asset_id FK → local media URL | Gallery | Gallery grid/lightbox; home items 1–3 | Audited; planned |
| Gallery 3.caption | L4 galleries[2].en/bn | GalleryImage(demo-gallery-3).caption {en,bn} | Gallery | Gallery grid/lightbox; home items 1–3 | Audited; planned |
| Gallery 3.alt | L4 galleries[2].caption reused L11 | GalleryImage(demo-gallery-3).alt_text {en,bn} | Gallery | Gallery grid/lightbox; home items 1–3 | Audited; planned |
| Gallery 3.accessible label / lightbox caption | L4 galleries[2].caption reused L11/L23 | GalleryImage(demo-gallery-3).caption {en,bn} (same row) | Gallery | Gallery grid/lightbox; home items 1–3 | Audited; planned |
| Gallery 3.category | L4 galleries[2].cat=facility | GalleryImage(demo-gallery-3).category_id FK | Gallery | Gallery grid/lightbox; home items 1–3 | Audited; planned |
| Gallery 3.photo/lightbox URL | L4 galleries[2].photo=lab | GalleryImage(demo-gallery-3).media_asset_id FK → local media URL | Gallery | Gallery grid/lightbox; home items 1–3 | Audited; planned |
| Gallery 4.caption | L4 galleries[3].en/bn | GalleryImage(demo-gallery-4).caption {en,bn} | Gallery | Gallery grid/lightbox; home items 1–3 | Audited; planned |
| Gallery 4.alt | L4 galleries[3].caption reused L11 | GalleryImage(demo-gallery-4).alt_text {en,bn} | Gallery | Gallery grid/lightbox; home items 1–3 | Audited; planned |
| Gallery 4.accessible label / lightbox caption | L4 galleries[3].caption reused L11/L23 | GalleryImage(demo-gallery-4).caption {en,bn} (same row) | Gallery | Gallery grid/lightbox; home items 1–3 | Audited; planned |
| Gallery 4.category | L4 galleries[3].cat=wellness | GalleryImage(demo-gallery-4).category_id FK | Gallery | Gallery grid/lightbox; home items 1–3 | Audited; planned |
| Gallery 4.photo/lightbox URL | L4 galleries[3].photo=pregnancy | GalleryImage(demo-gallery-4).media_asset_id FK → local media URL | Gallery | Gallery grid/lightbox; home items 1–3 | Audited; planned |
| Gallery 5.caption | L4 galleries[4].en/bn | GalleryImage(demo-gallery-5).caption {en,bn} | Gallery | Gallery grid/lightbox; home items 1–3 | Audited; planned |
| Gallery 5.alt | L4 galleries[4].caption reused L11 | GalleryImage(demo-gallery-5).alt_text {en,bn} | Gallery | Gallery grid/lightbox; home items 1–3 | Audited; planned |
| Gallery 5.accessible label / lightbox caption | L4 galleries[4].caption reused L11/L23 | GalleryImage(demo-gallery-5).caption {en,bn} (same row) | Gallery | Gallery grid/lightbox; home items 1–3 | Audited; planned |
| Gallery 5.category | L4 galleries[4].cat=clinical | GalleryImage(demo-gallery-5).category_id FK | Gallery | Gallery grid/lightbox; home items 1–3 | Audited; planned |
| Gallery 5.photo/lightbox URL | L4 galleries[4].photo=team | GalleryImage(demo-gallery-5).media_asset_id FK → local media URL | Gallery | Gallery grid/lightbox; home items 1–3 | Audited; planned |
| Gallery 6.caption | L4 galleries[5].en/bn | GalleryImage(demo-gallery-6).caption {en,bn} | Gallery | Gallery grid/lightbox; home items 1–3 | Audited; planned |
| Gallery 6.alt | L4 galleries[5].caption reused L11 | GalleryImage(demo-gallery-6).alt_text {en,bn} | Gallery | Gallery grid/lightbox; home items 1–3 | Audited; planned |
| Gallery 6.accessible label / lightbox caption | L4 galleries[5].caption reused L11/L23 | GalleryImage(demo-gallery-6).caption {en,bn} (same row) | Gallery | Gallery grid/lightbox; home items 1–3 | Audited; planned |
| Gallery 6.category | L4 galleries[5].cat=facility | GalleryImage(demo-gallery-6).category_id FK | Gallery | Gallery grid/lightbox; home items 1–3 | Audited; planned |
| Gallery 6.photo/lightbox URL | L4 galleries[5].photo=interior | GalleryImage(demo-gallery-6).media_asset_id FK → local media URL | Gallery | Gallery grid/lightbox; home items 1–3 | Audited; planned |
| Gallery 7.caption | L4 galleries[6].en/bn | GalleryImage(demo-gallery-7).caption {en,bn} | Gallery | Gallery grid/lightbox; home items 1–3 | Audited; planned |
| Gallery 7.alt | L4 galleries[6].caption reused L11 | GalleryImage(demo-gallery-7).alt_text {en,bn} | Gallery | Gallery grid/lightbox; home items 1–3 | Audited; planned |
| Gallery 7.accessible label / lightbox caption | L4 galleries[6].caption reused L11/L23 | GalleryImage(demo-gallery-7).caption {en,bn} (same row) | Gallery | Gallery grid/lightbox; home items 1–3 | Audited; planned |
| Gallery 7.category | L4 galleries[6].cat=wellness | GalleryImage(demo-gallery-7).category_id FK | Gallery | Gallery grid/lightbox; home items 1–3 | Audited; planned |
| Gallery 7.photo/lightbox URL | L4 galleries[6].photo=care | GalleryImage(demo-gallery-7).media_asset_id FK → local media URL | Gallery | Gallery grid/lightbox; home items 1–3 | Audited; planned |
| Gallery 8.caption | L4 galleries[7].en/bn | GalleryImage(demo-gallery-8).caption {en,bn} | Gallery | Gallery grid/lightbox; home items 1–3 | Audited; planned |
| Gallery 8.alt | L4 galleries[7].caption reused L11 | GalleryImage(demo-gallery-8).alt_text {en,bn} | Gallery | Gallery grid/lightbox; home items 1–3 | Audited; planned |
| Gallery 8.accessible label / lightbox caption | L4 galleries[7].caption reused L11/L23 | GalleryImage(demo-gallery-8).caption {en,bn} (same row) | Gallery | Gallery grid/lightbox; home items 1–3 | Audited; planned |
| Gallery 8.category | L4 galleries[7].cat=clinical | GalleryImage(demo-gallery-8).category_id FK | Gallery | Gallery grid/lightbox; home items 1–3 | Audited; planned |
| Gallery 8.photo/lightbox URL | L4 galleries[7].photo=doctor | GalleryImage(demo-gallery-8).media_asset_id FK → local media URL | Gallery | Gallery grid/lightbox; home items 1–3 | Audited; planned |
| Gallery collection order/visibility | L4 source order; L11 slice limits | GalleryImage.sort_order/is_visible | Gallery | Visible grid/home first 3 | Audited; planned |

## Media assets and shared files

Technical source URL is provenance. Local files must be acquired/uploaded; no source script reads or runtime seed fallback. Slot-specific alt overrides default_alt through the common localization service.

| Public field / collection member | Source location | Proposed database owner | Future admin screen | Public rendering location | Status |
| --- | --- | --- | --- | --- | --- |
| Media hero.source / file | app.js L1 PHOTOS.hero; exact URL in audit | MediaAsset(demo-photo-hero).source_url/path | Media library | Local image source for Home hero | Audited; planned |
| Media hero.title | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.title {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Audited; planned |
| Media hero.default_alt | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.default_alt {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Audited; planned |
| Media hero.credit | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.credit {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Audited; planned |
| Media hero.caveat | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.caveat {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Audited; planned |
| Media doctor.source / file | app.js L1 PHOTOS.doctor; exact URL in audit | MediaAsset(demo-photo-doctor).source_url/path | Media library | Local image source for menopause article; gallery #8 | Audited; planned |
| Media doctor.title | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.title {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Audited; planned |
| Media doctor.default_alt | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.default_alt {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Audited; planned |
| Media doctor.credit | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.credit {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Audited; planned |
| Media doctor.caveat | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.caveat {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Audited; planned |
| Media consult.source / file | app.js L1 PHOTOS.consult; exact URL in audit | MediaAsset(demo-photo-consult).source_url/path | Media library | Local image source for Home doctor split; fertility-first-visit article; gallery #2 | Audited; planned |
| Media consult.title | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.title {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Audited; planned |
| Media consult.default_alt | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.default_alt {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Audited; planned |
| Media consult.credit | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.credit {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Audited; planned |
| Media consult.caveat | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.caveat {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Audited; planned |
| Media clinic.source / file | app.js L1 PHOTOS.clinic; exact URL in audit | MediaAsset(demo-photo-clinic).source_url/path | Media library | Local image source for gallery #1 | Audited; planned |
| Media clinic.title | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.title {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Audited; planned |
| Media clinic.default_alt | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.default_alt {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Audited; planned |
| Media clinic.credit | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.credit {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Audited; planned |
| Media clinic.caveat | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.caveat {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Audited; planned |
| Media lab.source / file | app.js L1 PHOTOS.lab; exact URL in audit | MediaAsset(demo-photo-lab).source_url/path | Media library | Local image source for Diagnostics split; gallery #3 | Audited; planned |
| Media lab.title | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.title {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Audited; planned |
| Media lab.default_alt | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.default_alt {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Audited; planned |
| Media lab.credit | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.credit {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Audited; planned |
| Media lab.caveat | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.caveat {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Audited; planned |
| Media pregnancy.source / file | app.js L1 PHOTOS.pregnancy; exact URL in audit | MediaAsset(demo-photo-pregnancy).source_url/path | Media library | Local image source for pregnancy-care article; gallery #4 | Audited; planned |
| Media pregnancy.title | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.title {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Audited; planned |
| Media pregnancy.default_alt | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.default_alt {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Audited; planned |
| Media pregnancy.credit | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.credit {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Audited; planned |
| Media pregnancy.caveat | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.caveat {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Audited; planned |
| Media care.source / file | app.js L1 PHOTOS.care; exact URL in audit | MediaAsset(demo-photo-care).source_url/path | Media library | Local image source for About split; pcos article; gallery #7 | Audited; planned |
| Media care.title | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.title {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Audited; planned |
| Media care.default_alt | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.default_alt {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Audited; planned |
| Media care.credit | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.credit {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Audited; planned |
| Media care.caveat | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.caveat {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Audited; planned |
| Media interior.source / file | app.js L1 PHOTOS.interior; exact URL in audit | MediaAsset(demo-photo-interior).source_url/path | Media library | Local image source for gallery #6 | Audited; planned |
| Media interior.title | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.title {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Audited; planned |
| Media interior.default_alt | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.default_alt {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Audited; planned |
| Media interior.credit | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.credit {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Audited; planned |
| Media interior.caveat | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.caveat {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Audited; planned |
| Media team.source / file | app.js L1 PHOTOS.team; exact URL in audit | MediaAsset(demo-photo-team).source_url/path | Media library | Local image source for gallery #5 | Audited; planned |
| Media team.title | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.title {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Audited; planned |
| Media team.default_alt | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.default_alt {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Audited; planned |
| Media team.credit | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.credit {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Audited; planned |
| Media team.caveat | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.caveat {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Audited; planned |
| Media file technical metadata | External assets; none bundled | MediaAsset.mime_type/size/width/height/disk/original_filename | Media library | Safe dimensions/storage URLs; not editor-written copy | Audited; planned |

## Literal headings, shared links, photos, metadata, forms and code-owned presentation

| Public field / collection member | Source location | Proposed database owner | Future admin screen | Public rendering location | Status |
| --- | --- | --- | --- | --- | --- |
| Clinic display name / CTA, diagnostics, contact eyebrow | L8/L18/L22 DEEPAN IUI LAB | SiteSetting.clinic_name {en,bn} | Shared clinic settings | Three reused eyebrow slots; visit format | Audited; planned |
| Official Facebook target | L7/L22 https://www.facebook.com/drshahalamtalukder/ | SiteSetting.facebook_url {en,bn} | Shared clinic settings | Footer/contact standalone link; never submit action | Audited; planned |
| Google Maps target | L22 https://www.google.com/maps/search/?api=1&query=4S+Tower+628+East+Bogura+Road+Barishal | SiteSetting.map_url {en,bn} | Shared clinic settings | Contact map href | Audited; planned |
| Footer social label | L7 Facebook ↗ | ContentField.footer.social_label {en,bn} | Footer/shared copy | Footer standalone social anchor | Audited; planned |
| Copyright format/year/caveat | L7 © current year brand · galleryNote | ContentField.footer.copyright_format {en,bn}; allowed {year},{doctor_name},{stock_notice} | Footer/shared copy | Footer-bottom; year server-derived; canonical settings/notice | Audited; planned |
| Brand link / mark | L6/L7 index.html; ✳ | ContentField.shared.brand_target {en,bn}; SiteSetting.brand_icon enum | Shared settings/copy | Header logo href; header/footer brandmark | Audited; planned |
| Home location_eyebrow | L14 BARISHAL | ContentField.home.location_eyebrow {en,bn} | Home page fields | Home floating/care/journal/gallery headings | Audited; planned |
| Home care_eyebrow | L14 OUR AREAS OF CARE | ContentField.home.care_eyebrow {en,bn} | Home page fields | Home floating/care/journal/gallery headings | Audited; planned |
| Home journal_eyebrow | L14 THE JOURNAL | ContentField.home.journal_eyebrow {en,bn} | Home page fields | Home floating/care/journal/gallery headings | Audited; planned |
| Home gallery_eyebrow | L14 VISUAL STORIES | ContentField.home.gallery_eyebrow {en,bn} | Home page fields | Home floating/care/journal/gallery headings | Audited; planned |
| hero photo slot | L14 home PHOTOS.hero | ContentField.hero.image media_asset_id FK | Page image slots | Home/about/diagnostics image; shape/class code-owned | Audited; planned |
| hero image alt | L14 home Illustrative healthcare portrait — not the actual doctor | ContentField.hero.image_alt {en,bn} | Page image slots | Corresponding img alt; original caveat retained | Audited; planned |
| doctor photo slot | L14 home PHOTOS.consult | ContentField.doctor.image media_asset_id FK | Page image slots | Home/about/diagnostics image; shape/class code-owned | Audited; planned |
| doctor image alt | L14 home Healthcare consultation illustration | ContentField.doctor.image_alt {en,bn} | Page image slots | Corresponding img alt; original caveat retained | Audited; planned |
| about photo slot | L15 about PHOTOS.care | ContentField.about.image media_asset_id FK | Page image slots | Home/about/diagnostics image; shape/class code-owned | Audited; planned |
| about image alt | L15 about Illustrative consultation photo | ContentField.about.image_alt {en,bn} | Page image slots | Corresponding img alt; original caveat retained | Audited; planned |
| diagnostics photo slot | L18 diagnostics PHOTOS.lab | ContentField.diagnostics.image media_asset_id FK | Page image slots | Home/about/diagnostics image; shape/class code-owned | Audited; planned |
| diagnostics image alt | L18 diagnostics Illustrative lab stock photograph | ContentField.diagnostics.image_alt {en,bn} | Page image slots | Corresponding img alt; original caveat retained | Audited; planned |
| UI switch_to_bn | L6 বাংলা | ContentField.ui.switch_to_bn {en,bn} | Shared UI copy | Language/menu/dialog accessibility | Audited; planned |
| UI switch_to_en | L6 English | ContentField.ui.switch_to_en {en,bn} | Shared UI copy | Language/menu/dialog accessibility | Audited; planned |
| UI open_navigation | L6 Open navigation | ContentField.ui.open_navigation {en,bn} | Shared UI copy | Language/menu/dialog accessibility | Audited; planned |
| UI gallery_preview | L23 Gallery image preview | ContentField.ui.gallery_preview {en,bn} | Shared UI copy | Language/menu/dialog accessibility | Audited; planned |
| UI close_lightbox | L23 Close | ContentField.ui.close_lightbox {en,bn} | Shared UI copy | Language/menu/dialog accessibility | Audited; planned |
| Page title format | L23 localized page label \| brand | ContentField.meta.title_format {en,bn}; allowed page_label/doctor_name | Page metadata | Public title; actual post title intentional improvement | Audited; planned |
| about.meta_title | frontend/about.html head; L23 dynamic title | ContentField.meta.about.meta_title {en,bn} | Page metadata | Page head; eight types; no stock source meta fallback | Audited; planned |
| about.meta_description | frontend/about.html head; L23 dynamic title | ContentField.meta.about.meta_description {en,bn} | Page metadata | Page head; eight types; no stock source meta fallback | Audited; planned |
| article.meta_title | frontend/article.html head; L23 dynamic title | ContentField.meta.article.meta_title {en,bn} | Page metadata | Page head; eight types; no stock source meta fallback | Audited; planned |
| article.meta_description | frontend/article.html head; L23 dynamic title | ContentField.meta.article.meta_description {en,bn} | Page metadata | Page head; eight types; no stock source meta fallback | Audited; planned |
| blog.meta_title | frontend/blog.html head; L23 dynamic title | ContentField.meta.blog.meta_title {en,bn} | Page metadata | Page head; eight types; no stock source meta fallback | Audited; planned |
| blog.meta_description | frontend/blog.html head; L23 dynamic title | ContentField.meta.blog.meta_description {en,bn} | Page metadata | Page head; eight types; no stock source meta fallback | Audited; planned |
| contact.meta_title | frontend/contact.html head; L23 dynamic title | ContentField.meta.contact.meta_title {en,bn} | Page metadata | Page head; eight types; no stock source meta fallback | Audited; planned |
| contact.meta_description | frontend/contact.html head; L23 dynamic title | ContentField.meta.contact.meta_description {en,bn} | Page metadata | Page head; eight types; no stock source meta fallback | Audited; planned |
| diagnostics.meta_title | frontend/diagnostics.html head; L23 dynamic title | ContentField.meta.diagnostics.meta_title {en,bn} | Page metadata | Page head; eight types; no stock source meta fallback | Audited; planned |
| diagnostics.meta_description | frontend/diagnostics.html head; L23 dynamic title | ContentField.meta.diagnostics.meta_description {en,bn} | Page metadata | Page head; eight types; no stock source meta fallback | Audited; planned |
| gallery.meta_title | frontend/gallery.html head; L23 dynamic title | ContentField.meta.gallery.meta_title {en,bn} | Page metadata | Page head; eight types; no stock source meta fallback | Audited; planned |
| gallery.meta_description | frontend/gallery.html head; L23 dynamic title | ContentField.meta.gallery.meta_description {en,bn} | Page metadata | Page head; eight types; no stock source meta fallback | Audited; planned |
| home.meta_title | frontend/index.html head; L23 dynamic title | ContentField.meta.home.meta_title {en,bn} | Page metadata | Page head; eight types; no stock source meta fallback | Audited; planned |
| home.meta_description | frontend/index.html head; L23 dynamic title | ContentField.meta.home.meta_description {en,bn} | Page metadata | Page head; eight types; no stock source meta fallback | Audited; planned |
| services.meta_title | frontend/services.html head; L23 dynamic title | ContentField.meta.services.meta_title {en,bn} | Page metadata | Page head; eight types; no stock source meta fallback | Audited; planned |
| services.meta_description | frontend/services.html head; L23 dynamic title | ContentField.meta.services.meta_description {en,bn} | Page metadata | Page head; eight types; no stock source meta fallback | Audited; planned |
| Theme color/fonts/design classes | All HTML #205d54; style.css L1/L2 | Code-owned fixed design, not content JSON | None | Public layout CSS/font loading | Audited; planned |
| Shared section visibility and home collection limit/order | L14 sections; L10/L11 limits 3; L8 CTA | ContentField.is_visible only for registry-approved optional sections; Post/GalleryImage sort_order; limits code-defined | Pages/collection screens | Fixed sections; first 3 items; required caveats cannot be hidden | Audited; planned |
| home.primary_target | L14 contact.html | ContentField.home.primary_target {en,bn}; known route allowlist | Page fields/CTA | Home appointment CTA | Audited; planned |
| home.secondary_target | L14 services.html | ContentField.home.secondary_target {en,bn}; known route allowlist | Page fields/CTA | Home explore services | Audited; planned |
| home.doctor_target | L14 about.html | ContentField.home.doctor_target {en,bn}; known route allowlist | Page fields/CTA | Home doctor details | Audited; planned |
| home.journal_target | L14 blog.html | ContentField.home.journal_target {en,bn}; known route allowlist | Page fields/CTA | Home journal details | Audited; planned |
| home.gallery_target | L14 gallery.html | ContentField.home.gallery_target {en,bn}; known route allowlist | Page fields/CTA | Home gallery details | Audited; planned |
| cta.target | L8 contact.html | ContentField.cta.target {en,bn}; known route allowlist | Page fields/CTA | Shared CTA | Audited; planned |
| diagnostics.contact_target | L18 contact.html | ContentField.diagnostics.contact_target {en,bn}; known route allowlist | Page fields/CTA | Diagnostic contact link | Audited; planned |
| Form consultation option collection | L22 obtitle/gytitle/iuititle/diatitle | CareCard.title {en,bn}; technical import_key values | Care cards/contact form | Appointment select only; general stores no topic | Audited; planned |
| Original clipboard text/success (historical) | L23 bilingual header; Name/Phone/Topic/Message; Copied! / কপি হয়েছে! | Audit provenance only; replaced, no production runtime field | None | Removed from new submission behavior | Audited; planned |
| New UI/form mode_label | Owner contract / architecture addition; no original string | ContentField.ui/contact.mode_label {en,bn} | Shared UI/contact copy | Mode selector label | New; planned |
| New UI/form mode_appointment | Owner contract / architecture addition; no original string | ContentField.ui/contact.mode_appointment {en,bn} | Shared UI/contact copy | Appointment selector option | New; planned |
| New UI/form mode_general | Owner contract / architecture addition; no original string | ContentField.ui/contact.mode_general {en,bn} | Shared UI/contact copy | General contact option | New; planned |
| New UI/form submit_success | Owner contract / architecture addition; no original string | ContentField.ui/contact.submit_success {en,bn} | Shared UI/contact copy | Saved-success flash/status | New; planned |
| New UI/form not_confirmed_notice | Owner contract / architecture addition; no original string | ContentField.ui/contact.not_confirmed_notice {en,bn} | Shared UI/contact copy | Always-present appointment-request caveat | New; planned |
| New UI/form appointment_guidance | Owner contract / architecture addition; no original string | ContentField.ui/contact.appointment_guidance {en,bn} | Shared UI/contact copy | Appointment form helper | New; planned |
| New UI/form general_guidance | Owner contract / architecture addition; no original string | ContentField.ui/contact.general_guidance {en,bn} | Shared UI/contact copy | General message form helper | New; planned |
| New UI/form mode_apply | Owner contract / architecture addition; no original string | ContentField.ui/contact.mode_apply {en,bn} | Shared UI/contact copy | No-JS GET mode switch action | New; planned |
| New UI/form validation_required | Owner contract / architecture addition; no original string | ContentField.ui/contact.validation_required {en,bn} | Shared UI/contact copy | Required field errors | New; planned |
| New UI/form validation_invalid | Owner contract / architecture addition; no original string | ContentField.ui/contact.validation_invalid {en,bn} | Shared UI/contact copy | Invalid input errors | New; planned |
| New UI/form validation_max | Owner contract / architecture addition; no original string | ContentField.ui/contact.validation_max {en,bn} | Shared UI/contact copy | Length validation errors | New; planned |
| New UI/form save_failure | Owner contract / architecture addition; no original string | ContentField.ui/contact.save_failure {en,bn} | Shared UI/contact copy | No-success failure message | New; planned |
| New UI/form submit_pending | Owner contract / architecture addition; no original string | ContentField.ui/contact.submit_pending {en,bn} | Shared UI/contact copy | Optional disabled button status | New; planned |
| New UI/form empty_filter_results | Owner contract / architecture addition; no original string | ContentField.ui/contact.empty_filter_results {en,bn} | Shared UI/contact copy | Blog/gallery empty state | New; planned |
| New UI/form menu_close | Owner contract / architecture addition; no original string | ContentField.ui/contact.menu_close {en,bn} | Shared UI/contact copy | Navigation accessibility | New; planned |
| New UI/form lightbox_previous_focus | Owner contract / architecture addition; no original string | ContentField.ui/contact.lightbox_previous_focus {en,bn} | Shared UI/contact copy | Accessible enhancement copy if displayed | New; planned |
| Inquiry form name/phone/message/locale/type values | L22 inputs; new small mode selector | Inquiry.name/phone/message/locale/type private input; not localized public website text | Private inquiries | Contact POST → PRG only; never public list | New; planned |
| Inquiry consultation_type/status/admin note | L22 topic; private workflow addition | Inquiry.consultation_type nullable enum/status/admin_note/handled_at | Private inquiries | Topic field for appointment only; admin status/note never public | New; planned |

## Implementation notes

- Registry names above are the proposed contract; reconcile final names when implementing without dropping any row. All key/URL/format types have code-defined validation. Admin editors expose individual bilingual fields, not JSON payload editing.
- The 81 originals are initialization data, not runtime fallback. Old inquiry privacy/CTA text is intentionally revised; all four full articles, gallery captions and services descriptions retain the original caveats and content.
- Header/footer item labels can be edited per group. Shared identity/contact facts are single settings. Card link-label copies are deliberate collection ownership; shared read/view-more/filter copy remains single registered fields.
- Article metadata is individually editable. Original topic words are eyebrows; no real dates were supplied. Keep published_at nullable while published status displays the initial demo.
- Fixed glyphs (menu/close/arrows), bullet ornament and service numbering are presentation code. Selectable branding/care icons are safe codes in structured admin controls; no raw SVG/HTML.
- New query/login/filter/language/mode-switch forms have purpose-specific routes; only POST /inquiries writes private inquiry records.
