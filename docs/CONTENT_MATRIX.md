# Public content ownership matrix

Updated after Step 5, 2026-10-10. `frontend/` is the unmodified reference. `Imported; admin pending` means database ownership/initial value and its documented public rendering are implemented and verified; content editors remain future work. Admin authentication/dashboard/profile and reusable controls are now implemented. `Implemented; admin pending` covers private inquiry/request behavior whose staff screens remain pending. The 81-key table uses actual destinations. Structural layout/CSS/glyphs are code-owned. Shared identity/address/hours are canonical settings, never per-page copies. All managed text uses `{en,bn}` and the shared LocalizedContent resolver. The reconciliation sections describe actual schema and reusable rendering components.

The first table contains every one of the 81 dictionary keys, including unused blogdesc, with individual source, DB owner, admin screen, rendering use and status. Subsequent tables enumerate collection fields and extra literal/structural copy. Exact bilingual values and slugs are preserved in FRONTEND_AUDIT. Shared fact prose uses allowlisted format placeholders as specified in ARCHITECTURE.


## Dictionary fields: 81 rows

| Public field | Exact source | Proposed database owner | Future admin screen | Public rendering location | Status |
| --- | --- | --- | --- | --- | --- |
| home | frontend/assets/app.js L2 content.en/bn.home | NavigationItem(header:home).label; Page(home).label | Navigation; Page labels | Header; matching page hero/title; footer subset via footer items | Imported; admin pending |
| about | frontend/assets/app.js L2 content.en/bn.about | NavigationItem(header:about).label; Page(about).label; NavigationItem(footer:about).label | Navigation; Page labels | Header; matching page hero/title; footer subset via footer items | Imported; admin pending |
| services | frontend/assets/app.js L2 content.en/bn.services | NavigationItem(header:services).label; Page(services).label; NavigationItem(footer:services).label | Navigation; Page labels | Header; matching page hero/title; footer subset via footer items | Imported; admin pending |
| diagnostics | frontend/assets/app.js L2 content.en/bn.diagnostics | NavigationItem(header:diagnostics).label; Page(diagnostics).label | Navigation; Page labels | Header; matching page hero/title; footer subset via footer items | Imported; admin pending |
| blog | frontend/assets/app.js L2 content.en/bn.blog | NavigationItem(header:blog).label; Page(blog).label; NavigationItem(footer:blog).label; Page(article).label | Navigation; Page labels | Header; matching page hero/title; footer subset via footer items | Imported; admin pending |
| gallery | frontend/assets/app.js L2 content.en/bn.gallery | NavigationItem(header:gallery).label; Page(gallery).label; NavigationItem(footer:gallery).label | Navigation; Page labels | Header; matching page hero/title; footer subset via footer items | Imported; admin pending |
| contact | frontend/assets/app.js L2 content.en/bn.contact | NavigationItem(header:contact).label; Page(contact).label | Navigation; Page labels | Header; matching page hero/title; footer subset via footer items | Imported; admin pending |
| appointment | frontend/assets/app.js L2 content.en/bn.appointment | ContentField(home.appointment).value | Home page fields | Corresponding home hero/care/doctor/journal slot; herodesc permits canonical fact placeholders | Imported; admin pending |
| brand | frontend/assets/app.js L2 content.en/bn.brand | SiteSetting(primary).doctor_name | Shared settings | Brand/header/footer; about; page-title format | Imported; admin pending |
| subtitle | frontend/assets/app.js L2 content.en/bn.subtitle | SiteSetting(primary).subtitle | Shared settings | Header brand subtitle | Imported; admin pending |
| eyebrow | frontend/assets/app.js L2 content.en/bn.eyebrow | ContentField(home.eyebrow).value | Home page fields | Corresponding home hero/care/doctor/journal slot; herodesc permits canonical fact placeholders | Imported; admin pending |
| herotitle | frontend/assets/app.js L2 content.en/bn.herotitle | ContentField(home.herotitle).value | Home page fields | Corresponding home hero/care/doctor/journal slot; herodesc permits canonical fact placeholders | Imported; admin pending |
| herodesc | frontend/assets/app.js L2 content.en/bn.herodesc | ContentField(home.herodesc).value | Home page fields | Corresponding home hero/care/doctor/journal slot; herodesc permits canonical fact placeholders | Imported; admin pending |
| credential | frontend/assets/app.js L2 content.en/bn.credential | SiteSetting(primary).credential | Shared doctor settings | Home stats/bullets; about bullets; canonical prose placeholders | Imported; admin pending |
| department | frontend/assets/app.js L2 content.en/bn.department | SiteSetting(primary).department | Shared doctor settings | Home stats/bullets; about bullets; canonical prose placeholders | Imported; admin pending |
| place | frontend/assets/app.js L2 content.en/bn.place | SiteSetting(primary).institution | Shared doctor settings | Home stats/bullets; about bullets; canonical prose placeholders | Imported; admin pending |
| visit | frontend/assets/app.js L2 content.en/bn.visit | ContentField(home.visit).value | Home page fields | Corresponding home hero/care/doctor/journal slot; herodesc permits canonical fact placeholders | Imported; admin pending |
| explore | frontend/assets/app.js L2 content.en/bn.explore | ContentField(ui.explore).value | Shared UI copy | explore: home services link/footer heading; read: article action; readmore: home about/blog/gallery links | Imported; admin pending |
| servicesTitle | frontend/assets/app.js L2 content.en/bn.servicesTitle | ContentField(home.servicesTitle).value | Home page fields | Corresponding home hero/care/doctor/journal slot; herodesc permits canonical fact placeholders | Imported; admin pending |
| servicesDesc | frontend/assets/app.js L2 content.en/bn.servicesDesc | ContentField(home.servicesDesc).value | Home page fields | Corresponding home hero/care/doctor/journal slot; herodesc permits canonical fact placeholders | Imported; admin pending |
| obtitle | frontend/assets/app.js L2 content.en/bn.obtitle | CareCard(obstetrics).title; ConsultationOption(obstetrics).label | Homepage care cards | Homepage card; appointment topic option | Imported; admin pending |
| obdesc | frontend/assets/app.js L2 content.en/bn.obdesc | CareCard(obstetrics).description | Homepage care cards | Homepage card description | Imported; admin pending |
| gytitle | frontend/assets/app.js L2 content.en/bn.gytitle | CareCard(gynecology).title; ConsultationOption(gynecology).label | Homepage care cards | Homepage card; appointment topic option | Imported; admin pending |
| gydesc | frontend/assets/app.js L2 content.en/bn.gydesc | CareCard(gynecology).description | Homepage care cards | Homepage card description | Imported; admin pending |
| iuititle | frontend/assets/app.js L2 content.en/bn.iuititle | CareCard(fertility).title; ConsultationOption(fertility).label | Homepage care cards | Homepage card; appointment topic option | Imported; admin pending |
| iuidesc | frontend/assets/app.js L2 content.en/bn.iuidesc | CareCard(fertility).description | Homepage care cards | Homepage card description | Imported; admin pending |
| diatitle | frontend/assets/app.js L2 content.en/bn.diatitle | CareCard(diagnostics).title; ConsultationOption(diagnostics).label | Homepage care cards | Homepage card; appointment topic option | Imported; admin pending |
| diadesc | frontend/assets/app.js L2 content.en/bn.diadesc | CareCard(diagnostics).description | Homepage care cards | Homepage card description | Imported; admin pending |
| learn | frontend/assets/app.js L2 content.en/bn.learn | CareCard(all four).link_label | Homepage care cards | Each home care-card action label | Imported; admin pending |
| doctorEyebrow | frontend/assets/app.js L2 content.en/bn.doctorEyebrow | ContentField(home.doctorEyebrow).value | Home page fields | Corresponding home hero/care/doctor/journal slot; herodesc permits canonical fact placeholders | Imported; admin pending |
| doctorTitle | frontend/assets/app.js L2 content.en/bn.doctorTitle | ContentField(home.doctorTitle).value | Home page fields | Corresponding home hero/care/doctor/journal slot; herodesc permits canonical fact placeholders | Imported; admin pending |
| doctorText | frontend/assets/app.js L2 content.en/bn.doctorText | SiteSetting(primary).doctor_biography | Shared doctor settings | Home doctor split; about biography | Imported; admin pending |
| qualification | frontend/assets/app.js L2 content.en/bn.qualification | ContentField(shared.qualification).value | Shared copy | qualification: about; visitTitle: about/diagnostics; hoursTitle: about | Imported; admin pending |
| profession | frontend/assets/app.js L2 content.en/bn.profession | ContentField(shared.profession).value | Shared copy | Profession: home stats; visitText: home floating/about/diagnostics; references canonical settings | Imported; admin pending |
| visitTitle | frontend/assets/app.js L2 content.en/bn.visitTitle | ContentField(shared.visitTitle).value | Shared copy | qualification: about; visitTitle: about/diagnostics; hoursTitle: about | Imported; admin pending |
| visitText | frontend/assets/app.js L2 content.en/bn.visitText | ContentField(shared.visitText).value | Shared copy | Profession: home stats; visitText: home floating/about/diagnostics; references canonical settings | Imported; admin pending |
| hoursTitle | frontend/assets/app.js L2 content.en/bn.hoursTitle | ContentField(shared.hoursTitle).value | Shared copy | qualification: about; visitTitle: about/diagnostics; hoursTitle: about | Imported; admin pending |
| hours | frontend/assets/app.js L2 content.en/bn.hours | SiteSetting(primary).consultation_hours | Shared clinic settings | Topbar; about hours card; contact; footer | Imported; admin pending |
| friday | frontend/assets/app.js L2 content.en/bn.friday | SiteSetting(primary).friday_note | Shared clinic settings | Topbar; about hours card; contact; footer | Imported; admin pending |
| blogtitle | frontend/assets/app.js L2 content.en/bn.blogtitle | ContentField(home.blogtitle).value | Home page fields | Corresponding home hero/care/doctor/journal slot; herodesc permits canonical fact placeholders | Imported; admin pending |
| blogdesc | frontend/assets/app.js L2 content.en/bn.blogdesc | ContentField(blog.blogdesc).value | Blog page fields | Not rendered in original; reserved editable educational copy | Imported; admin pending |
| gallerytitle | frontend/assets/app.js L2 content.en/bn.gallerytitle | ContentField(shared.gallerytitle).value | Gallery and shared notices | Gallery hero/home preview; galleryNote: gallery/about/footer copyright | Imported; admin pending |
| gallerydesc | frontend/assets/app.js L2 content.en/bn.gallerydesc | ContentField(shared.gallerydesc).value | Gallery and shared notices | Gallery hero/home preview; galleryNote: gallery/about/footer copyright | Imported; admin pending |
| all | frontend/assets/app.js L2 content.en/bn.all | ContentField(ui.filter_all).value | Shared UI copy | Blog and gallery filters | Imported; admin pending |
| clinical | frontend/assets/app.js L2 content.en/bn.clinical | Category(clinical).label | Categories | Filter buttons/category tags; facility gallery only | Imported; admin pending |
| wellness | frontend/assets/app.js L2 content.en/bn.wellness | Category(wellness).label | Categories | Filter buttons/category tags; facility gallery only | Imported; admin pending |
| facility | frontend/assets/app.js L2 content.en/bn.facility | Category(facility).label | Categories | Filter buttons/category tags; facility gallery only | Imported; admin pending |
| read | frontend/assets/app.js L2 content.en/bn.read | ContentField(ui.read).value | Shared UI copy | explore: home services link/footer heading; read: article action; readmore: home about/blog/gallery links | Imported; admin pending |
| ctatitle | frontend/assets/app.js L2 content.en/bn.ctatitle | SiteSetting(primary).cta_title | Shared CTA | CTA on seven page types; desc/label intentionally revised for stored inquiry | Imported; admin pending |
| ctadesc | frontend/assets/app.js L2 content.en/bn.ctadesc | SiteSetting(primary).cta_description | Shared CTA | CTA on seven page types; desc/label intentionally revised for stored inquiry | Imported; admin pending |
| ctabtn | frontend/assets/app.js L2 content.en/bn.ctabtn | SiteSetting(primary).cta_label | Shared CTA | CTA on seven page types; desc/label intentionally revised for stored inquiry | Imported; admin pending |
| address | frontend/assets/app.js L2 content.en/bn.address | SiteSetting(primary).address | Shared clinic settings | Topbar; contact; footer; visitText placeholder | Imported; admin pending |
| fb | frontend/assets/app.js L2 content.en/bn.fb | ContentField(contact.fb).value | Contact page fields | Contact information card | Imported; admin pending |
| footertext | frontend/assets/app.js L2 content.en/bn.footertext | SiteSetting(primary).footer_description | Shared settings | Footer brand column | Imported; admin pending |
| galleryNote | frontend/assets/app.js L2 content.en/bn.galleryNote | SiteSetting(primary).stock_notice; MediaAsset(all nine).stock_note | Gallery and shared notices | Gallery hero/home preview; galleryNote: gallery/about/footer copyright | Imported; admin pending |
| blogheading | frontend/assets/app.js L2 content.en/bn.blogheading | ContentField(blog.blogheading).value | Blog page fields | Blog hero/tag-filter heading | Imported; admin pending |
| blogintro | frontend/assets/app.js L2 content.en/bn.blogintro | ContentField(blog.blogintro).value | Blog page fields | Blog hero/tag-filter heading | Imported; admin pending |
| tagtitle | frontend/assets/app.js L2 content.en/bn.tagtitle | ContentField(blog.tagtitle).value | Blog page fields | Blog hero/tag-filter heading | Imported; admin pending |
| contacttitle | frontend/assets/app.js L2 content.en/bn.contacttitle | ContentField(contact.contacttitle).value | Contact form/copy | Contact hero/form; contactdesc/formtitle/name/prepare/privacy intentionally revised | Imported; admin pending |
| contactdesc | frontend/assets/app.js L2 content.en/bn.contactdesc | ContentField(contact.contactdesc).value | Contact form/copy | Contact hero/form; contactdesc/formtitle/name/prepare/privacy intentionally revised | Imported; admin pending |
| formtitle | frontend/assets/app.js L2 content.en/bn.formtitle | ContentField(contact.formtitle).value | Contact form/copy | Contact hero/form; contactdesc/formtitle/name/prepare/privacy intentionally revised | Imported; admin pending |
| name | frontend/assets/app.js L2 content.en/bn.name | ContentField(contact.name).value | Contact form/copy | Contact hero/form; contactdesc/formtitle/name/prepare/privacy intentionally revised | Imported; admin pending |
| phone | frontend/assets/app.js L2 content.en/bn.phone | ContentField(contact.phone).value | Contact form/copy | Contact hero/form; contactdesc/formtitle/name/prepare/privacy intentionally revised | Imported; admin pending |
| topic | frontend/assets/app.js L2 content.en/bn.topic | ContentField(contact.topic).value | Contact form/copy | Contact hero/form; contactdesc/formtitle/name/prepare/privacy intentionally revised | Imported; admin pending |
| message | frontend/assets/app.js L2 content.en/bn.message | ContentField(contact.message).value | Contact form/copy | Contact hero/form; contactdesc/formtitle/name/prepare/privacy intentionally revised | Imported; admin pending |
| prepare | frontend/assets/app.js L2 content.en/bn.prepare | ContentField(contact.prepare).value | Contact form/copy | Contact hero/form; contactdesc/formtitle/name/prepare/privacy intentionally revised | Imported; admin pending |
| privacy | frontend/assets/app.js L2 content.en/bn.privacy | ContentField(contact.privacy).value | Contact form/copy | Contact hero/form; contactdesc/formtitle/name/prepare/privacy intentionally revised | Imported; admin pending |
| addressLabel | frontend/assets/app.js L2 content.en/bn.addressLabel | ContentField(contact.addressLabel).value | Contact page fields | Contact information card | Imported; admin pending |
| hoursLabel | frontend/assets/app.js L2 content.en/bn.hoursLabel | ContentField(contact.hoursLabel).value | Contact page fields | Contact information card | Imported; admin pending |
| openmap | frontend/assets/app.js L2 content.en/bn.openmap | ContentField(contact.openmap).value | Contact page fields | Contact information card | Imported; admin pending |
| abouttitle | frontend/assets/app.js L2 content.en/bn.abouttitle | ContentField(about.abouttitle).value | About page fields | About hero | Imported; admin pending |
| aboutdesc | frontend/assets/app.js L2 content.en/bn.aboutdesc | ContentField(about.aboutdesc).value | About page fields | About hero | Imported; admin pending |
| diagtitle | frontend/assets/app.js L2 content.en/bn.diagtitle | ContentField(diagnostics.diagtitle).value | Diagnostics page fields | Diagnostics hero/split/inquiry topics/verified-menu caveat | Imported; admin pending |
| diagdesc | frontend/assets/app.js L2 content.en/bn.diagdesc | ContentField(diagnostics.diagdesc).value | Diagnostics page fields | Diagnostics hero/split/inquiry topics/verified-menu caveat | Imported; admin pending |
| diagnotice | frontend/assets/app.js L2 content.en/bn.diagnotice | ContentField(diagnostics.diagnotice).value | Diagnostics page fields | Diagnostics hero/split/inquiry topics/verified-menu caveat | Imported; admin pending |
| diagtags | frontend/assets/app.js L2 content.en/bn.diagtags | ContentField(diagnostics.diagtags).value | Diagnostics page fields | Diagnostics hero/split/inquiry topics/verified-menu caveat | Imported; admin pending |
| diagtagsdesc | frontend/assets/app.js L2 content.en/bn.diagtagsdesc | ContentField(diagnostics.diagtagsdesc).value | Diagnostics page fields | Diagnostics hero/split/inquiry topics/verified-menu caveat | Imported; admin pending |
| servicePageTitle | frontend/assets/app.js L2 content.en/bn.servicePageTitle | ContentField(services.servicePageTitle).value | Services page fields | Services hero | Imported; admin pending |
| servicePageDesc | frontend/assets/app.js L2 content.en/bn.servicePageDesc | ContentField(services.servicePageDesc).value | Services page fields | Services hero | Imported; admin pending |
| disclaimer | frontend/assets/app.js L2 content.en/bn.disclaimer | SiteSetting(primary).medical_disclaimer | Shared notices | Services grid and every article; required caveat | Imported; admin pending |
| readmore | frontend/assets/app.js L2 content.en/bn.readmore | ContentField(ui.readmore).value | Shared UI copy | explore: home services link/footer heading; read: article action; readmore: home about/blog/gallery links | Imported; admin pending |

## Navigation labels, destinations and collection controls

| Public field / collection member | Source location | Proposed database owner | Future admin screen | Public rendering location | Status |
| --- | --- | --- | --- | --- | --- |
| header home.label | app.js L6 nav(); content.home | NavigationItem(header:home).label {en,bn} | Navigation | header navigation | Imported; admin pending |
| header home.target | app.js L6 index.html | NavigationItem.target {en,bn}; known route validation | Navigation | header link href | Imported; admin pending |
| header about.label | app.js L6 nav(); content.about | NavigationItem(header:about).label {en,bn} | Navigation | header navigation | Imported; admin pending |
| header about.target | app.js L6 about.html | NavigationItem.target {en,bn}; known route validation | Navigation | header link href | Imported; admin pending |
| header services.label | app.js L6 nav(); content.services | NavigationItem(header:services).label {en,bn} | Navigation | header navigation | Imported; admin pending |
| header services.target | app.js L6 services.html | NavigationItem.target {en,bn}; known route validation | Navigation | header link href | Imported; admin pending |
| header diagnostics.label | app.js L6 nav(); content.diagnostics | NavigationItem(header:diagnostics).label {en,bn} | Navigation | header navigation | Imported; admin pending |
| header diagnostics.target | app.js L6 diagnostics.html | NavigationItem.target {en,bn}; known route validation | Navigation | header link href | Imported; admin pending |
| header blog.label | app.js L6 nav(); content.blog | NavigationItem(header:blog).label {en,bn} | Navigation | header navigation | Imported; admin pending |
| header blog.target | app.js L6 blog.html | NavigationItem.target {en,bn}; known route validation | Navigation | header link href | Imported; admin pending |
| header gallery.label | app.js L6 nav(); content.gallery | NavigationItem(header:gallery).label {en,bn} | Navigation | header navigation | Imported; admin pending |
| header gallery.target | app.js L6 gallery.html | NavigationItem.target {en,bn}; known route validation | Navigation | header link href | Imported; admin pending |
| header contact.label | app.js L6 nav(); content.contact | NavigationItem(header:contact).label {en,bn} | Navigation | header navigation | Imported; admin pending |
| header contact.target | app.js L6 contact.html | NavigationItem.target {en,bn}; known route validation | Navigation | header link href | Imported; admin pending |
| footer about.label | app.js L7 footer(); content.about | NavigationItem(footer:about).label {en,bn} | Navigation | footer navigation | Imported; admin pending |
| footer about.target | app.js L7 about.html | NavigationItem.target {en,bn}; known route validation | Navigation | footer link href | Imported; admin pending |
| footer services.label | app.js L7 footer(); content.services | NavigationItem(footer:services).label {en,bn} | Navigation | footer navigation | Imported; admin pending |
| footer services.target | app.js L7 services.html | NavigationItem.target {en,bn}; known route validation | Navigation | footer link href | Imported; admin pending |
| footer blog.label | app.js L7 footer(); content.blog | NavigationItem(footer:blog).label {en,bn} | Navigation | footer navigation | Imported; admin pending |
| footer blog.target | app.js L7 blog.html | NavigationItem.target {en,bn}; known route validation | Navigation | footer link href | Imported; admin pending |
| footer gallery.label | app.js L7 footer(); content.gallery | NavigationItem(footer:gallery).label {en,bn} | Navigation | footer navigation | Imported; admin pending |
| footer gallery.target | app.js L7 gallery.html | NavigationItem.target {en,bn}; known route validation | Navigation | footer link href | Imported; admin pending |
| Navigation ordering/visibility | L6/L7 array order | NavigationItem.sort_order/is_visible | Navigation | Header/footer ordering | Imported; admin pending |

## Homepage care-card fields

| Public field / collection member | Source location | Proposed database owner | Future admin screen | Public rendering location | Status |
| --- | --- | --- | --- | --- | --- |
| Card 1 obstetrics.title | app.js L9 cards(); obtitle | CareCard(obstetrics).title | Homepage care cards | Home .service-card; title also appointment option | Imported; admin pending |
| Card 1 obstetrics.description | app.js L9 cards(); obdesc | CareCard(obstetrics).description | Homepage care cards | Home .service-card; title also appointment option | Imported; admin pending |
| Card 1 obstetrics.icon | app.js L9 cards(); 🤰 | CareCard(obstetrics).icon_code (allowlist) | Homepage care cards | Home .service-card; title also appointment option | Imported; admin pending |
| Card 1 obstetrics.link label | app.js L9 cards(); learn | CareCard(obstetrics).link_label {en,bn} | Homepage care cards | Home .service-card; title also appointment option | Imported; admin pending |
| Card 1 obstetrics.target | app.js L9 cards(); services.html | CareCard(obstetrics).target {en,bn} | Homepage care cards | Home .service-card; title also appointment option | Imported; admin pending |
| Card 2 gynecology.title | app.js L9 cards(); gytitle | CareCard(gynecology).title | Homepage care cards | Home .service-card; title also appointment option | Imported; admin pending |
| Card 2 gynecology.description | app.js L9 cards(); gydesc | CareCard(gynecology).description | Homepage care cards | Home .service-card; title also appointment option | Imported; admin pending |
| Card 2 gynecology.icon | app.js L9 cards(); ✿ | CareCard(gynecology).icon_code (allowlist) | Homepage care cards | Home .service-card; title also appointment option | Imported; admin pending |
| Card 2 gynecology.link label | app.js L9 cards(); learn | CareCard(gynecology).link_label {en,bn} | Homepage care cards | Home .service-card; title also appointment option | Imported; admin pending |
| Card 2 gynecology.target | app.js L9 cards(); services.html | CareCard(gynecology).target {en,bn} | Homepage care cards | Home .service-card; title also appointment option | Imported; admin pending |
| Card 3 fertility.title | app.js L9 cards(); iuititle | CareCard(fertility).title | Homepage care cards | Home .service-card; title also appointment option | Imported; admin pending |
| Card 3 fertility.description | app.js L9 cards(); iuidesc | CareCard(fertility).description | Homepage care cards | Home .service-card; title also appointment option | Imported; admin pending |
| Card 3 fertility.icon | app.js L9 cards(); ♡ | CareCard(fertility).icon_code (allowlist) | Homepage care cards | Home .service-card; title also appointment option | Imported; admin pending |
| Card 3 fertility.link label | app.js L9 cards(); learn | CareCard(fertility).link_label {en,bn} | Homepage care cards | Home .service-card; title also appointment option | Imported; admin pending |
| Card 3 fertility.target | app.js L9 cards(); services.html | CareCard(fertility).target {en,bn} | Homepage care cards | Home .service-card; title also appointment option | Imported; admin pending |
| Card 4 diagnostics.title | app.js L9 cards(); diatitle | CareCard(diagnostics).title | Homepage care cards | Home .service-card; title also appointment option | Imported; admin pending |
| Card 4 diagnostics.description | app.js L9 cards(); diadesc | CareCard(diagnostics).description | Homepage care cards | Home .service-card; title also appointment option | Imported; admin pending |
| Card 4 diagnostics.icon | app.js L9 cards(); 🔬 | CareCard(diagnostics).icon_code (allowlist) | Homepage care cards | Home .service-card; title also appointment option | Imported; admin pending |
| Card 4 diagnostics.link label | app.js L9 cards(); learn | CareCard(diagnostics).link_label {en,bn} | Homepage care cards | Home .service-card; title also appointment option | Imported; admin pending |
| Card 4 diagnostics.target | app.js L9 cards(); diagnostics.html | CareCard(diagnostics).target {en,bn} | Homepage care cards | Home .service-card; title also appointment option | Imported; admin pending |
| Care-card ordering/visibility/topic code | L9 source order; L22 topic option titles | CareCard.sort_order/is_visible/import_key | Homepage care cards | Home order; appointment-topic allowlist | Imported; admin pending |

## Services-page fields (separate collection)

| Public field / collection member | Source location | Proposed database owner | Future admin screen | Public rendering location | Status |
| --- | --- | --- | --- | --- | --- |
| Service 1.title | app.js L16 serviceDetails.en/bn[0][0] | Service(import_key=demo-service-1).title {en,bn} | Services | Services .info-box 1 | Imported; admin pending |
| Service 1.description | app.js L16 serviceDetails.en/bn[0][1] | Service(import_key=demo-service-1).description {en,bn} | Services | Services .info-box 1 | Imported; admin pending |
| Service 2.title | app.js L16 serviceDetails.en/bn[1][0] | Service(import_key=demo-service-2).title {en,bn} | Services | Services .info-box 2 | Imported; admin pending |
| Service 2.description | app.js L16 serviceDetails.en/bn[1][1] | Service(import_key=demo-service-2).description {en,bn} | Services | Services .info-box 2 | Imported; admin pending |
| Service 3.title | app.js L16 serviceDetails.en/bn[2][0] | Service(import_key=demo-service-3).title {en,bn} | Services | Services .info-box 3 | Imported; admin pending |
| Service 3.description | app.js L16 serviceDetails.en/bn[2][1] | Service(import_key=demo-service-3).description {en,bn} | Services | Services .info-box 3 | Imported; admin pending |
| Service 4.title | app.js L16 serviceDetails.en/bn[3][0] | Service(import_key=demo-service-4).title {en,bn} | Services | Services .info-box 4 | Imported; admin pending |
| Service 4.description | app.js L16 serviceDetails.en/bn[3][1] | Service(import_key=demo-service-4).description {en,bn} | Services | Services .info-box 4 | Imported; admin pending |
| Service 5.title | app.js L16 serviceDetails.en/bn[4][0] | Service(import_key=demo-service-5).title {en,bn} | Services | Services .info-box 5 | Imported; admin pending |
| Service 5.description | app.js L16 serviceDetails.en/bn[4][1] | Service(import_key=demo-service-5).description {en,bn} | Services | Services .info-box 5 | Imported; admin pending |
| Service order/visibility and displayed number | L17 services() index 01–05 | Service.sort_order/is_visible; number derived in Blade | Services | Services detail block order/number | Imported; admin pending |

## Categories / tags

One category per demo item; hashtag prefix is code punctuation. Posts relate only to clinical/wellness in the original.

| Public field / collection member | Source location | Proposed database owner | Future admin screen | Public rendering location | Status |
| --- | --- | --- | --- | --- | --- |
| Category clinical.name | app.js L2 clinical; L3/L4 cat; L12 filter order | Category.label {en,bn} | Categories | Blog/gallery category filters/tags; facility gallery only | Imported; admin pending |
| Category clinical.code | app.js L2 clinical; L3/L4 cat; L12 filter order | Category.slug (stable) | Categories | Blog/gallery category filters/tags; facility gallery only | Imported; admin pending |
| Category clinical.order/visibility | app.js L2 clinical; L3/L4 cat; L12 filter order | Category.sort_order/for_blog/for_gallery | Categories | Blog/gallery category filters/tags; facility gallery only | Imported; admin pending |
| Category wellness.name | app.js L2 wellness; L3/L4 cat; L12 filter order | Category.label {en,bn} | Categories | Blog/gallery category filters/tags; facility gallery only | Imported; admin pending |
| Category wellness.code | app.js L2 wellness; L3/L4 cat; L12 filter order | Category.slug (stable) | Categories | Blog/gallery category filters/tags; facility gallery only | Imported; admin pending |
| Category wellness.order/visibility | app.js L2 wellness; L3/L4 cat; L12 filter order | Category.sort_order/for_blog/for_gallery | Categories | Blog/gallery category filters/tags; facility gallery only | Imported; admin pending |
| Category facility.name | app.js L2 facility; L3/L4 cat; L12 filter order | Category.label {en,bn} | Categories | Blog/gallery category filters/tags; facility gallery only | Imported; admin pending |
| Category facility.code | app.js L2 facility; L3/L4 cat; L12 filter order | Category.slug (stable) | Categories | Blog/gallery category filters/tags; facility gallery only | Imported; admin pending |
| Category facility.order/visibility | app.js L2 facility; L3/L4 cat; L12 filter order | Category.sort_order/for_blog/for_gallery | Categories | Blog/gallery category filters/tags; facility gallery only | Imported; admin pending |

## Every article field and paragraph

| Public field / collection member | Source location | Proposed database owner | Future admin screen | Public rendering location | Status |
| --- | --- | --- | --- | --- | --- |
| Post pregnancy-care.slug | app.js L3 articles[id=pregnancy-care].id | Post.slug | Posts | Blog/home card; article detail | Imported; admin pending |
| Post pregnancy-care.title | app.js L3 articles[id=pregnancy-care].en/bn.title | Post.title {en,bn} | Posts | Blog/home card; article detail | Imported; admin pending |
| Post pregnancy-care.excerpt | app.js L3 articles[id=pregnancy-care].en/bn.tease | Post.excerpt {en,bn} | Posts | Blog/home card; article detail | Imported; admin pending |
| Post pregnancy-care.eyebrow | app.js L3 articles[id=pregnancy-care].date (topic label) | Post.editorial_badge {en,bn} | Posts | Blog/home card; article detail | Imported; admin pending |
| Post pregnancy-care.category | app.js L3 articles[id=pregnancy-care].cat | Post.category_id FK | Posts | Blog/home card; article detail | Imported; admin pending |
| Post pregnancy-care.photo | app.js L3 articles[id=pregnancy-care].photo → PHOTOS.pregnancy | Post.media_asset_id FK | Posts | Blog/home card; article detail | Imported; admin pending |
| Post pregnancy-care.alt | app.js L3 articles[id=pregnancy-care].localized title, L10/L21 | Post.image_alt {en,bn} | Posts | Blog/home card; article detail | Imported; admin pending |
| Post pregnancy-care.body paragraph 1 | L3 articles[id=pregnancy-care].en/bn.body[0] | Post.body {en,bn} plain text paragraph 1 | Posts | Article .prose paragraphs | Imported; admin pending |
| Post pregnancy-care.body paragraph 2 | L3 articles[id=pregnancy-care].en/bn.body[1] | Post.body {en,bn} plain text paragraph 2 | Posts | Article .prose paragraphs | Imported; admin pending |
| Post pregnancy-care.meta_title | L23 blog label \| brand; article title L3 | Post.meta_title {en,bn} | Posts; metadata | Article head; initial defaults derived explicitly at import | Imported; admin pending |
| Post pregnancy-care.meta_description | All HTML shared English description; L3 excerpt available | Post.meta_description {en,bn} | Posts; metadata | Article head; initial defaults derived explicitly at import | Imported; admin pending |
| Post pcos.slug | app.js L3 articles[id=pcos].id | Post.slug | Posts | Blog/home card; article detail | Imported; admin pending |
| Post pcos.title | app.js L3 articles[id=pcos].en/bn.title | Post.title {en,bn} | Posts | Blog/home card; article detail | Imported; admin pending |
| Post pcos.excerpt | app.js L3 articles[id=pcos].en/bn.tease | Post.excerpt {en,bn} | Posts | Blog/home card; article detail | Imported; admin pending |
| Post pcos.eyebrow | app.js L3 articles[id=pcos].date (topic label) | Post.editorial_badge {en,bn} | Posts | Blog/home card; article detail | Imported; admin pending |
| Post pcos.category | app.js L3 articles[id=pcos].cat | Post.category_id FK | Posts | Blog/home card; article detail | Imported; admin pending |
| Post pcos.photo | app.js L3 articles[id=pcos].photo → PHOTOS.care | Post.media_asset_id FK | Posts | Blog/home card; article detail | Imported; admin pending |
| Post pcos.alt | app.js L3 articles[id=pcos].localized title, L10/L21 | Post.image_alt {en,bn} | Posts | Blog/home card; article detail | Imported; admin pending |
| Post pcos.body paragraph 1 | L3 articles[id=pcos].en/bn.body[0] | Post.body {en,bn} plain text paragraph 1 | Posts | Article .prose paragraphs | Imported; admin pending |
| Post pcos.body paragraph 2 | L3 articles[id=pcos].en/bn.body[1] | Post.body {en,bn} plain text paragraph 2 | Posts | Article .prose paragraphs | Imported; admin pending |
| Post pcos.meta_title | L23 blog label \| brand; article title L3 | Post.meta_title {en,bn} | Posts; metadata | Article head; initial defaults derived explicitly at import | Imported; admin pending |
| Post pcos.meta_description | All HTML shared English description; L3 excerpt available | Post.meta_description {en,bn} | Posts; metadata | Article head; initial defaults derived explicitly at import | Imported; admin pending |
| Post fertility-first-visit.slug | app.js L3 articles[id=fertility-first-visit].id | Post.slug | Posts | Blog/home card; article detail | Imported; admin pending |
| Post fertility-first-visit.title | app.js L3 articles[id=fertility-first-visit].en/bn.title | Post.title {en,bn} | Posts | Blog/home card; article detail | Imported; admin pending |
| Post fertility-first-visit.excerpt | app.js L3 articles[id=fertility-first-visit].en/bn.tease | Post.excerpt {en,bn} | Posts | Blog/home card; article detail | Imported; admin pending |
| Post fertility-first-visit.eyebrow | app.js L3 articles[id=fertility-first-visit].date (topic label) | Post.editorial_badge {en,bn} | Posts | Blog/home card; article detail | Imported; admin pending |
| Post fertility-first-visit.category | app.js L3 articles[id=fertility-first-visit].cat | Post.category_id FK | Posts | Blog/home card; article detail | Imported; admin pending |
| Post fertility-first-visit.photo | app.js L3 articles[id=fertility-first-visit].photo → PHOTOS.consult | Post.media_asset_id FK | Posts | Blog/home card; article detail | Imported; admin pending |
| Post fertility-first-visit.alt | app.js L3 articles[id=fertility-first-visit].localized title, L10/L21 | Post.image_alt {en,bn} | Posts | Blog/home card; article detail | Imported; admin pending |
| Post fertility-first-visit.body paragraph 1 | L3 articles[id=fertility-first-visit].en/bn.body[0] | Post.body {en,bn} plain text paragraph 1 | Posts | Article .prose paragraphs | Imported; admin pending |
| Post fertility-first-visit.body paragraph 2 | L3 articles[id=fertility-first-visit].en/bn.body[1] | Post.body {en,bn} plain text paragraph 2 | Posts | Article .prose paragraphs | Imported; admin pending |
| Post fertility-first-visit.meta_title | L23 blog label \| brand; article title L3 | Post.meta_title {en,bn} | Posts; metadata | Article head; initial defaults derived explicitly at import | Imported; admin pending |
| Post fertility-first-visit.meta_description | All HTML shared English description; L3 excerpt available | Post.meta_description {en,bn} | Posts; metadata | Article head; initial defaults derived explicitly at import | Imported; admin pending |
| Post menopause.slug | app.js L3 articles[id=menopause].id | Post.slug | Posts | Blog/home card; article detail | Imported; admin pending |
| Post menopause.title | app.js L3 articles[id=menopause].en/bn.title | Post.title {en,bn} | Posts | Blog/home card; article detail | Imported; admin pending |
| Post menopause.excerpt | app.js L3 articles[id=menopause].en/bn.tease | Post.excerpt {en,bn} | Posts | Blog/home card; article detail | Imported; admin pending |
| Post menopause.eyebrow | app.js L3 articles[id=menopause].date (topic label) | Post.editorial_badge {en,bn} | Posts | Blog/home card; article detail | Imported; admin pending |
| Post menopause.category | app.js L3 articles[id=menopause].cat | Post.category_id FK | Posts | Blog/home card; article detail | Imported; admin pending |
| Post menopause.photo | app.js L3 articles[id=menopause].photo → PHOTOS.doctor | Post.media_asset_id FK | Posts | Blog/home card; article detail | Imported; admin pending |
| Post menopause.alt | app.js L3 articles[id=menopause].localized title, L10/L21 | Post.image_alt {en,bn} | Posts | Blog/home card; article detail | Imported; admin pending |
| Post menopause.body paragraph 1 | L3 articles[id=menopause].en/bn.body[0] | Post.body {en,bn} plain text paragraph 1 | Posts | Article .prose paragraphs | Imported; admin pending |
| Post menopause.body paragraph 2 | L3 articles[id=menopause].en/bn.body[1] | Post.body {en,bn} plain text paragraph 2 | Posts | Article .prose paragraphs | Imported; admin pending |
| Post menopause.meta_title | L23 blog label \| brand; article title L3 | Post.meta_title {en,bn} | Posts; metadata | Article head; initial defaults derived explicitly at import | Imported; admin pending |
| Post menopause.meta_description | All HTML shared English description; L3 excerpt available | Post.meta_description {en,bn} | Posts; metadata | Article head; initial defaults derived explicitly at import | Imported; admin pending |
| Post collection ordering/publication | L3 source order; no actual dates/publication flags | Post.sort_order/status/published_at (initialization timestamp) | Posts | Home first 3 published; blog listing; article lookup; demo initially published | Imported; admin pending |
| Article category/back/read link labels and targets | L10/L21 read/blog; article.html?id=slug; blog.html | ContentField.ui.read; NavigationItem blog.label; route generated from Post.slug/category | Shared UI; Posts; Navigation | Article cards/detail; route shape code-owned | Imported; admin pending |

## Every gallery item field

| Public field / collection member | Source location | Proposed database owner | Future admin screen | Public rendering location | Status |
| --- | --- | --- | --- | --- | --- |
| Gallery 1.caption | L4 galleries[0].en/bn | GalleryImage(demo-gallery-1).caption {en,bn} | Gallery | Gallery grid/lightbox; home items 1–3 | Imported; admin pending |
| Gallery 1.alt | L4 galleries[0].caption reused L11 | GalleryImage(demo-gallery-1).alt_text {en,bn} | Gallery | Gallery grid/lightbox; home items 1–3 | Imported; admin pending |
| Gallery 1.accessible label / lightbox caption | L4 galleries[0].caption reused L11/L23 | GalleryImage(demo-gallery-1).caption {en,bn} (same row) | Gallery | Gallery grid/lightbox; home items 1–3 | Imported; admin pending |
| Gallery 1.category | L4 galleries[0].cat=facility | GalleryImage(demo-gallery-1).category_id FK | Gallery | Gallery grid/lightbox; home items 1–3 | Imported; admin pending |
| Gallery 1.photo/lightbox URL | L4 galleries[0].photo=clinic | GalleryImage(demo-gallery-1).media_asset_id FK → local or legacy external media URL | Gallery | Gallery grid/lightbox; home items 1–3 | Imported; admin pending |
| Gallery 2.caption | L4 galleries[1].en/bn | GalleryImage(demo-gallery-2).caption {en,bn} | Gallery | Gallery grid/lightbox; home items 1–3 | Imported; admin pending |
| Gallery 2.alt | L4 galleries[1].caption reused L11 | GalleryImage(demo-gallery-2).alt_text {en,bn} | Gallery | Gallery grid/lightbox; home items 1–3 | Imported; admin pending |
| Gallery 2.accessible label / lightbox caption | L4 galleries[1].caption reused L11/L23 | GalleryImage(demo-gallery-2).caption {en,bn} (same row) | Gallery | Gallery grid/lightbox; home items 1–3 | Imported; admin pending |
| Gallery 2.category | L4 galleries[1].cat=clinical | GalleryImage(demo-gallery-2).category_id FK | Gallery | Gallery grid/lightbox; home items 1–3 | Imported; admin pending |
| Gallery 2.photo/lightbox URL | L4 galleries[1].photo=consult | GalleryImage(demo-gallery-2).media_asset_id FK → local or legacy external media URL | Gallery | Gallery grid/lightbox; home items 1–3 | Imported; admin pending |
| Gallery 3.caption | L4 galleries[2].en/bn | GalleryImage(demo-gallery-3).caption {en,bn} | Gallery | Gallery grid/lightbox; home items 1–3 | Imported; admin pending |
| Gallery 3.alt | L4 galleries[2].caption reused L11 | GalleryImage(demo-gallery-3).alt_text {en,bn} | Gallery | Gallery grid/lightbox; home items 1–3 | Imported; admin pending |
| Gallery 3.accessible label / lightbox caption | L4 galleries[2].caption reused L11/L23 | GalleryImage(demo-gallery-3).caption {en,bn} (same row) | Gallery | Gallery grid/lightbox; home items 1–3 | Imported; admin pending |
| Gallery 3.category | L4 galleries[2].cat=facility | GalleryImage(demo-gallery-3).category_id FK | Gallery | Gallery grid/lightbox; home items 1–3 | Imported; admin pending |
| Gallery 3.photo/lightbox URL | L4 galleries[2].photo=lab | GalleryImage(demo-gallery-3).media_asset_id FK → local or legacy external media URL | Gallery | Gallery grid/lightbox; home items 1–3 | Imported; admin pending |
| Gallery 4.caption | L4 galleries[3].en/bn | GalleryImage(demo-gallery-4).caption {en,bn} | Gallery | Gallery grid/lightbox; home items 1–3 | Imported; admin pending |
| Gallery 4.alt | L4 galleries[3].caption reused L11 | GalleryImage(demo-gallery-4).alt_text {en,bn} | Gallery | Gallery grid/lightbox; home items 1–3 | Imported; admin pending |
| Gallery 4.accessible label / lightbox caption | L4 galleries[3].caption reused L11/L23 | GalleryImage(demo-gallery-4).caption {en,bn} (same row) | Gallery | Gallery grid/lightbox; home items 1–3 | Imported; admin pending |
| Gallery 4.category | L4 galleries[3].cat=wellness | GalleryImage(demo-gallery-4).category_id FK | Gallery | Gallery grid/lightbox; home items 1–3 | Imported; admin pending |
| Gallery 4.photo/lightbox URL | L4 galleries[3].photo=pregnancy | GalleryImage(demo-gallery-4).media_asset_id FK → local or legacy external media URL | Gallery | Gallery grid/lightbox; home items 1–3 | Imported; admin pending |
| Gallery 5.caption | L4 galleries[4].en/bn | GalleryImage(demo-gallery-5).caption {en,bn} | Gallery | Gallery grid/lightbox; home items 1–3 | Imported; admin pending |
| Gallery 5.alt | L4 galleries[4].caption reused L11 | GalleryImage(demo-gallery-5).alt_text {en,bn} | Gallery | Gallery grid/lightbox; home items 1–3 | Imported; admin pending |
| Gallery 5.accessible label / lightbox caption | L4 galleries[4].caption reused L11/L23 | GalleryImage(demo-gallery-5).caption {en,bn} (same row) | Gallery | Gallery grid/lightbox; home items 1–3 | Imported; admin pending |
| Gallery 5.category | L4 galleries[4].cat=clinical | GalleryImage(demo-gallery-5).category_id FK | Gallery | Gallery grid/lightbox; home items 1–3 | Imported; admin pending |
| Gallery 5.photo/lightbox URL | L4 galleries[4].photo=team | GalleryImage(demo-gallery-5).media_asset_id FK → local or legacy external media URL | Gallery | Gallery grid/lightbox; home items 1–3 | Imported; admin pending |
| Gallery 6.caption | L4 galleries[5].en/bn | GalleryImage(demo-gallery-6).caption {en,bn} | Gallery | Gallery grid/lightbox; home items 1–3 | Imported; admin pending |
| Gallery 6.alt | L4 galleries[5].caption reused L11 | GalleryImage(demo-gallery-6).alt_text {en,bn} | Gallery | Gallery grid/lightbox; home items 1–3 | Imported; admin pending |
| Gallery 6.accessible label / lightbox caption | L4 galleries[5].caption reused L11/L23 | GalleryImage(demo-gallery-6).caption {en,bn} (same row) | Gallery | Gallery grid/lightbox; home items 1–3 | Imported; admin pending |
| Gallery 6.category | L4 galleries[5].cat=facility | GalleryImage(demo-gallery-6).category_id FK | Gallery | Gallery grid/lightbox; home items 1–3 | Imported; admin pending |
| Gallery 6.photo/lightbox URL | L4 galleries[5].photo=interior | GalleryImage(demo-gallery-6).media_asset_id FK → local or legacy external media URL | Gallery | Gallery grid/lightbox; home items 1–3 | Imported; admin pending |
| Gallery 7.caption | L4 galleries[6].en/bn | GalleryImage(demo-gallery-7).caption {en,bn} | Gallery | Gallery grid/lightbox; home items 1–3 | Imported; admin pending |
| Gallery 7.alt | L4 galleries[6].caption reused L11 | GalleryImage(demo-gallery-7).alt_text {en,bn} | Gallery | Gallery grid/lightbox; home items 1–3 | Imported; admin pending |
| Gallery 7.accessible label / lightbox caption | L4 galleries[6].caption reused L11/L23 | GalleryImage(demo-gallery-7).caption {en,bn} (same row) | Gallery | Gallery grid/lightbox; home items 1–3 | Imported; admin pending |
| Gallery 7.category | L4 galleries[6].cat=wellness | GalleryImage(demo-gallery-7).category_id FK | Gallery | Gallery grid/lightbox; home items 1–3 | Imported; admin pending |
| Gallery 7.photo/lightbox URL | L4 galleries[6].photo=care | GalleryImage(demo-gallery-7).media_asset_id FK → local or legacy external media URL | Gallery | Gallery grid/lightbox; home items 1–3 | Imported; admin pending |
| Gallery 8.caption | L4 galleries[7].en/bn | GalleryImage(demo-gallery-8).caption {en,bn} | Gallery | Gallery grid/lightbox; home items 1–3 | Imported; admin pending |
| Gallery 8.alt | L4 galleries[7].caption reused L11 | GalleryImage(demo-gallery-8).alt_text {en,bn} | Gallery | Gallery grid/lightbox; home items 1–3 | Imported; admin pending |
| Gallery 8.accessible label / lightbox caption | L4 galleries[7].caption reused L11/L23 | GalleryImage(demo-gallery-8).caption {en,bn} (same row) | Gallery | Gallery grid/lightbox; home items 1–3 | Imported; admin pending |
| Gallery 8.category | L4 galleries[7].cat=clinical | GalleryImage(demo-gallery-8).category_id FK | Gallery | Gallery grid/lightbox; home items 1–3 | Imported; admin pending |
| Gallery 8.photo/lightbox URL | L4 galleries[7].photo=doctor | GalleryImage(demo-gallery-8).media_asset_id FK → local or legacy external media URL | Gallery | Gallery grid/lightbox; home items 1–3 | Imported; admin pending |
| Gallery collection order/visibility | L4 source order; L11 slice limits | GalleryImage.sort_order/is_visible/on_homepage | Gallery | Visible grid/home first 3 | Imported; admin pending |

## Media assets and shared files

Initial technical source URLs are retained as legacy external media. Verified local files may later be uploaded; no source script reads or runtime seed fallback. Slot-specific alt overrides default_alt through the common localization service.

| Public field / collection member | Source location | Proposed database owner | Future admin screen | Public rendering location | Status |
| --- | --- | --- | --- | --- | --- |
| Media hero.source / file | app.js L1 PHOTOS.hero; exact URL in audit | MediaAsset(demo-photo-hero).source_url/path | Media library | Legacy external initial image source for Home hero | Imported; admin pending |
| Media hero.title | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.title {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Imported; admin pending |
| Media hero.default_alt | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.alt_text {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Imported; admin pending |
| Media hero.credit | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.provenance_note (no supplied credit) {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Imported; admin pending |
| Media hero.caveat | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.stock_note {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Imported; admin pending |
| Media doctor.source / file | app.js L1 PHOTOS.doctor; exact URL in audit | MediaAsset(demo-photo-doctor).source_url/path | Media library | Legacy external initial image source for menopause article; gallery #8 | Imported; admin pending |
| Media doctor.title | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.title {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Imported; admin pending |
| Media doctor.default_alt | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.alt_text {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Imported; admin pending |
| Media doctor.credit | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.provenance_note (no supplied credit) {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Imported; admin pending |
| Media doctor.caveat | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.stock_note {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Imported; admin pending |
| Media consult.source / file | app.js L1 PHOTOS.consult; exact URL in audit | MediaAsset(demo-photo-consult).source_url/path | Media library | Legacy external initial image source for Home doctor split; fertility-first-visit article; gallery #2 | Imported; admin pending |
| Media consult.title | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.title {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Imported; admin pending |
| Media consult.default_alt | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.alt_text {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Imported; admin pending |
| Media consult.credit | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.provenance_note (no supplied credit) {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Imported; admin pending |
| Media consult.caveat | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.stock_note {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Imported; admin pending |
| Media clinic.source / file | app.js L1 PHOTOS.clinic; exact URL in audit | MediaAsset(demo-photo-clinic).source_url/path | Media library | Legacy external initial image source for gallery #1 | Imported; admin pending |
| Media clinic.title | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.title {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Imported; admin pending |
| Media clinic.default_alt | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.alt_text {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Imported; admin pending |
| Media clinic.credit | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.provenance_note (no supplied credit) {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Imported; admin pending |
| Media clinic.caveat | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.stock_note {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Imported; admin pending |
| Media lab.source / file | app.js L1 PHOTOS.lab; exact URL in audit | MediaAsset(demo-photo-lab).source_url/path | Media library | Legacy external initial image source for Diagnostics split; gallery #3 | Imported; admin pending |
| Media lab.title | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.title {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Imported; admin pending |
| Media lab.default_alt | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.alt_text {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Imported; admin pending |
| Media lab.credit | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.provenance_note (no supplied credit) {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Imported; admin pending |
| Media lab.caveat | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.stock_note {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Imported; admin pending |
| Media pregnancy.source / file | app.js L1 PHOTOS.pregnancy; exact URL in audit | MediaAsset(demo-photo-pregnancy).source_url/path | Media library | Legacy external initial image source for pregnancy-care article; gallery #4 | Imported; admin pending |
| Media pregnancy.title | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.title {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Imported; admin pending |
| Media pregnancy.default_alt | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.alt_text {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Imported; admin pending |
| Media pregnancy.credit | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.provenance_note (no supplied credit) {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Imported; admin pending |
| Media pregnancy.caveat | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.stock_note {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Imported; admin pending |
| Media care.source / file | app.js L1 PHOTOS.care; exact URL in audit | MediaAsset(demo-photo-care).source_url/path | Media library | Legacy external initial image source for About split; pcos article; gallery #7 | Imported; admin pending |
| Media care.title | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.title {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Imported; admin pending |
| Media care.default_alt | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.alt_text {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Imported; admin pending |
| Media care.credit | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.provenance_note (no supplied credit) {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Imported; admin pending |
| Media care.caveat | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.stock_note {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Imported; admin pending |
| Media interior.source / file | app.js L1 PHOTOS.interior; exact URL in audit | MediaAsset(demo-photo-interior).source_url/path | Media library | Legacy external initial image source for gallery #6 | Imported; admin pending |
| Media interior.title | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.title {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Imported; admin pending |
| Media interior.default_alt | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.alt_text {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Imported; admin pending |
| Media interior.credit | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.provenance_note (no supplied credit) {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Imported; admin pending |
| Media interior.caveat | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.stock_note {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Imported; admin pending |
| Media team.source / file | app.js L1 PHOTOS.team; exact URL in audit | MediaAsset(demo-photo-team).source_url/path | Media library | Legacy external initial image source for gallery #5 | Imported; admin pending |
| Media team.title | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.title {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Imported; admin pending |
| Media team.default_alt | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.alt_text {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Imported; admin pending |
| Media team.credit | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.provenance_note (no supplied credit) {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Imported; admin pending |
| Media team.caveat | L1 photo identity; L2 galleryNote; L11 captions | MediaAsset.stock_note {en,bn}; credit blank if not supplied | Media library | Media/default accessible text; never invent credit or identity | Imported; admin pending |
| Media file technical metadata | External assets; none bundled | MediaAsset.mime_type/size/width/height/disk/original_filename | Media library | Safe dimensions/storage URLs; not editor-written copy | Imported; admin pending |

## Literal headings, shared links, photos, metadata, forms and code-owned presentation

| Public field / collection member | Source location | Proposed database owner | Future admin screen | Public rendering location | Status |
| --- | --- | --- | --- | --- | --- |
| Clinic display name / CTA, diagnostics, contact eyebrow | L8/L18/L22 DEEPAN IUI LAB | SiteSetting.clinic_name {en,bn} | Shared clinic settings | Three reused eyebrow slots; visit format | Imported; admin pending |
| Official Facebook target | L7/L22 https://www.facebook.com/drshahalamtalukder/ | SiteSetting.facebook_url {en,bn} | Shared clinic settings | Footer/contact standalone link; never submit action | Imported; admin pending |
| Google Maps target | L22 https://www.google.com/maps/search/?api=1&query=4S+Tower+628+East+Bogura+Road+Barishal | SiteSetting.map_url {en,bn} | Shared clinic settings | Contact map href | Imported; admin pending |
| Footer social label | L7 Facebook ↗ | ContentField.footer.social_label {en,bn} | Footer/shared copy | Footer standalone social anchor | Imported; admin pending |
| Copyright format/year/caveat | L7 © current year brand · galleryNote | ContentField.footer.copyright_format {en,bn}; allowed {year},{doctor_name},{stock_notice} | Footer/shared copy | Footer-bottom; year server-derived; canonical settings/notice | Imported; admin pending |
| Brand link / mark | L6/L7 index.html; ✳ | ContentField.shared.brand_target {en,bn}; SiteSetting.brand_icon enum | Shared settings/copy | Header logo href; header/footer brandmark | Imported; admin pending |
| Home location_eyebrow | L14 BARISHAL | SiteSetting.location_label {en,bn} | Home page fields | Home floating/care/journal/gallery headings | Imported; admin pending |
| Home care_eyebrow | L14 OUR AREAS OF CARE | ContentField.home.care_eyebrow {en,bn} | Home page fields | Home floating/care/journal/gallery headings | Imported; admin pending |
| Home journal_eyebrow | L14 THE JOURNAL | ContentField.home.journal_eyebrow {en,bn} | Home page fields | Home floating/care/journal/gallery headings | Imported; admin pending |
| Home gallery_eyebrow | L14 VISUAL STORIES | ContentField.home.gallery_eyebrow {en,bn} | Home page fields | Home floating/care/journal/gallery headings | Imported; admin pending |
| hero photo slot | L14 home PHOTOS.hero | SiteSetting.portrait_media_asset_id FK | Page image slots | Home/about/diagnostics image; shape/class code-owned | Imported; admin pending |
| hero image alt | L14 home Illustrative healthcare portrait — not the actual doctor | ContentField(home.hero_alt).value {en,bn} | Page image slots | Corresponding img alt; original caveat retained | Imported; admin pending |
| doctor photo slot | L14 home PHOTOS.consult | ContentField(home.doctor_image).media_asset_id | Page image slots | Home/about/diagnostics image; shape/class code-owned | Imported; admin pending |
| doctor image alt | L14 home Healthcare consultation illustration | ContentField(home.doctor_alt).value {en,bn} | Page image slots | Corresponding img alt; original caveat retained | Imported; admin pending |
| about photo slot | L15 about PHOTOS.care | ContentField.about.image media_asset_id FK | Page image slots | Home/about/diagnostics image; shape/class code-owned | Imported; admin pending |
| about image alt | L15 about Illustrative consultation photo | ContentField.about.image_alt {en,bn} | Page image slots | Corresponding img alt; original caveat retained | Imported; admin pending |
| diagnostics photo slot | L18 diagnostics PHOTOS.lab | ContentField.diagnostics.image media_asset_id FK | Page image slots | Home/about/diagnostics image; shape/class code-owned | Imported; admin pending |
| diagnostics image alt | L18 diagnostics Illustrative lab stock photograph | ContentField.diagnostics.image_alt {en,bn} | Page image slots | Corresponding img alt; original caveat retained | Imported; admin pending |
| UI switch_to_bn | L6 বাংলা | ContentField.ui.switch_to_bn {en,bn} | Shared UI copy | Language/menu/dialog accessibility | Imported; admin pending |
| UI switch_to_en | L6 English | ContentField.ui.switch_to_en {en,bn} | Shared UI copy | Language/menu/dialog accessibility | Imported; admin pending |
| UI open_navigation | L6 Open navigation | ContentField.ui.open_navigation {en,bn} | Shared UI copy | Language/menu/dialog accessibility | Imported; admin pending |
| UI gallery_preview | L23 Gallery image preview | ContentField.ui.gallery_preview {en,bn} | Shared UI copy | Language/menu/dialog accessibility | Imported; admin pending |
| UI close_lightbox | L23 Close | ContentField.ui.close_lightbox {en,bn} | Shared UI copy | Language/menu/dialog accessibility | Imported; admin pending |
| Page title format | L23 localized page label \| brand | ContentField.meta.title_format {en,bn}; allowed page_label/doctor_name | Page metadata | Public title; actual post title intentional improvement | Imported; admin pending |
| about.meta_title | frontend/about.html head; L23 dynamic title | Page(about).meta_title {en,bn} | Page metadata | Page head; eight types; no stock source meta fallback | Imported; admin pending |
| about.meta_description | frontend/about.html head; L23 dynamic title | Page(about).meta_description {en,bn} | Page metadata | Page head; eight types; no stock source meta fallback | Imported; admin pending |
| article.meta_title | frontend/article.html head; L23 dynamic title | Page(article).meta_title {en,bn} | Page metadata | Page head; eight types; no stock source meta fallback | Imported; admin pending |
| article.meta_description | frontend/article.html head; L23 dynamic title | Page(article).meta_description {en,bn} | Page metadata | Page head; eight types; no stock source meta fallback | Imported; admin pending |
| blog.meta_title | frontend/blog.html head; L23 dynamic title | Page(blog).meta_title {en,bn} | Page metadata | Page head; eight types; no stock source meta fallback | Imported; admin pending |
| blog.meta_description | frontend/blog.html head; L23 dynamic title | Page(blog).meta_description {en,bn} | Page metadata | Page head; eight types; no stock source meta fallback | Imported; admin pending |
| contact.meta_title | frontend/contact.html head; L23 dynamic title | Page(contact).meta_title {en,bn} | Page metadata | Page head; eight types; no stock source meta fallback | Imported; admin pending |
| contact.meta_description | frontend/contact.html head; L23 dynamic title | Page(contact).meta_description {en,bn} | Page metadata | Page head; eight types; no stock source meta fallback | Imported; admin pending |
| diagnostics.meta_title | frontend/diagnostics.html head; L23 dynamic title | Page(diagnostics).meta_title {en,bn} | Page metadata | Page head; eight types; no stock source meta fallback | Imported; admin pending |
| diagnostics.meta_description | frontend/diagnostics.html head; L23 dynamic title | Page(diagnostics).meta_description {en,bn} | Page metadata | Page head; eight types; no stock source meta fallback | Imported; admin pending |
| gallery.meta_title | frontend/gallery.html head; L23 dynamic title | Page(gallery).meta_title {en,bn} | Page metadata | Page head; eight types; no stock source meta fallback | Imported; admin pending |
| gallery.meta_description | frontend/gallery.html head; L23 dynamic title | Page(gallery).meta_description {en,bn} | Page metadata | Page head; eight types; no stock source meta fallback | Imported; admin pending |
| home.meta_title | frontend/index.html head; L23 dynamic title | Page(home).meta_title {en,bn} | Page metadata | Page head; eight types; no stock source meta fallback | Imported; admin pending |
| home.meta_description | frontend/index.html head; L23 dynamic title | Page(home).meta_description {en,bn} | Page metadata | Page head; eight types; no stock source meta fallback | Imported; admin pending |
| services.meta_title | frontend/services.html head; L23 dynamic title | Page(services).meta_title {en,bn} | Page metadata | Page head; eight types; no stock source meta fallback | Imported; admin pending |
| services.meta_description | frontend/services.html head; L23 dynamic title | Page(services).meta_description {en,bn} | Page metadata | Page head; eight types; no stock source meta fallback | Imported; admin pending |
| Theme color/fonts/design classes | All HTML #205d54; style.css L1/L2 | Code-owned fixed design, not content JSON | None | Public layout CSS/font loading | Code-owned; preserved |
| Shared section visibility and home collection limit/order | L14 sections; L10/L11 limits 3; L8 CTA | PageSection.is_visible only for registry-approved optional sections; Post/GalleryImage sort_order; ContentField(home.article_count/home.gallery_count).value | Pages/collection screens | Fixed sections; first 3 items; required caveats cannot be hidden | Imported; admin pending |
| home.primary_target | L14 contact.html | ContentField.home.primary_target {en,bn}; known route allowlist | Page fields/CTA | Home appointment CTA | Imported; admin pending |
| home.secondary_target | L14 services.html | ContentField.home.secondary_target {en,bn}; known route allowlist | Page fields/CTA | Home explore services | Imported; admin pending |
| home.doctor_target | L14 about.html | ContentField.home.doctor_target {en,bn}; known route allowlist | Page fields/CTA | Home doctor details | Imported; admin pending |
| home.journal_target | L14 blog.html | ContentField.home.journal_target {en,bn}; known route allowlist | Page fields/CTA | Home journal details | Imported; admin pending |
| home.gallery_target | L14 gallery.html | ContentField.home.gallery_target {en,bn}; known route allowlist | Page fields/CTA | Home gallery details | Imported; admin pending |
| cta.target | L8 contact.html | SiteSetting.cta_target {en,bn}; known route allowlist | Page fields/CTA | Shared CTA | Imported; admin pending |
| diagnostics.contact_target | L18 contact.html | ContentField.diagnostics.contact_target {en,bn}; known route allowlist | Page fields/CTA | Diagnostic contact link | Imported; admin pending |
| Four independent consultation options | L22 obtitle/gytitle/iuititle/diatitle | ConsultationOption.label {en,bn}; stable key values | Consultation options/contact form | Appointment select only; general stores no topic | Imported; admin pending |
| Original clipboard text/success (historical) | L23 bilingual header; Name/Phone/Topic/Message; Copied! / কপি হয়েছে! | Audit provenance only; replaced, no production runtime field | None | Removed from new submission behavior | Historical provenance only |
| New UI/form mode_label | Owner contract / architecture addition; no original string | ContentField(contact.mode_label).value {en,bn} | Shared UI/contact copy | Mode selector label | Imported; admin pending |
| New UI/form mode_appointment | Owner contract / architecture addition; no original string | ContentField(contact.mode_appointment).value {en,bn} | Shared UI/contact copy | Appointment selector option | Imported; admin pending |
| New UI/form mode_general | Owner contract / architecture addition; no original string | ContentField(contact.mode_general).value {en,bn} | Shared UI/contact copy | General contact option | Imported; admin pending |
| New UI/form submit_success | Owner contract / architecture addition; no original string | ContentField(contact.success_appointment / contact.success_contact).value {en,bn} | Shared UI/contact copy | Saved-success flash/status | Imported; admin pending |
| New UI/form not_confirmed_notice | Owner contract / architecture addition; no original string | ContentField(contact.privacy).value {en,bn} | Shared UI/contact copy | Always-present appointment-request caveat | Imported; admin pending |
| New UI/form appointment_guidance | Owner contract / architecture addition; no original string | ContentField(contact.privacy).value {en,bn} | Shared UI/contact copy | Appointment form helper | Imported; admin pending |
| New UI/form general_guidance | Owner contract / architecture addition; no original string | ContentField(contact.privacy).value {en,bn} | Shared UI/contact copy | General message form helper | Imported; admin pending |
| New UI/form mode_apply | Owner contract / architecture addition; no original string | ContentField(contact.mode_apply).value {en,bn} | Shared UI/contact copy | No-JS GET mode switch action | Imported; admin pending |
| New UI/form validation_required | Owner contract / architecture addition; no original string | ContentField(contact.validation_required).value {en,bn} | Shared UI/contact copy | Required field errors | Imported; admin pending |
| New UI/form validation_invalid | Owner contract / architecture addition; no original string | ContentField(contact.validation_invalid).value {en,bn} | Shared UI/contact copy | Invalid input errors | Imported; admin pending |
| New UI/form validation_max | Owner contract / architecture addition; no original string | ContentField(contact.validation_max).value {en,bn} | Shared UI/contact copy | Length validation errors | Imported; admin pending |
| New UI/form save_failure | Owner contract / architecture addition; no original string | ContentField(contact.save_failure).value {en,bn} | Shared UI/contact copy | No-success failure message | Imported; admin pending |
| New UI/form submit_pending | Owner contract / architecture addition; no original string | ContentField(contact.submit_pending).value {en,bn} | Shared UI/contact copy | Optional disabled button status | Imported; admin pending |
| New UI/form empty_filter_results | Owner contract / architecture addition; no original string | ContentField(ui.empty_filter_results).value {en,bn} | Shared UI/contact copy | Blog/gallery empty state | Imported; admin pending |
| New UI/form menu_close | Owner contract / architecture addition; no original string | ContentField(ui.menu_close).value {en,bn} | Shared UI/contact copy | Navigation accessibility | Imported; admin pending |
| New UI/form lightbox_previous_focus | Owner contract / architecture addition; no original string | code-owned focus restoration (no displayed copy) {en,bn} | Shared UI/contact copy | Accessible enhancement copy if displayed | Code-owned; implemented |
| Inquiry form name/phone/message/locale/type values | L22 inputs; new small mode selector | Inquiry.name/phone/message/locale/kind private input; not localized public website text | Private inquiries | Contact POST → PRG only; never public list | Implemented; admin pending |
| Inquiry consultation_type/status/admin note | L22 topic; private workflow addition | Inquiry.consultation_option_id/key/label snapshot; status/internal_notes/confirmed_visit_at/handled_by/handled_at | Private inquiries | Topic field for appointment only; admin status/note never public | Implemented; admin pending |

## Implementation notes

- Registry names above are the proposed contract; reconcile final names when implementing without dropping any row. All key/URL/format types have code-defined validation in config/cms_models.php, config/content_fields.php, config/page_sections.php, config/private_content_fields.php and ContentRegistry. Admin editors expose individual bilingual fields, not JSON payload editing.
- The 81 originals are initialization data, not runtime fallback. Old inquiry privacy/CTA text is intentionally revised; all four full articles, gallery captions and services descriptions retain the original caveats and content.
- Header/footer item labels can be edited per group. Shared identity/contact facts are single settings. Card link-label copies are deliberate collection ownership; shared read/view-more/filter copy remains single registered fields.
- Article metadata is individually editable. Original topic words are eyebrows; no real dates were supplied. Published records require published_at; initial values are the import timestamp, not historical dates or editorial badges.
- Fixed glyphs (menu/close/arrows), bullet ornament and service numbering are presentation code. Selectable branding/care icons are safe codes in structured admin controls; no raw SVG/HTML.
- New query/login/filter/language/mode-switch forms have purpose-specific routes; only POST /inquiries writes private inquiry records.


## Step 3 actual schema reconciliation and additional fields

The source rows above remain the full original inventory. Actual initial database content is populated; no admin content entry is needed. All 81 destination pairs, four article bodies, five service blocks, four cards, eight gallery entries, nine media URLs and page metadata were verified. See SEED_CONTENT_REPORT for exact counts/copy and docs/ARCHITECTURE.md for the actual schema.

| Field / collection | Source | Database owner | Future admin screen | Public rendering | Status |
| --- | --- | --- | --- | --- | --- |
| Four consultation labels/keys | app.js contact() obtitle/gytitle/iuititle/diatitle | ConsultationOption.label/key | Consultation options | Appointment-only select | Imported; admin pending |
| Consultation order/active flag | Original option order | ConsultationOption.sort_order/is_active | Consultation options | Appointment options | Imported; admin pending |
| Appointment submit label | Owner Step 3 exact copy | ContentField(contact.prepare).value | Contact copy | Appointment submit | Imported; admin pending |
| Contact submit label | Owner Step 3 exact copy | ContentField(contact.submit_contact).value | Contact copy | General submit | Imported; admin pending |
| Two success messages | Owner Step 3 exact copy | ContentField(contact.success_appointment/success_contact).value | Contact copy | Success flash after save | Imported; admin pending |
| Default locale | Owner contract | SiteSetting.default_locale | Shared settings | Locale middleware | Imported; admin pending |
| Homepage article/gallery counts | articleCards(3)/galleryGrid(3) | ContentField(home.article_count/home.gallery_count).value | Home page | DB preview limits | Imported; admin pending |
| Fixed sections visibility and position | Original page renderers | PageSection.key/page_id/is_visible/sort_order | Pages | Home optional sections / shared per-page CTA | 20 imported, all visible; positions code-owned |
| Category applicability | filters(article/gallery) | Category.for_blog/for_gallery | Categories | Filter controls | Imported; admin pending |
| Post homepage flag/publication timestamp | First-three source previews; owner initialization rule | Post.on_homepage/published_at | Posts | Published previews and detail scope; timestamp never badge | Imported; admin pending |
| Gallery homepage flag | First-three source previews | GalleryImage.on_homepage | Gallery | Homepage gallery preview | Imported; admin pending |
| Media provenance/stock caveat | Original URLs + stock notice | MediaAsset.provenance_note/stock_note | Media library | Image source/stock caveat | Imported; admin pending |
| Media path/disk/MIME/size/dimensions/filename | Future real uploads; unknown for external initial URLs | MediaAsset.path/disk/mime_type/size/width/height/original_filename | Media library | Storage URL when local; null technical metadata initially | Schema/validation implemented; upload UI pending |
| Private inquiry name/phone/email/message | Visitor submission (email not shown initially) | Inquiry.name/phone/email/message | Private inquiries | POST only; never public listings | Implemented; admin pending |
| Private kind/reference/locale | Submission context/generated ULID | Inquiry.kind/public_reference/locale | Private inquiries | No public reference or PII listing | Implemented; admin pending |
| Consultation submission snapshot | Chosen active consultation option | Inquiry.consultation_option_id/key/label | Private inquiries | Stored appointment context; general clears it | Implemented; admin pending |
| Private status/notes/confirmed time/handler | Staff workflow; not a booking promise | Inquiry.status/internal_notes/confirmed_visit_at/handled_by/handled_at | Private inquiries | No public rendering | Schema implemented; admin pending |
| Admin identity/authorization | Owner-supplied interactive provisioning in Step 5 | User.name/email/password/is_admin | Private login/profile | No public user content | Provisioning/auth/profile implemented; no owner account created automatically |
| Completion marker | Successful explicit import only | content_initializations.key/snapshot_sha256/completed_at | No editor | No public rendering | Completed; normal repeated seed skips import |

Canonical field formats reference shared settings instead of storing duplicate doctor/address/hours text. Post bodies are localized arrays of plain paragraphs; no executable templates. Required English validation is enforced on saves; optional Bangla/optional fields use the shared safe resolver. All source literals without Bangla receive simple translations, including headings, accessibility labels, badges and metadata. The unused blogdesc remains blog.blogdesc. Historical credit rows mean provenance controls; no photographer credit was supplied or invented. Displayed copies of fixed glyphs remain code-owned. Unused proposed helper strings are covered by contact.privacy or code-owned focus behavior; there is no arbitrary JSON editor.

Future admin screens must use the code registry’s field control/owner/group/rules, and individual English / বাংলা controls. Schema and public rendering are implemented; an admin editor is not yet claimed for any row.

## Step 4 rendering reconciliation

Every original source row retains the same database owner; no second content catalog or seed fallback was introduced. `blog.blogdesc` remains intentionally unrendered because the original renderer never used it. Provenance/technical media fields and private inquiry/staff fields are administrative data, not invented public copy.

| Fields/collection | Existing database owner | Rendering location / Step 4 status |
| --- | --- | --- |
| Shared branding/subtitle/address/hours/Friday | SiteSetting + shared.brand_target | public.parts.topbar/navigation/footer; locale-aware safe targets, escaped text |
| Header/footer labels/targets/order/visibility | NavigationItem | public.parts.navigation/footer; shared contact heading still reads header:contact label even when its link is hidden |
| Shared CTA/copyright/footer/notices | SiteSetting + registered footer/UI fields | public.parts.cta/footer/notice; diagnostic notice remains its own diagnostics.diagnotice; essential notices retained |
| Home cards/icons/targets | CareCard | public.parts.care-card; four enabled initial rows, original glyphs/classes/order |
| Services blocks/title/body | Service | public.pages.services; five enabled rows, code-owned numbering |
| Category slugs/labels/applicability | Category | public.parts.filters; validated GET links, server collection filtering, locale retained, no new tagging system |
| Post title/excerpt/badge/category/media/alt/body/metadata | Post + Category + MediaAsset | public.parts.article-card/paragraphs and public.pages.article; published-only slug binding, no date substitution, escaped paragraph arrays |
| Gallery caption/alt/category/media/visibility/order | GalleryImage + Category + MediaAsset | public.parts.gallery-item/gallery/lightbox; escaped attributes, localized captions/alt and safe URLs |
| All page headings/copy/link labels/media/SEO | ContentField + Page + approved PageSection + canonical SiteSetting | Existing eight public.pages templates within public.layouts.app; fixed original hierarchy/classes/order; all primary content server-rendered |
| Locale button/filter empty state/menu/lightbox accessible labels | Existing registered ui.* fields | navigation/filters/articles/gallery/lightbox; no hardcoded replacement language dictionary |
| Contact mode/name/phone/topic/message/submit/privacy/success/errors | Existing contact.* fields + ConsultationOption | public.pages.contact and InquiryController; mode GET separate from POST, hidden validated locale, safe internal redirect, success only after existing save |
| Article editor paragraph controls | Code-owned private editor controls; future Post.body values | Reusable admin.partials.paragraph-repeater/paragraph-row + admin.js; add/remove/reorder implemented, escaped arrays; article CRUD remains pending |

Homepage counts and flags still select exactly the original first three articles/gallery entries. No content records, translations, visibility/publication decisions, source assets or initialization snapshot were changed in Step 4. Public interaction CSS is separate from the unchanged baseline and admin Tailwind. PUBLIC_VERIFICATION records intentional functional/control differences and the manual visual checklist.

## Step 5 admin infrastructure

Public owners/values/rendering above are unchanged. This step implements private account screens and reusable infrastructure; it does not claim settings/page/nav/collection/inbox editors are complete.

| Field/control | Owner | Implemented screen/component | Status |
| --- | --- | --- | --- |
| Admin name/email | User; validated whitelist, canonical email | /admin/profile; current-password verification | Implemented |
| Password/current-password challenge | User hashed cast; private request fields never flashed/rendered | /admin/profile password form; admin:create hidden prompts | Implemented; no automatic account |
| Explicit admin authorization | User.is_admin + access-admin Gate | RequireAdmin, protected route group, AdminController base | Implemented; no public privilege editing |
| Dashboard published/visible/new-request counts | Post/GalleryImage/Inquiry database scopes | /admin | Implemented real aggregates; no private request listings/fake activity |
| English/Bangla localized text controls | Existing localized JSON owner fields | x-admin.localized-tabs with input/textarea components | Reusable controls implemented; both languages retained in one form |
| Article plain paragraph body controls | Post.body | x-admin.paragraph-repeater → existing shared partials | Add/remove/reorder and language tabs implemented; article CRUD pending |
| Bilingual atomic update boundary | Code registry + existing CmsModel owner | AdminContentWriter | Tested whitelist/language-key validation, trimming, atomic save and omitted-Bangla preservation; no new CMS write routes yet |
| Missing-Bangla indicator/fallback help | Current field's bn value + shared public LocalizedContent rule | Localized tabs | Implemented indicator; no translation API |
| Tables/pagination/badges/flash/errors/buttons/breadcrumbs | Code-owned private UI | components/admin and admin partials | Implemented; Tailwind pagination only in private bundle |
| Media picker | Existing media reference | x-admin.media-picker | Disabled truthful placeholder; existing reference unchanged; upload/library pending |
| Delete confirmation | Authorized future entity action | x-admin.delete-confirmation | CSRF/DELETE + dialog or no-JS checkbox consent; actual delete routes pending |
| Planned sidebar destinations | Code-owned config/admin_navigation.php | Sidebar | All requested groups present; only Dashboard/Profile links active |
