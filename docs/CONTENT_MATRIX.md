# Public content ownership matrix

Updated after Step 8, 2026-10-10. `frontend/` is the unmodified reference. Shared settings, fixed pages, navigation, media, care-card, service-block and consultation-option editors are implemented; article/category/gallery and inquiry staff editors remain pending. The exact Step 7/8 reconciliations below supersede historical proposal names and pending statuses. `Imported; admin pending` denotes the remaining initialized collection fields. Shared identity/address/hours stay canonical. All managed text uses `{en,bn}` and LocalizedContent; public OG/remaining SEO is Step 13.

The first table contains every one of the 81 dictionary keys, including unused blogdesc, with individual source, DB owner, admin screen, rendering use and status. Subsequent tables enumerate collection fields and extra literal/structural copy. Exact bilingual values and slugs are preserved in FRONTEND_AUDIT. Shared fact prose uses allowlisted format placeholders as specified in ARCHITECTURE.


## Dictionary fields: 81 rows

| Public field | Exact source | Proposed database owner | Future admin screen | Public rendering location | Status |
| --- | --- | --- | --- | --- | --- |
| home | frontend/assets/app.js L2 content.en/bn.home | NavigationItem(header:home).label; Page(home).label | `/admin/navigation`; `/admin/pages/home/edit` | Header; matching page hero/title; footer subset via footer items | Editor/render implemented |
| about | frontend/assets/app.js L2 content.en/bn.about | NavigationItem(header:about).label; Page(about).label; NavigationItem(footer:about).label | `/admin/navigation`; `/admin/pages/about/edit` | Header; matching page hero/title; footer subset via footer items | Editor/render implemented |
| services | frontend/assets/app.js L2 content.en/bn.services | NavigationItem(header:services).label; Page(services).label; NavigationItem(footer:services).label | `/admin/navigation`; `/admin/pages/services/edit` | Header; matching page hero/title; footer subset via footer items | Editor/render implemented |
| diagnostics | frontend/assets/app.js L2 content.en/bn.diagnostics | NavigationItem(header:diagnostics).label; Page(diagnostics).label | `/admin/navigation`; `/admin/pages/diagnostics/edit` | Header; matching page hero/title; footer subset via footer items | Editor/render implemented |
| blog | frontend/assets/app.js L2 content.en/bn.blog | NavigationItem(header:blog).label; Page(blog).label; NavigationItem(footer:blog).label; Page(article).label | `/admin/navigation`; `/admin/pages/blog/edit`; `/admin/pages/article/edit` | Header; matching page hero/title; footer subset via footer items | Editor/render implemented |
| gallery | frontend/assets/app.js L2 content.en/bn.gallery | NavigationItem(header:gallery).label; Page(gallery).label; NavigationItem(footer:gallery).label | `/admin/navigation`; `/admin/pages/gallery/edit` | Header; matching page hero/title; footer subset via footer items | Editor/render implemented |
| contact | frontend/assets/app.js L2 content.en/bn.contact | NavigationItem(header:contact).label; Page(contact).label | `/admin/navigation`; `/admin/pages/contact/edit` | Header; matching page hero/title; footer subset via footer items | Editor/render implemented |
| appointment | frontend/assets/app.js L2 content.en/bn.appointment | ContentField(home.appointment).value | `/admin/pages/home/edit` | Corresponding home hero/care/doctor/journal slot; herodesc permits canonical fact placeholders | Editor/render implemented |
| brand | frontend/assets/app.js L2 content.en/bn.brand | SiteSetting(primary).doctor_name | `/admin/settings?group=doctor` | Brand/header/footer; about; page-title format | Editor/render implemented |
| subtitle | frontend/assets/app.js L2 content.en/bn.subtitle | SiteSetting(primary).subtitle | `/admin/settings?group=branding` | Header brand subtitle | Editor/render implemented |
| eyebrow | frontend/assets/app.js L2 content.en/bn.eyebrow | ContentField(home.eyebrow).value | `/admin/pages/home/edit` | Corresponding home hero/care/doctor/journal slot; herodesc permits canonical fact placeholders | Editor/render implemented |
| herotitle | frontend/assets/app.js L2 content.en/bn.herotitle | ContentField(home.herotitle).value | `/admin/pages/home/edit` | Corresponding home hero/care/doctor/journal slot; herodesc permits canonical fact placeholders | Editor/render implemented |
| herodesc | frontend/assets/app.js L2 content.en/bn.herodesc | ContentField(home.herodesc).value | `/admin/pages/home/edit` | Corresponding home hero/care/doctor/journal slot; herodesc permits canonical fact placeholders | Editor/render implemented |
| credential | frontend/assets/app.js L2 content.en/bn.credential | SiteSetting(primary).credential | `/admin/settings?group=doctor` | Home stats/bullets; about bullets; canonical prose placeholders | Editor/render implemented |
| department | frontend/assets/app.js L2 content.en/bn.department | SiteSetting(primary).department | `/admin/settings?group=doctor` | Home stats/bullets; about bullets; canonical prose placeholders | Editor/render implemented |
| place | frontend/assets/app.js L2 content.en/bn.place | SiteSetting(primary).institution | `/admin/settings?group=doctor` | Home stats/bullets; about bullets; canonical prose placeholders | Editor/render implemented |
| visit | frontend/assets/app.js L2 content.en/bn.visit | ContentField(home.visit).value | `/admin/pages/home/edit` | Corresponding home hero/care/doctor/journal slot; herodesc permits canonical fact placeholders | Editor/render implemented |
| explore | frontend/assets/app.js L2 content.en/bn.explore | ContentField(ui.explore).value | `/admin/settings?group=ui` | explore: home services link/footer heading; read: article action; readmore: home about/blog/gallery links | Editor/render implemented |
| servicesTitle | frontend/assets/app.js L2 content.en/bn.servicesTitle | ContentField(home.servicesTitle).value | `/admin/pages/home/edit` | Corresponding home hero/care/doctor/journal slot; herodesc permits canonical fact placeholders | Editor/render implemented |
| servicesDesc | frontend/assets/app.js L2 content.en/bn.servicesDesc | ContentField(home.servicesDesc).value | `/admin/pages/home/edit` | Corresponding home hero/care/doctor/journal slot; herodesc permits canonical fact placeholders | Editor/render implemented |
| obtitle | frontend/assets/app.js L2 content.en/bn.obtitle | CareCard(obstetrics).title; ConsultationOption(obstetrics).label | `/admin/care-cards` · dedicated card form; `/admin/consultation-options` · independent option form | Homepage card; appointment topic option | Editor/render implemented |
| obdesc | frontend/assets/app.js L2 content.en/bn.obdesc | CareCard(obstetrics).description | `/admin/care-cards` · dedicated card form | Homepage card description | Editor/render implemented |
| gytitle | frontend/assets/app.js L2 content.en/bn.gytitle | CareCard(gynecology).title; ConsultationOption(gynecology).label | `/admin/care-cards` · dedicated card form; `/admin/consultation-options` · independent option form | Homepage card; appointment topic option | Editor/render implemented |
| gydesc | frontend/assets/app.js L2 content.en/bn.gydesc | CareCard(gynecology).description | `/admin/care-cards` · dedicated card form | Homepage card description | Editor/render implemented |
| iuititle | frontend/assets/app.js L2 content.en/bn.iuititle | CareCard(fertility).title; ConsultationOption(fertility).label | `/admin/care-cards` · dedicated card form; `/admin/consultation-options` · independent option form | Homepage card; appointment topic option | Editor/render implemented |
| iuidesc | frontend/assets/app.js L2 content.en/bn.iuidesc | CareCard(fertility).description | `/admin/care-cards` · dedicated card form | Homepage card description | Editor/render implemented |
| diatitle | frontend/assets/app.js L2 content.en/bn.diatitle | CareCard(diagnostics).title; ConsultationOption(diagnostics).label | `/admin/care-cards` · dedicated card form; `/admin/consultation-options` · independent option form | Homepage card; appointment topic option | Editor/render implemented |
| diadesc | frontend/assets/app.js L2 content.en/bn.diadesc | CareCard(diagnostics).description | `/admin/care-cards` · dedicated card form | Homepage card description | Editor/render implemented |
| learn | frontend/assets/app.js L2 content.en/bn.learn | CareCard(all four).link_label | `/admin/care-cards` · dedicated card form | Each home care-card action label | Editor/render implemented |
| doctorEyebrow | frontend/assets/app.js L2 content.en/bn.doctorEyebrow | ContentField(home.doctorEyebrow).value | `/admin/pages/home/edit` | Corresponding home hero/care/doctor/journal slot; herodesc permits canonical fact placeholders | Editor/render implemented |
| doctorTitle | frontend/assets/app.js L2 content.en/bn.doctorTitle | ContentField(home.doctorTitle).value | `/admin/pages/home/edit` | Corresponding home hero/care/doctor/journal slot; herodesc permits canonical fact placeholders | Editor/render implemented |
| doctorText | frontend/assets/app.js L2 content.en/bn.doctorText | SiteSetting(primary).doctor_biography | `/admin/settings?group=doctor` | Home doctor split; about biography | Editor/render implemented |
| qualification | frontend/assets/app.js L2 content.en/bn.qualification | ContentField(shared.qualification).value | `/admin/settings?group=information` | qualification: about; visitTitle: about/diagnostics; hoursTitle: about | Editor/render implemented |
| profession | frontend/assets/app.js L2 content.en/bn.profession | ContentField(shared.profession).value | `/admin/settings?group=information` | Profession: home stats; visitText: home floating/about/diagnostics; references canonical settings | Editor/render implemented |
| visitTitle | frontend/assets/app.js L2 content.en/bn.visitTitle | ContentField(shared.visitTitle).value | `/admin/settings?group=information` | qualification: about; visitTitle: about/diagnostics; hoursTitle: about | Editor/render implemented |
| visitText | frontend/assets/app.js L2 content.en/bn.visitText | ContentField(shared.visitText).value | `/admin/settings?group=information` | Profession: home stats; visitText: home floating/about/diagnostics; references canonical settings | Editor/render implemented |
| hoursTitle | frontend/assets/app.js L2 content.en/bn.hoursTitle | ContentField(shared.hoursTitle).value | `/admin/settings?group=information` | qualification: about; visitTitle: about/diagnostics; hoursTitle: about | Editor/render implemented |
| hours | frontend/assets/app.js L2 content.en/bn.hours | SiteSetting(primary).consultation_hours | `/admin/settings?group=clinic` | Topbar; about hours card; contact; footer | Editor/render implemented |
| friday | frontend/assets/app.js L2 content.en/bn.friday | SiteSetting(primary).friday_note | `/admin/settings?group=clinic` | Topbar; about hours card; contact; footer | Editor/render implemented |
| blogtitle | frontend/assets/app.js L2 content.en/bn.blogtitle | ContentField(home.blogtitle).value | `/admin/pages/home/edit` | Corresponding home hero/care/doctor/journal slot; herodesc permits canonical fact placeholders | Editor/render implemented |
| blogdesc | frontend/assets/app.js L2 content.en/bn.blogdesc | ContentField(blog.blogdesc).value | `/admin/pages/blog/edit` | Not rendered in original; reserved editable educational copy | Editor implemented; unused source key retained |
| gallerytitle | frontend/assets/app.js L2 content.en/bn.gallerytitle | ContentField(shared.gallerytitle).value | `/admin/settings?group=information` | Gallery hero/home preview; galleryNote: gallery/about/footer copyright | Editor/render implemented |
| gallerydesc | frontend/assets/app.js L2 content.en/bn.gallerydesc | ContentField(shared.gallerydesc).value | `/admin/settings?group=information` | Gallery hero/home preview; galleryNote: gallery/about/footer copyright | Editor/render implemented |
| all | frontend/assets/app.js L2 content.en/bn.all | ContentField(ui.filter_all).value | `/admin/settings?group=ui` | Blog and gallery filters | Editor/render implemented |
| clinical | frontend/assets/app.js L2 content.en/bn.clinical | Category(clinical).label | Categories | Filter buttons/category tags; facility gallery only | Imported; admin pending |
| wellness | frontend/assets/app.js L2 content.en/bn.wellness | Category(wellness).label | Categories | Filter buttons/category tags; facility gallery only | Imported; admin pending |
| facility | frontend/assets/app.js L2 content.en/bn.facility | Category(facility).label | Categories | Filter buttons/category tags; facility gallery only | Imported; admin pending |
| read | frontend/assets/app.js L2 content.en/bn.read | ContentField(ui.read).value | `/admin/settings?group=ui` | explore: home services link/footer heading; read: article action; readmore: home about/blog/gallery links | Editor/render implemented |
| ctatitle | frontend/assets/app.js L2 content.en/bn.ctatitle | SiteSetting(primary).cta_title | `/admin/settings?group=cta` | CTA on seven page types; desc/label intentionally revised for stored inquiry | Editor/render implemented |
| ctadesc | frontend/assets/app.js L2 content.en/bn.ctadesc | SiteSetting(primary).cta_description | `/admin/settings?group=cta` | CTA on seven page types; desc/label intentionally revised for stored inquiry | Editor/render implemented |
| ctabtn | frontend/assets/app.js L2 content.en/bn.ctabtn | SiteSetting(primary).cta_label | `/admin/settings?group=cta` | CTA on seven page types; desc/label intentionally revised for stored inquiry | Editor/render implemented |
| address | frontend/assets/app.js L2 content.en/bn.address | SiteSetting(primary).address | `/admin/settings?group=clinic` | Topbar; contact; footer; visitText placeholder | Editor/render implemented |
| fb | frontend/assets/app.js L2 content.en/bn.fb | ContentField(contact.fb).value | `/admin/settings?group=social` | Contact information card | Editor/render implemented |
| footertext | frontend/assets/app.js L2 content.en/bn.footertext | SiteSetting(primary).footer_description | `/admin/settings?group=footer` | Footer brand column | Editor/render implemented |
| galleryNote | frontend/assets/app.js L2 content.en/bn.galleryNote | SiteSetting(primary).stock_notice; MediaAsset(all nine).stock_note | `/admin/settings?group=notices` | Gallery hero/home preview; galleryNote: gallery/about/footer copyright | Editor/render implemented |
| blogheading | frontend/assets/app.js L2 content.en/bn.blogheading | ContentField(blog.blogheading).value | `/admin/pages/blog/edit` | Blog hero/tag-filter heading | Editor/render implemented |
| blogintro | frontend/assets/app.js L2 content.en/bn.blogintro | ContentField(blog.blogintro).value | `/admin/pages/blog/edit` | Blog hero/tag-filter heading | Editor/render implemented |
| tagtitle | frontend/assets/app.js L2 content.en/bn.tagtitle | ContentField(blog.tagtitle).value | `/admin/pages/blog/edit` | Blog hero/tag-filter heading | Editor/render implemented |
| contacttitle | frontend/assets/app.js L2 content.en/bn.contacttitle | ContentField(contact.contacttitle).value | `/admin/pages/contact/edit` | Contact hero/form; contactdesc/formtitle/name/prepare/privacy intentionally revised | Editor/render implemented |
| contactdesc | frontend/assets/app.js L2 content.en/bn.contactdesc | ContentField(contact.contactdesc).value | `/admin/pages/contact/edit` | Contact hero/form; contactdesc/formtitle/name/prepare/privacy intentionally revised | Editor/render implemented |
| formtitle | frontend/assets/app.js L2 content.en/bn.formtitle | ContentField(contact.formtitle).value | `/admin/settings?group=forms` | Contact hero/form; contactdesc/formtitle/name/prepare/privacy intentionally revised | Editor/render implemented |
| name | frontend/assets/app.js L2 content.en/bn.name | ContentField(contact.name).value | `/admin/settings?group=forms` | Contact hero/form; contactdesc/formtitle/name/prepare/privacy intentionally revised | Editor/render implemented |
| phone | frontend/assets/app.js L2 content.en/bn.phone | ContentField(contact.phone).value | `/admin/settings?group=forms` | Contact hero/form; contactdesc/formtitle/name/prepare/privacy intentionally revised | Editor/render implemented |
| topic | frontend/assets/app.js L2 content.en/bn.topic | ContentField(contact.topic).value | `/admin/settings?group=forms` | Contact hero/form; contactdesc/formtitle/name/prepare/privacy intentionally revised | Editor/render implemented |
| message | frontend/assets/app.js L2 content.en/bn.message | ContentField(contact.message).value | `/admin/settings?group=forms` | Contact hero/form; contactdesc/formtitle/name/prepare/privacy intentionally revised | Editor/render implemented |
| prepare | frontend/assets/app.js L2 content.en/bn.prepare | ContentField(contact.prepare).value | `/admin/settings?group=forms` | Contact hero/form; contactdesc/formtitle/name/prepare/privacy intentionally revised | Editor/render implemented |
| privacy | frontend/assets/app.js L2 content.en/bn.privacy | ContentField(contact.privacy).value | `/admin/settings?group=forms` | Contact hero/form; contactdesc/formtitle/name/prepare/privacy intentionally revised | Editor/render implemented |
| addressLabel | frontend/assets/app.js L2 content.en/bn.addressLabel | ContentField(contact.addressLabel).value | `/admin/pages/contact/edit` | Contact information card | Editor/render implemented |
| hoursLabel | frontend/assets/app.js L2 content.en/bn.hoursLabel | ContentField(contact.hoursLabel).value | `/admin/pages/contact/edit` | Contact information card | Editor/render implemented |
| openmap | frontend/assets/app.js L2 content.en/bn.openmap | ContentField(contact.openmap).value | `/admin/settings?group=social` | Contact information card | Editor/render implemented |
| abouttitle | frontend/assets/app.js L2 content.en/bn.abouttitle | ContentField(about.abouttitle).value | `/admin/pages/about/edit` | About hero | Editor/render implemented |
| aboutdesc | frontend/assets/app.js L2 content.en/bn.aboutdesc | ContentField(about.aboutdesc).value | `/admin/pages/about/edit` | About hero | Editor/render implemented |
| diagtitle | frontend/assets/app.js L2 content.en/bn.diagtitle | ContentField(diagnostics.diagtitle).value | `/admin/pages/diagnostics/edit` | Diagnostics hero/split/inquiry topics/verified-menu caveat | Editor/render implemented |
| diagdesc | frontend/assets/app.js L2 content.en/bn.diagdesc | ContentField(diagnostics.diagdesc).value | `/admin/pages/diagnostics/edit` | Diagnostics hero/split/inquiry topics/verified-menu caveat | Editor/render implemented |
| diagnotice | frontend/assets/app.js L2 content.en/bn.diagnotice | ContentField(diagnostics.diagnotice).value | `/admin/pages/diagnostics/edit` | Diagnostics hero/split/inquiry topics/verified-menu caveat | Editor/render implemented |
| diagtags | frontend/assets/app.js L2 content.en/bn.diagtags | ContentField(diagnostics.diagtags).value | `/admin/pages/diagnostics/edit` | Diagnostics hero/split/inquiry topics/verified-menu caveat | Editor/render implemented |
| diagtagsdesc | frontend/assets/app.js L2 content.en/bn.diagtagsdesc | ContentField(diagnostics.diagtagsdesc).value | `/admin/pages/diagnostics/edit` | Diagnostics hero/split/inquiry topics/verified-menu caveat | Editor/render implemented |
| servicePageTitle | frontend/assets/app.js L2 content.en/bn.servicePageTitle | ContentField(services.servicePageTitle).value | `/admin/pages/services/edit` | Services hero | Editor/render implemented |
| servicePageDesc | frontend/assets/app.js L2 content.en/bn.servicePageDesc | ContentField(services.servicePageDesc).value | `/admin/pages/services/edit` | Services hero | Editor/render implemented |
| disclaimer | frontend/assets/app.js L2 content.en/bn.disclaimer | SiteSetting(primary).medical_disclaimer | `/admin/settings?group=notices` | Services grid and every article; required caveat | Editor/render implemented |
| readmore | frontend/assets/app.js L2 content.en/bn.readmore | ContentField(ui.readmore).value | `/admin/settings?group=ui` | explore: home services link/footer heading; read: article action; readmore: home about/blog/gallery links | Editor/render implemented |

## Navigation labels, destinations and collection controls

| Public field / collection member | Source location | Proposed database owner | Future admin screen | Public rendering location | Status |
| --- | --- | --- | --- | --- | --- |
| header home.label | app.js L6 nav(); content.home | NavigationItem(header:home).label {en,bn} | `/admin/navigation` | header navigation | Editor/render implemented |
| header home.target | app.js L6 index.html | NavigationItem.target {en,bn}; known route validation | `/admin/navigation` | header link href | Editor/render implemented |
| header about.label | app.js L6 nav(); content.about | NavigationItem(header:about).label {en,bn} | `/admin/navigation` | header navigation | Editor/render implemented |
| header about.target | app.js L6 about.html | NavigationItem.target {en,bn}; known route validation | `/admin/navigation` | header link href | Editor/render implemented |
| header services.label | app.js L6 nav(); content.services | NavigationItem(header:services).label {en,bn} | `/admin/navigation` | header navigation | Editor/render implemented |
| header services.target | app.js L6 services.html | NavigationItem.target {en,bn}; known route validation | `/admin/navigation` | header link href | Editor/render implemented |
| header diagnostics.label | app.js L6 nav(); content.diagnostics | NavigationItem(header:diagnostics).label {en,bn} | `/admin/navigation` | header navigation | Editor/render implemented |
| header diagnostics.target | app.js L6 diagnostics.html | NavigationItem.target {en,bn}; known route validation | `/admin/navigation` | header link href | Editor/render implemented |
| header blog.label | app.js L6 nav(); content.blog | NavigationItem(header:blog).label {en,bn} | `/admin/navigation` | header navigation | Editor/render implemented |
| header blog.target | app.js L6 blog.html | NavigationItem.target {en,bn}; known route validation | `/admin/navigation` | header link href | Editor/render implemented |
| header gallery.label | app.js L6 nav(); content.gallery | NavigationItem(header:gallery).label {en,bn} | `/admin/navigation` | header navigation | Editor/render implemented |
| header gallery.target | app.js L6 gallery.html | NavigationItem.target {en,bn}; known route validation | `/admin/navigation` | header link href | Editor/render implemented |
| header contact.label | app.js L6 nav(); content.contact | NavigationItem(header:contact).label {en,bn} | `/admin/navigation` | header navigation | Editor/render implemented |
| header contact.target | app.js L6 contact.html | NavigationItem.target {en,bn}; known route validation | `/admin/navigation` | header link href | Editor/render implemented |
| footer about.label | app.js L7 footer(); content.about | NavigationItem(footer:about).label {en,bn} | `/admin/navigation` | footer navigation | Editor/render implemented |
| footer about.target | app.js L7 about.html | NavigationItem.target {en,bn}; known route validation | `/admin/navigation` | footer link href | Editor/render implemented |
| footer services.label | app.js L7 footer(); content.services | NavigationItem(footer:services).label {en,bn} | `/admin/navigation` | footer navigation | Editor/render implemented |
| footer services.target | app.js L7 services.html | NavigationItem.target {en,bn}; known route validation | `/admin/navigation` | footer link href | Editor/render implemented |
| footer blog.label | app.js L7 footer(); content.blog | NavigationItem(footer:blog).label {en,bn} | `/admin/navigation` | footer navigation | Editor/render implemented |
| footer blog.target | app.js L7 blog.html | NavigationItem.target {en,bn}; known route validation | `/admin/navigation` | footer link href | Editor/render implemented |
| footer gallery.label | app.js L7 footer(); content.gallery | NavigationItem(footer:gallery).label {en,bn} | `/admin/navigation` | footer navigation | Editor/render implemented |
| footer gallery.target | app.js L7 gallery.html | NavigationItem.target {en,bn}; known route validation | `/admin/navigation` | footer link href | Editor/render implemented |
| Navigation ordering/visibility | L6/L7 array order | NavigationItem.sort_order/is_visible | `/admin/navigation` | Header/footer ordering | Editor/render implemented |

## Homepage care-card fields

| Public field / collection member | Source location | Proposed database owner | Future admin screen | Public rendering location | Status |
| --- | --- | --- | --- | --- | --- |
| Card 1 obstetrics.title | app.js L9 cards(); obtitle | CareCard(obstetrics).title | `/admin/care-cards` · dedicated card form | Home .service-card only; options are independent | Editor/render implemented |
| Card 1 obstetrics.description | app.js L9 cards(); obdesc | CareCard(obstetrics).description | `/admin/care-cards` · dedicated card form | Home .service-card only; options are independent | Editor/render implemented |
| Card 1 obstetrics.icon | app.js L9 cards(); 🤰 | CareCard(obstetrics).icon {en,bn} (allowlisted glyph) | `/admin/care-cards` · dedicated card form | Home .service-card only; options are independent | Editor/render implemented |
| Card 1 obstetrics.link label | app.js L9 cards(); learn | CareCard(obstetrics).link_label {en,bn} | `/admin/care-cards` · dedicated card form | Home .service-card only; options are independent | Editor/render implemented |
| Card 1 obstetrics.target | app.js L9 cards(); services.html | CareCard(obstetrics).target {en,bn} | `/admin/care-cards` · dedicated card form | Home .service-card only; options are independent | Editor/render implemented |
| Card 2 gynecology.title | app.js L9 cards(); gytitle | CareCard(gynecology).title | `/admin/care-cards` · dedicated card form | Home .service-card only; options are independent | Editor/render implemented |
| Card 2 gynecology.description | app.js L9 cards(); gydesc | CareCard(gynecology).description | `/admin/care-cards` · dedicated card form | Home .service-card only; options are independent | Editor/render implemented |
| Card 2 gynecology.icon | app.js L9 cards(); ✿ | CareCard(gynecology).icon {en,bn} (allowlisted glyph) | `/admin/care-cards` · dedicated card form | Home .service-card only; options are independent | Editor/render implemented |
| Card 2 gynecology.link label | app.js L9 cards(); learn | CareCard(gynecology).link_label {en,bn} | `/admin/care-cards` · dedicated card form | Home .service-card only; options are independent | Editor/render implemented |
| Card 2 gynecology.target | app.js L9 cards(); services.html | CareCard(gynecology).target {en,bn} | `/admin/care-cards` · dedicated card form | Home .service-card only; options are independent | Editor/render implemented |
| Card 3 fertility.title | app.js L9 cards(); iuititle | CareCard(fertility).title | `/admin/care-cards` · dedicated card form | Home .service-card only; options are independent | Editor/render implemented |
| Card 3 fertility.description | app.js L9 cards(); iuidesc | CareCard(fertility).description | `/admin/care-cards` · dedicated card form | Home .service-card only; options are independent | Editor/render implemented |
| Card 3 fertility.icon | app.js L9 cards(); ♡ | CareCard(fertility).icon {en,bn} (allowlisted glyph) | `/admin/care-cards` · dedicated card form | Home .service-card only; options are independent | Editor/render implemented |
| Card 3 fertility.link label | app.js L9 cards(); learn | CareCard(fertility).link_label {en,bn} | `/admin/care-cards` · dedicated card form | Home .service-card only; options are independent | Editor/render implemented |
| Card 3 fertility.target | app.js L9 cards(); services.html | CareCard(fertility).target {en,bn} | `/admin/care-cards` · dedicated card form | Home .service-card only; options are independent | Editor/render implemented |
| Card 4 diagnostics.title | app.js L9 cards(); diatitle | CareCard(diagnostics).title | `/admin/care-cards` · dedicated card form | Home .service-card only; options are independent | Editor/render implemented |
| Card 4 diagnostics.description | app.js L9 cards(); diadesc | CareCard(diagnostics).description | `/admin/care-cards` · dedicated card form | Home .service-card only; options are independent | Editor/render implemented |
| Card 4 diagnostics.icon | app.js L9 cards(); 🔬 | CareCard(diagnostics).icon {en,bn} (allowlisted glyph) | `/admin/care-cards` · dedicated card form | Home .service-card only; options are independent | Editor/render implemented |
| Card 4 diagnostics.link label | app.js L9 cards(); learn | CareCard(diagnostics).link_label {en,bn} | `/admin/care-cards` · dedicated card form | Home .service-card only; options are independent | Editor/render implemented |
| Card 4 diagnostics.target | app.js L9 cards(); diagnostics.html | CareCard(diagnostics).target {en,bn} | `/admin/care-cards` · dedicated card form | Home .service-card only; options are independent | Editor/render implemented |
| Care-card ordering/visibility/topic code | L9 source order; L22 topic option titles | CareCard.sort_order/is_enabled/key | `/admin/care-cards` · dedicated card form | Home enabled order; stable card key | Editor/render implemented |

## Services-page fields (separate collection)

| Public field / collection member | Source location | Proposed database owner | Future admin screen | Public rendering location | Status |
| --- | --- | --- | --- | --- | --- |
| Service 1.title | app.js L16 serviceDetails.en/bn[0][0] | Service(key=pregnancy-antenatal-care).title {en,bn} | `/admin/services` · dedicated service form | Services .info-box 1 | Editor/render implemented |
| Service 1.description | app.js L16 serviceDetails.en/bn[0][1] | Service(key=pregnancy-antenatal-care).body {en,bn} | `/admin/services` · dedicated service form | Services .info-box 1 | Editor/render implemented |
| Service 2.title | app.js L16 serviceDetails.en/bn[1][0] | Service(key=menstrual-gynecological-problems).title {en,bn} | `/admin/services` · dedicated service form | Services .info-box 2 | Editor/render implemented |
| Service 2.description | app.js L16 serviceDetails.en/bn[1][1] | Service(key=menstrual-gynecological-problems).body {en,bn} | `/admin/services` · dedicated service form | Services .info-box 2 | Editor/render implemented |
| Service 3.title | app.js L16 serviceDetails.en/bn[2][0] | Service(key=infertility-iui).title {en,bn} | `/admin/services` · dedicated service form | Services .info-box 3 | Editor/render implemented |
| Service 3.description | app.js L16 serviceDetails.en/bn[2][1] | Service(key=infertility-iui).body {en,bn} | `/admin/services` · dedicated service form | Services .info-box 3 | Editor/render implemented |
| Service 4.title | app.js L16 serviceDetails.en/bn[3][0] | Service(key=menopause-midlife-health).title {en,bn} | `/admin/services` · dedicated service form | Services .info-box 4 | Editor/render implemented |
| Service 4.description | app.js L16 serviceDetails.en/bn[3][1] | Service(key=menopause-midlife-health).body {en,bn} | `/admin/services` · dedicated service form | Services .info-box 4 | Editor/render implemented |
| Service 5.title | app.js L16 serviceDetails.en/bn[4][0] | Service(key=postnatal-reproductive-health).title {en,bn} | `/admin/services` · dedicated service form | Services .info-box 5 | Editor/render implemented |
| Service 5.description | app.js L16 serviceDetails.en/bn[4][1] | Service(key=postnatal-reproductive-health).body {en,bn} | `/admin/services` · dedicated service form | Services .info-box 5 | Editor/render implemented |
| Service order/visibility and displayed number | L17 services() index 01–05 | Service.sort_order/is_enabled; number derived in Blade | `/admin/services` · Move up/down + enabled control | Services detail block order/number | Editor/render implemented |

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
| Article category/back/read link labels and targets | L10/L21 read/blog; article.html?id=slug; blog.html | ContentField.ui.read; NavigationItem blog.label; route generated from Post.slug/category | `/admin/settings?group=ui`; `/admin/navigation` | Article cards/detail; route shape code-owned | Editor/render implemented |

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
| Clinic display name / CTA, diagnostics, contact eyebrow | L8/L18/L22 DEEPAN IUI LAB | SiteSetting.clinic_name {en,bn} | `/admin/settings?group=clinic` | Three reused eyebrow slots; visit format | Editor/render implemented |
| Official Facebook target | L7/L22 https://www.facebook.com/drshahalamtalukder/ | SiteSetting.facebook_url {en,bn} | `/admin/settings?group=social` | Footer/contact standalone link; never submit action | Editor/render implemented |
| Google Maps target | L22 https://www.google.com/maps/search/?api=1&query=4S+Tower+628+East+Bogura+Road+Barishal | SiteSetting.map_url {en,bn} | `/admin/settings?group=social` | Contact map href | Editor/render implemented |
| Footer social label | L7 Facebook ↗ | ContentField.footer.social_label {en,bn} | `/admin/settings?group=social` | Footer standalone social anchor | Editor/render implemented |
| Copyright format/year/caveat | L7 © current year brand · galleryNote | ContentField.footer.copyright_format {en,bn}; allowed {year},{doctor_name},{stock_notice} | `/admin/settings?group=footer` | Footer-bottom; year server-derived; canonical settings/notice | Editor/render implemented |
| Brand link / mark | L6/L7 index.html; ✳ | ContentField.shared.brand_target {en,bn}; SiteSetting.brand_icon enum | `/admin/settings?group=branding` | Header logo href; header/footer brandmark | Editor/render implemented |
| Home location_eyebrow | L14 BARISHAL | SiteSetting.location_label {en,bn} | `/admin/settings?group=clinic` | Home floating/care/journal/gallery headings | Editor/render implemented |
| Home care_eyebrow | L14 OUR AREAS OF CARE | ContentField.home.care_eyebrow {en,bn} | `/admin/pages/home/edit` | Home floating/care/journal/gallery headings | Editor/render implemented |
| Home journal_eyebrow | L14 THE JOURNAL | ContentField.home.journal_eyebrow {en,bn} | `/admin/pages/home/edit` | Home floating/care/journal/gallery headings | Editor/render implemented |
| Home gallery_eyebrow | L14 VISUAL STORIES | ContentField.home.gallery_eyebrow {en,bn} | `/admin/pages/home/edit` | Home floating/care/journal/gallery headings | Editor/render implemented |
| hero photo slot | L14 home PHOTOS.hero | SiteSetting.portrait_media_asset_id FK | `/admin/settings?group=branding` | Home/about/diagnostics image; shape/class code-owned | Editor/render implemented |
| hero image alt | L14 home Illustrative healthcare portrait — not the actual doctor | ContentField(home.hero_alt).value {en,bn} | `/admin/settings?group=branding` | Corresponding img alt; original caveat retained | Editor/render implemented |
| doctor photo slot | L14 home PHOTOS.consult | ContentField(home.doctor_image).media_asset_id | `/admin/pages/home/edit` | Home/about/diagnostics image; shape/class code-owned | Editor/render implemented |
| doctor image alt | L14 home Healthcare consultation illustration | ContentField(home.doctor_alt).value {en,bn} | `/admin/pages/home/edit` | Corresponding img alt; original caveat retained | Editor/render implemented |
| about photo slot | L15 about PHOTOS.care | ContentField.about.image media_asset_id FK | `/admin/pages/about/edit` | Home/about/diagnostics image; shape/class code-owned | Editor/render implemented |
| about image alt | L15 about Illustrative consultation photo | ContentField.about.image_alt {en,bn} | `/admin/pages/about/edit` | Corresponding img alt; original caveat retained | Editor/render implemented |
| diagnostics photo slot | L18 diagnostics PHOTOS.lab | ContentField.diagnostics.image media_asset_id FK | `/admin/pages/diagnostics/edit` | Home/about/diagnostics image; shape/class code-owned | Editor/render implemented |
| diagnostics image alt | L18 diagnostics Illustrative lab stock photograph | ContentField.diagnostics.image_alt {en,bn} | `/admin/pages/diagnostics/edit` | Corresponding img alt; original caveat retained | Editor/render implemented |
| UI switch_to_bn | L6 বাংলা | ContentField.ui.switch_to_bn {en,bn} | `/admin/settings?group=ui` | Language/menu/dialog accessibility | Editor/render implemented |
| UI switch_to_en | L6 English | ContentField.ui.switch_to_en {en,bn} | `/admin/settings?group=ui` | Language/menu/dialog accessibility | Editor/render implemented |
| UI open_navigation | L6 Open navigation | ContentField.ui.open_navigation {en,bn} | `/admin/settings?group=ui` | Language/menu/dialog accessibility | Editor/render implemented |
| UI gallery_preview | L23 Gallery image preview | ContentField.ui.gallery_preview {en,bn} | `/admin/settings?group=ui` | Language/menu/dialog accessibility | Editor/render implemented |
| UI close_lightbox | L23 Close | ContentField.ui.close_lightbox {en,bn} | `/admin/settings?group=ui` | Language/menu/dialog accessibility | Editor/render implemented |
| Page title format | L23 localized page label \| brand | ContentField.meta.title_format {en,bn}; allowed page_label/doctor_name | `/admin/settings?group=information` | Public title; actual post title intentional improvement | Editor/render implemented |
| about.meta_title | frontend/about.html head; L23 dynamic title | Page(about).meta_title {en,bn} | `/admin/pages/about/edit` | Page head; eight types; no stock source meta fallback | Editor/render implemented |
| about.meta_description | frontend/about.html head; L23 dynamic title | Page(about).meta_description {en,bn} | `/admin/pages/about/edit` | Page head; eight types; no stock source meta fallback | Editor/render implemented |
| article.meta_title | frontend/article.html head; L23 dynamic title | Page(article).meta_title {en,bn} | `/admin/pages/article/edit` | Page head; eight types; no stock source meta fallback | Editor/render implemented |
| article.meta_description | frontend/article.html head; L23 dynamic title | Page(article).meta_description {en,bn} | `/admin/pages/article/edit` | Page head; eight types; no stock source meta fallback | Editor/render implemented |
| blog.meta_title | frontend/blog.html head; L23 dynamic title | Page(blog).meta_title {en,bn} | `/admin/pages/blog/edit` | Page head; eight types; no stock source meta fallback | Editor/render implemented |
| blog.meta_description | frontend/blog.html head; L23 dynamic title | Page(blog).meta_description {en,bn} | `/admin/pages/blog/edit` | Page head; eight types; no stock source meta fallback | Editor/render implemented |
| contact.meta_title | frontend/contact.html head; L23 dynamic title | Page(contact).meta_title {en,bn} | `/admin/pages/contact/edit` | Page head; eight types; no stock source meta fallback | Editor/render implemented |
| contact.meta_description | frontend/contact.html head; L23 dynamic title | Page(contact).meta_description {en,bn} | `/admin/pages/contact/edit` | Page head; eight types; no stock source meta fallback | Editor/render implemented |
| diagnostics.meta_title | frontend/diagnostics.html head; L23 dynamic title | Page(diagnostics).meta_title {en,bn} | `/admin/pages/diagnostics/edit` | Page head; eight types; no stock source meta fallback | Editor/render implemented |
| diagnostics.meta_description | frontend/diagnostics.html head; L23 dynamic title | Page(diagnostics).meta_description {en,bn} | `/admin/pages/diagnostics/edit` | Page head; eight types; no stock source meta fallback | Editor/render implemented |
| gallery.meta_title | frontend/gallery.html head; L23 dynamic title | Page(gallery).meta_title {en,bn} | `/admin/pages/gallery/edit` | Page head; eight types; no stock source meta fallback | Editor/render implemented |
| gallery.meta_description | frontend/gallery.html head; L23 dynamic title | Page(gallery).meta_description {en,bn} | `/admin/pages/gallery/edit` | Page head; eight types; no stock source meta fallback | Editor/render implemented |
| home.meta_title | frontend/index.html head; L23 dynamic title | Page(home).meta_title {en,bn} | `/admin/pages/home/edit` | Page head; eight types; no stock source meta fallback | Editor/render implemented |
| home.meta_description | frontend/index.html head; L23 dynamic title | Page(home).meta_description {en,bn} | `/admin/pages/home/edit` | Page head; eight types; no stock source meta fallback | Editor/render implemented |
| services.meta_title | frontend/services.html head; L23 dynamic title | Page(services).meta_title {en,bn} | `/admin/pages/services/edit` | Page head; eight types; no stock source meta fallback | Editor/render implemented |
| services.meta_description | frontend/services.html head; L23 dynamic title | Page(services).meta_description {en,bn} | `/admin/pages/services/edit` | Page head; eight types; no stock source meta fallback | Editor/render implemented |
| Theme color/fonts/design classes | All HTML #205d54; style.css L1/L2 | Code-owned fixed design, not content JSON | None | Public layout CSS/font loading | Code-owned; preserved |
| Shared section visibility and home collection limit/order | L14 sections; L10/L11 limits 3; L8 CTA | PageSection.is_visible only for registry-approved optional sections; Post/GalleryImage sort_order; ContentField(home.article_count/home.gallery_count).value | `/admin/pages/home/edit`; `/admin/pages/{key}/edit` | Fixed sections; first 3 items; required caveats cannot be hidden | Section/count editors implemented; collection order editor pending |
| home.primary_target | L14 contact.html | ContentField.home.primary_target {en,bn}; known route allowlist | `/admin/pages/home/edit` | Home appointment CTA | Editor/render implemented |
| home.secondary_target | L14 services.html | ContentField.home.secondary_target {en,bn}; known route allowlist | `/admin/pages/home/edit` | Home explore services | Editor/render implemented |
| home.doctor_target | L14 about.html | ContentField.home.doctor_target {en,bn}; known route allowlist | `/admin/pages/home/edit` | Home doctor details | Editor/render implemented |
| home.journal_target | L14 blog.html | ContentField.home.journal_target {en,bn}; known route allowlist | `/admin/pages/home/edit` | Home journal details | Editor/render implemented |
| home.gallery_target | L14 gallery.html | ContentField.home.gallery_target {en,bn}; known route allowlist | `/admin/pages/home/edit` | Home gallery details | Editor/render implemented |
| cta.target | L8 contact.html | SiteSetting.cta_target {en,bn}; known route allowlist | `/admin/settings?group=cta` | Shared CTA | Editor/render implemented |
| diagnostics.contact_target | L18 contact.html | ContentField.diagnostics.contact_target {en,bn}; known route allowlist | `/admin/pages/diagnostics/edit` | Diagnostic contact link | Editor/render implemented |
| Four independent consultation options | L22 obtitle/gytitle/iuititle/diatitle | ConsultationOption.label {en,bn}; stable key values | `/admin/consultation-options` · label/key-at-create, move/active controls | Appointment select only; general stores no topic | Editor/render implemented |
| Original clipboard text/success (historical) | L23 bilingual header; Name/Phone/Topic/Message; Copied! / কপি হয়েছে! | Audit provenance only; replaced, no production runtime field | None | Removed from new submission behavior | Historical provenance only |
| New UI/form mode_label | Owner contract / architecture addition; no original string | ContentField(contact.mode_label).value {en,bn} | `/admin/settings?group=forms` | Mode selector label | Editor/render implemented |
| New UI/form mode_appointment | Owner contract / architecture addition; no original string | ContentField(contact.mode_appointment).value {en,bn} | `/admin/settings?group=forms` | Appointment selector option | Editor/render implemented |
| New UI/form mode_general | Owner contract / architecture addition; no original string | ContentField(contact.mode_general).value {en,bn} | `/admin/settings?group=forms` | General contact option | Editor/render implemented |
| New UI/form submit_success | Owner contract / architecture addition; no original string | ContentField(contact.success_appointment / contact.success_contact).value {en,bn} | `/admin/settings?group=forms` | Saved-success flash/status | Editor/render implemented |
| New UI/form not_confirmed_notice | Owner contract / architecture addition; no original string | ContentField(contact.privacy).value {en,bn} | `/admin/settings?group=forms` | Always-present appointment-request caveat | Editor/render implemented |
| New UI/form appointment_guidance | Owner contract / architecture addition; no original string | ContentField(contact.privacy).value {en,bn} | `/admin/settings?group=forms` | Appointment form helper | Editor/render implemented |
| New UI/form general_guidance | Owner contract / architecture addition; no original string | ContentField(contact.privacy).value {en,bn} | `/admin/settings?group=forms` | General message form helper | Editor/render implemented |
| New UI/form mode_apply | Owner contract / architecture addition; no original string | ContentField(contact.mode_apply).value {en,bn} | `/admin/settings?group=forms` | No-JS GET mode switch action | Editor/render implemented |
| New UI/form validation_required | Owner contract / architecture addition; no original string | ContentField(contact.validation_required).value {en,bn} | `/admin/settings?group=forms` | Required field errors | Editor/render implemented |
| New UI/form validation_invalid | Owner contract / architecture addition; no original string | ContentField(contact.validation_invalid).value {en,bn} | `/admin/settings?group=forms` | Invalid input errors | Editor/render implemented |
| New UI/form validation_max | Owner contract / architecture addition; no original string | ContentField(contact.validation_max).value {en,bn} | `/admin/settings?group=forms` | Length validation errors | Editor/render implemented |
| New UI/form save_failure | Owner contract / architecture addition; no original string | ContentField(contact.save_failure).value {en,bn} | `/admin/settings?group=forms` | No-success failure message | Editor implemented; request wiring Step 11 |
| New UI/form submit_pending | Owner contract / architecture addition; no original string | ContentField(contact.submit_pending).value {en,bn} | `/admin/settings?group=forms` | Optional disabled button status | Editor implemented; request wiring Step 11 |
| New UI/form empty_filter_results | Owner contract / architecture addition; no original string | ContentField(ui.empty_filter_results).value {en,bn} | `/admin/settings?group=ui` | Blog/gallery empty state | Editor/render implemented |
| New UI/form menu_close | Owner contract / architecture addition; no original string | ContentField(ui.menu_close).value {en,bn} | `/admin/settings?group=ui` | Navigation accessibility | Editor/render implemented |
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
| Four consultation labels/keys | app.js contact() obtitle/gytitle/iuititle/diatitle | ConsultationOption.label/key | `/admin/consultation-options` · label/key-at-create, move/active controls | Appointment-only select | Editor/render implemented |
| Consultation order/active flag | Original option order | ConsultationOption.sort_order/is_active | `/admin/consultation-options` · label/key-at-create, move/active controls | Appointment options | Editor/render implemented |
| Appointment submit label | Owner Step 3 exact copy | ContentField(contact.prepare).value | Contact copy | Appointment submit | Imported; admin pending |
| Contact submit label | Owner Step 3 exact copy | ContentField(contact.submit_contact).value | Contact copy | General submit | Imported; admin pending |
| Two success messages | Owner Step 3 exact copy | ContentField(contact.success_appointment/success_contact).value | Contact copy | Success flash after save | Imported; admin pending |
| Default locale | Owner contract | SiteSetting.default_locale | Shared settings | Locale middleware | Imported; admin pending |
| Homepage article/gallery counts | articleCards(3)/galleryGrid(3) | ContentField(home.article_count/home.gallery_count).value | Home page | DB preview limits | Imported; admin pending |
| Fixed sections visibility and position | Original page renderers | PageSection.key/page_id/is_visible/sort_order | Pages | Home optional sections / shared per-page CTA | 20 imported, all visible; positions code-owned |
| Category applicability | filters(article/gallery) | Category.for_blog/for_gallery | Categories | Filter controls | Imported; admin pending |
| Post homepage flag/publication timestamp | First-three source previews; owner initialization rule | Post.on_homepage/published_at | Posts | Published previews and detail scope; timestamp never badge | Imported; admin pending |
| Gallery homepage flag | First-three source previews | GalleryImage.on_homepage | Gallery | Homepage gallery preview | Imported; admin pending |
| Media provenance/stock caveat | Original URLs + stock notice | MediaAsset.provenance_note/stock_note | Media library details | Image source/stock caveat; existing public notices remain code-defined slots | Imported; bilingual media editor implemented |
| Media path/disk/MIME/size/dimensions/filename | Real validated uploads; unknown for external initial URLs | MediaAsset.path/disk/mime_type/size/width/height/original_filename | Media library read-only technical details | Shared URL resolver; null technical metadata for initial external records | Upload/library implemented; public-media disk, random raster paths; no storage link |
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

## Step 6 media reconciliation (supersedes historical pending-media statuses)

All original public image references/alt/captions and the 81-key mapping retain their owners and initialized values. No original image download or replacement occurred: development contains **9 external records / 0 local records**. The nullable caption migration adds no demo text, and the initialization snapshot remains unchanged. Older planned media rows are reconciled by the actual implementation below.

| Public/admin field or collection | Source location | Actual database/code owner | Admin screen | Public rendering / behavior | Status |
| --- | --- | --- | --- | --- | --- |
| Media title `{en,bn}` | Initial source photo key/descriptive label; owner input for uploads | MediaAsset.title | Media upload/details; registry English required | Library/picker name, escaped text | Bilingual editor implemented; preserves omitted Bangla |
| Default image alt `{en,bn}` | Initial source image defaults + existing source translations | MediaAsset.alt_text | Media upload/details | Admin preview/picker default; slot-specific public alt remains its assigned owner | Bilingual editor implemented |
| Default media caption `{en,bn}` | New optional library field, no invented original caption | MediaAsset.caption nullable JSON | Media upload/details | Available default for later content screens; does not override GalleryImage.caption | Migration/editor implemented; original rows safely null |
| Provenance and stock note `{en,bn}` | Original fixed source URLs/caveats, actual owner-supplied provenance | MediaAsset.provenance_note/stock_note | Media upload/details | Administrative source/authenticity documentation; public stock/medical notices remain intact | Bilingual editor implemented; no fabricated permissions/identity |
| Original external URL | Nine audited frontend app.js PHOTOS URLs, unchanged seeded records | MediaAsset.source_url, read-only editor field | Media preview/copy URL | Same legacy HTTP(S) URL in public/admin/lightbox until explicit replacement | Retained; 9 external / 0 local initial records; remote fragility documented |
| Local file/disk/path/MIME/size/dimensions/filename | Owner upload; extension/bytes/signature/dimension validation | MediaAsset technical fields; immutable request input; config/media_fields.php validation | Upload + read-only details | public-media disk URL, strict random images/YYYY/MM path | Implemented; JPEG/PNG/WebP ≤5 MiB, ≤6000 edge / 24M pixels |
| Media root/public URL | Deployment configuration, never visitor/admin path input | config/filesystems.php public-media; PUBLIC_MEDIA_ROOT/PUBLIC_MEDIA_URL | Hosting setup documentation | Default public/uploads + APP_URL/uploads; split public_html override | Implemented; no mandatory storage:link |
| Media library pagination/search/source filter | Database asset collection | MediaAsset; validated q/kind/page/picker GET | /admin/media | Private Tailwind pages; no inquiry creation or public CSS reset | Implemented, 24 images/page |
| Selected/replacement image IDs | Existing database asset chosen by owner | ContentField.media_asset_id, Post.media_asset_id, GalleryImage.media_asset_id, SiteSetting.portrait_media_asset_id | Shared picker; explicit media reference replacement | All public slots/admin/lightbox reuse MediaAsset::url; existing ratios/styles preserved | Shared picker + atomic replace-all implemented; later individual content editors pending |
| Usage/deletion consent | Four relational FK owners, including hidden/draft entries | MediaAsset usage relations + protected controller/MediaManager | Media details/confirmation | In-use deletion blocked; reference removal never deletes shared bytes | Implemented; unused local deletion stages/restores/cleans synchronously |
| Apache execution/MIME/listing policy | Code-owned upload-directory policy | public/uploads/.htaccess (tracked) | Hosting setup, not arbitrary CMS text | Static generated raster names only; no execution/listing; nosniff where supported | 12 actual local Apache checks passed; cPanel configuration still to verify |

Sidebar now activates Dashboard, Media and Profile only. Media picker/delete components are functional for this module; historical Step 5 placeholder/pending statements above describe that earlier step. See MEDIA_MANAGER and IMPLEMENTATION_STATUS for actual failure handling, test counts, external font/image dependencies and deployment requirements.

## Step 7 exact editor/render reconciliation

This section supersedes earlier editor-pending rows for SiteSetting, ContentField, Page, PageSection and NavigationItem. Collection/inquiry CRUD remains pending. The complete registry coverage test proves that each of the **88 ContentField keys** and **23 editable SiteSetting fields** belongs to exactly one editor. Keys, foreign-owner identities and layout order are not editable. Source lineage remains in the original 347 rows; these rows name actual controls/routes and public templates. Metadata columns added by Step 7 have no invented source values. See CMS_EDITORS for saving/fallback and manual review.

### All shared settings

| Database field | Exact admin route/control | Public render locations | Status |
| --- | --- | --- | --- |
| `SiteSetting.doctor_name` | `/admin/settings?group=doctor`, `settings[doctor_name]` | `resources/views/public/pages/about.blade.php`; `resources/views/public/parts/footer.blade.php`; `resources/views/public/parts/navigation.blade.php`; `app/Services/WebsiteContent.php` | Implemented; shared source |
| `SiteSetting.doctor_biography` | `/admin/settings?group=doctor`, `settings[doctor_biography]` | `resources/views/public/pages/about.blade.php`; `resources/views/public/pages/home.blade.php`; `app/Http/Controllers/PublicPageController.php` | Implemented; shared source |
| `SiteSetting.credential` | `/admin/settings?group=doctor`, `settings[credential]` | `resources/views/public/pages/about.blade.php`; `resources/views/public/pages/home.blade.php`; `app/Services/WebsiteContent.php` | Implemented; shared source |
| `SiteSetting.department` | `/admin/settings?group=doctor`, `settings[department]` | `resources/views/public/pages/about.blade.php`; `resources/views/public/pages/home.blade.php`; `app/Services/WebsiteContent.php` | Implemented; shared source |
| `SiteSetting.institution` | `/admin/settings?group=doctor`, `settings[institution]` | `resources/views/public/pages/about.blade.php`; `resources/views/public/pages/home.blade.php`; `app/Services/WebsiteContent.php` | Implemented; shared source |
| `SiteSetting.clinic_name` | `/admin/settings?group=clinic`, `settings[clinic_name]` | `resources/views/public/pages/contact.blade.php`; `resources/views/public/pages/diagnostics.blade.php`; `resources/views/public/parts/cta.blade.php`; `app/Services/WebsiteContent.php` | Implemented; shared source |
| `SiteSetting.address` | `/admin/settings?group=clinic`, `settings[address]` | `resources/views/public/pages/contact.blade.php`; `resources/views/public/parts/footer.blade.php`; `resources/views/public/parts/topbar.blade.php`; `app/Services/WebsiteContent.php` | Implemented; shared source |
| `SiteSetting.consultation_hours` | `/admin/settings?group=clinic`, `settings[consultation_hours]` | `resources/views/public/pages/about.blade.php`; `resources/views/public/pages/contact.blade.php`; `resources/views/public/parts/footer.blade.php`; `resources/views/public/parts/topbar.blade.php`; `app/Services/WebsiteContent.php` | Implemented; shared source |
| `SiteSetting.friday_note` | `/admin/settings?group=clinic`, `settings[friday_note]` | `resources/views/public/pages/about.blade.php`; `resources/views/public/pages/contact.blade.php`; `resources/views/public/parts/footer.blade.php`; `resources/views/public/parts/topbar.blade.php`; `app/Services/WebsiteContent.php` | Implemented; shared source |
| `SiteSetting.location_label` | `/admin/settings?group=clinic`, `settings[location_label]` | `resources/views/public/pages/home.blade.php` | Implemented; shared source |
| `SiteSetting.subtitle` | `/admin/settings?group=branding`, `settings[subtitle]` | `resources/views/public/parts/navigation.blade.php` | Implemented; shared source |
| `SiteSetting.brand_icon` | `/admin/settings?group=branding`, `settings[brand_icon]` | `public.parts.navigation` + `public.parts.footer` brandmarks | Implemented; shared source |
| `SiteSetting.portrait_media_asset_id` | `/admin/settings?group=branding`, `settings[portrait_media_asset_id]` | Home `.hero-photo` via SiteSetting.portrait → MediaAsset::url; page OG fallback planned Step 13 | Implemented; shared source |
| `SiteSetting.facebook_url` | `/admin/settings?group=social`, `settings[facebook_url]` | `resources/views/public/pages/contact.blade.php`; `resources/views/public/parts/footer.blade.php` | Implemented; shared source |
| `SiteSetting.map_url` | `/admin/settings?group=social`, `settings[map_url]` | `resources/views/public/pages/contact.blade.php` | Implemented; shared source |
| `SiteSetting.footer_description` | `/admin/settings?group=footer`, `settings[footer_description]` | `resources/views/public/parts/footer.blade.php` | Implemented; shared source |
| `SiteSetting.cta_title` | `/admin/settings?group=cta`, `settings[cta_title]` | `resources/views/public/parts/cta.blade.php` | Implemented; shared source |
| `SiteSetting.cta_description` | `/admin/settings?group=cta`, `settings[cta_description]` | `resources/views/public/parts/cta.blade.php` | Implemented; shared source |
| `SiteSetting.cta_label` | `/admin/settings?group=cta`, `settings[cta_label]` | `resources/views/public/parts/cta.blade.php` | Implemented; shared source |
| `SiteSetting.cta_target` | `/admin/settings?group=cta`, `settings[cta_target]` | `resources/views/public/parts/cta.blade.php` | Implemented; shared source |
| `SiteSetting.medical_disclaimer` | `/admin/settings?group=notices`, `settings[medical_disclaimer]` | `resources/views/public/parts/notice.blade.php` | Implemented; shared source |
| `SiteSetting.stock_notice` | `/admin/settings?group=notices`, `settings[stock_notice]` | `resources/views/public/pages/about.blade.php`; `resources/views/public/pages/gallery.blade.php`; `app/Services/WebsiteContent.php` | Implemented; shared source |
| `SiteSetting.default_locale` | `/admin/settings?group=locale`, `settings[default_locale]` | `SetPublicLocale` after validated URL/session preference | Implemented; shared source |

### Every named content field

`{id}` is the actual existing ContentField database ID, scoped by its registered screen. Localized fields use `[en]`/`[bn]` beneath `value`; scalar counts use a number input; images use `media_asset_id`. Destination controls normalize to localized named route keys or validated URLs.

| Registry key / database field | Source | Exact admin screen/control | Type | Actual public render location | Status |
| --- | --- | --- | --- | --- | --- |
| `home.appointment` → `ContentField.value` | frontend/assets/app.js L2 content.en/bn.appointment | `/admin/pages/home/edit` · `fields[{id}][value][en/bn]` | text | `resources/views/public/pages/home.blade.php` | Editor/render implemented |
| `home.eyebrow` → `ContentField.value` | frontend/assets/app.js L2 content.en/bn.eyebrow | `/admin/pages/home/edit` · `fields[{id}][value][en/bn]` | text | `resources/views/public/pages/home.blade.php` | Editor/render implemented |
| `home.herotitle` → `ContentField.value` | frontend/assets/app.js L2 content.en/bn.herotitle | `/admin/pages/home/edit` · `fields[{id}][value][en/bn]` | text | `resources/views/public/pages/home.blade.php` | Editor/render implemented |
| `home.herodesc` → `ContentField.value` | frontend/assets/app.js L2 content.en/bn.herodesc | `/admin/pages/home/edit` · `fields[{id}][value][en/bn]` | format | `resources/views/public/pages/home.blade.php` | Editor/render implemented |
| `home.visit` → `ContentField.value` | frontend/assets/app.js L2 content.en/bn.visit | `/admin/pages/home/edit` · `fields[{id}][value][en/bn]` | format | `resources/views/public/pages/home.blade.php` | Editor/render implemented |
| `home.servicesTitle` → `ContentField.value` | frontend/assets/app.js L2 content.en/bn.servicesTitle | `/admin/pages/home/edit` · `fields[{id}][value][en/bn]` | text | `resources/views/public/pages/home.blade.php` | Editor/render implemented |
| `home.servicesDesc` → `ContentField.value` | frontend/assets/app.js L2 content.en/bn.servicesDesc | `/admin/pages/home/edit` · `fields[{id}][value][en/bn]` | textarea | `resources/views/public/pages/home.blade.php` | Editor/render implemented |
| `home.doctorEyebrow` → `ContentField.value` | frontend/assets/app.js L2 content.en/bn.doctorEyebrow | `/admin/pages/home/edit` · `fields[{id}][value][en/bn]` | text | `resources/views/public/pages/home.blade.php` | Editor/render implemented |
| `home.doctorTitle` → `ContentField.value` | frontend/assets/app.js L2 content.en/bn.doctorTitle | `/admin/pages/home/edit` · `fields[{id}][value][en/bn]` | text | `resources/views/public/pages/home.blade.php` | Editor/render implemented |
| `home.blogtitle` → `ContentField.value` | frontend/assets/app.js L2 content.en/bn.blogtitle | `/admin/pages/home/edit` · `fields[{id}][value][en/bn]` | text | `resources/views/public/pages/home.blade.php` | Editor/render implemented |
| `ui.explore` → `ContentField.value` | frontend/assets/app.js L2 content.en/bn.explore | `/admin/settings?group=ui` · `fields[{id}][value][en/bn]` | text | `resources/views/public/pages/home.blade.php`; `resources/views/public/parts/footer.blade.php` | Editor/render implemented |
| `ui.read` → `ContentField.value` | frontend/assets/app.js L2 content.en/bn.read | `/admin/settings?group=ui` · `fields[{id}][value][en/bn]` | text | `resources/views/public/parts/article-card.blade.php` | Editor/render implemented |
| `ui.readmore` → `ContentField.value` | frontend/assets/app.js L2 content.en/bn.readmore | `/admin/settings?group=ui` · `fields[{id}][value][en/bn]` | text | `resources/views/public/pages/home.blade.php` | Editor/render implemented |
| `ui.filter_all` → `ContentField.value` | frontend/assets/app.js L2 content.en/bn.all | `/admin/settings?group=ui` · `fields[{id}][value][en/bn]` | text | `resources/views/public/parts/filters.blade.php` | Editor/render implemented |
| `shared.qualification` → `ContentField.value` | frontend/assets/app.js L2 content.en/bn.qualification | `/admin/settings?group=information` · `fields[{id}][value][en/bn]` | text | `resources/views/public/pages/about.blade.php` | Editor/render implemented |
| `shared.hoursTitle` → `ContentField.value` | frontend/assets/app.js L2 content.en/bn.hoursTitle | `/admin/settings?group=information` · `fields[{id}][value][en/bn]` | text | `resources/views/public/pages/about.blade.php` | Editor/render implemented |
| `shared.visitTitle` → `ContentField.value` | frontend/assets/app.js L2 content.en/bn.visitTitle | `/admin/settings?group=information` · `fields[{id}][value][en/bn]` | text | `resources/views/public/pages/about.blade.php`; `resources/views/public/pages/diagnostics.blade.php` | Editor/render implemented |
| `shared.profession` → `ContentField.value` | frontend/assets/app.js L2 content.en/bn.profession | `/admin/settings?group=information` · `fields[{id}][value][en/bn]` | format | `resources/views/public/pages/home.blade.php` | Editor/render implemented |
| `shared.visitText` → `ContentField.value` | frontend/assets/app.js L2 content.en/bn.visitText | `/admin/settings?group=information` · `fields[{id}][value][en/bn]` | format | `resources/views/public/pages/about.blade.php`; `resources/views/public/pages/diagnostics.blade.php`; `resources/views/public/pages/home.blade.php` | Editor/render implemented |
| `shared.gallerytitle` → `ContentField.value` | frontend/assets/app.js L2 content.en/bn.gallerytitle | `/admin/settings?group=information` · `fields[{id}][value][en/bn]` | text | `resources/views/public/pages/gallery.blade.php`; `resources/views/public/pages/home.blade.php` | Editor/render implemented |
| `shared.gallerydesc` → `ContentField.value` | frontend/assets/app.js L2 content.en/bn.gallerydesc | `/admin/settings?group=information` · `fields[{id}][value][en/bn]` | textarea | `resources/views/public/pages/gallery.blade.php`; `resources/views/public/pages/home.blade.php` | Editor/render implemented |
| `blog.blogheading` → `ContentField.value` | frontend/assets/app.js L2 content.en/bn.blogheading | `/admin/pages/blog/edit` · `fields[{id}][value][en/bn]` | text | `resources/views/public/pages/blog.blade.php` | Editor/render implemented |
| `blog.blogintro` → `ContentField.value` | frontend/assets/app.js L2 content.en/bn.blogintro | `/admin/pages/blog/edit` · `fields[{id}][value][en/bn]` | textarea | `resources/views/public/pages/blog.blade.php` | Editor/render implemented |
| `blog.tagtitle` → `ContentField.value` | frontend/assets/app.js L2 content.en/bn.tagtitle | `/admin/pages/blog/edit` · `fields[{id}][value][en/bn]` | text | `resources/views/public/pages/blog.blade.php` | Editor/render implemented |
| `blog.blogdesc` → `ContentField.value` | frontend/assets/app.js L2 content.en/bn.blogdesc | `/admin/pages/blog/edit` · `fields[{id}][value][en/bn]` | textarea | Original unused key retained; no public rendering | Editor implemented; unused source key preserved |
| `contact.fb` → `ContentField.value` | frontend/assets/app.js L2 content.en/bn.fb | `/admin/settings?group=social` · `fields[{id}][value][en/bn]` | text | `resources/views/public/pages/contact.blade.php` | Editor/render implemented |
| `contact.contacttitle` → `ContentField.value` | frontend/assets/app.js L2 content.en/bn.contacttitle | `/admin/pages/contact/edit` · `fields[{id}][value][en/bn]` | text | `resources/views/public/pages/contact.blade.php` | Editor/render implemented |
| `contact.contactdesc` → `ContentField.value` | frontend/assets/app.js L2 content.en/bn.contactdesc | `/admin/pages/contact/edit` · `fields[{id}][value][en/bn]` | textarea | `resources/views/public/pages/contact.blade.php` | Editor/render implemented |
| `contact.formtitle` → `ContentField.value` | frontend/assets/app.js L2 content.en/bn.formtitle | `/admin/settings?group=forms` · `fields[{id}][value][en/bn]` | text | `resources/views/public/pages/contact.blade.php` | Editor/render implemented |
| `contact.name` → `ContentField.value` | frontend/assets/app.js L2 content.en/bn.name | `/admin/settings?group=forms` · `fields[{id}][value][en/bn]` | text | `resources/views/public/pages/contact.blade.php`; `app/Http/Controllers/InquiryController.php` | Editor/render implemented |
| `contact.phone` → `ContentField.value` | frontend/assets/app.js L2 content.en/bn.phone | `/admin/settings?group=forms` · `fields[{id}][value][en/bn]` | text | `resources/views/public/pages/contact.blade.php`; `app/Http/Controllers/InquiryController.php` | Editor/render implemented |
| `contact.topic` → `ContentField.value` | frontend/assets/app.js L2 content.en/bn.topic | `/admin/settings?group=forms` · `fields[{id}][value][en/bn]` | text | `resources/views/public/pages/contact.blade.php`; `app/Http/Controllers/InquiryController.php` | Editor/render implemented |
| `contact.message` → `ContentField.value` | frontend/assets/app.js L2 content.en/bn.message | `/admin/settings?group=forms` · `fields[{id}][value][en/bn]` | text | `resources/views/public/pages/contact.blade.php`; `app/Http/Controllers/InquiryController.php` | Editor/render implemented |
| `contact.prepare` → `ContentField.value` | frontend/assets/app.js L2 content.en/bn.prepare | `/admin/settings?group=forms` · `fields[{id}][value][en/bn]` | text | `resources/views/public/pages/contact.blade.php` | Editor/render implemented |
| `contact.privacy` → `ContentField.value` | frontend/assets/app.js L2 content.en/bn.privacy | `/admin/settings?group=forms` · `fields[{id}][value][en/bn]` | textarea | `resources/views/public/pages/contact.blade.php` | Editor/render implemented |
| `contact.addressLabel` → `ContentField.value` | frontend/assets/app.js L2 content.en/bn.addressLabel | `/admin/pages/contact/edit` · `fields[{id}][value][en/bn]` | text | `resources/views/public/pages/contact.blade.php` | Editor/render implemented |
| `contact.hoursLabel` → `ContentField.value` | frontend/assets/app.js L2 content.en/bn.hoursLabel | `/admin/pages/contact/edit` · `fields[{id}][value][en/bn]` | text | `resources/views/public/pages/contact.blade.php` | Editor/render implemented |
| `contact.openmap` → `ContentField.value` | frontend/assets/app.js L2 content.en/bn.openmap | `/admin/settings?group=social` · `fields[{id}][value][en/bn]` | text | `resources/views/public/pages/contact.blade.php` | Editor/render implemented |
| `about.abouttitle` → `ContentField.value` | frontend/assets/app.js L2 content.en/bn.abouttitle | `/admin/pages/about/edit` · `fields[{id}][value][en/bn]` | text | `resources/views/public/pages/about.blade.php` | Editor/render implemented |
| `about.aboutdesc` → `ContentField.value` | frontend/assets/app.js L2 content.en/bn.aboutdesc | `/admin/pages/about/edit` · `fields[{id}][value][en/bn]` | textarea | `resources/views/public/pages/about.blade.php` | Editor/render implemented |
| `diagnostics.diagtitle` → `ContentField.value` | frontend/assets/app.js L2 content.en/bn.diagtitle | `/admin/pages/diagnostics/edit` · `fields[{id}][value][en/bn]` | format | `resources/views/public/pages/diagnostics.blade.php` | Editor/render implemented |
| `diagnostics.diagdesc` → `ContentField.value` | frontend/assets/app.js L2 content.en/bn.diagdesc | `/admin/pages/diagnostics/edit` · `fields[{id}][value][en/bn]` | textarea | `resources/views/public/pages/diagnostics.blade.php` | Editor/render implemented |
| `diagnostics.diagnotice` → `ContentField.value` | frontend/assets/app.js L2 content.en/bn.diagnotice | `/admin/pages/diagnostics/edit` · `fields[{id}][value][en/bn]` | textarea | `resources/views/public/pages/diagnostics.blade.php` | Editor/render implemented |
| `diagnostics.diagtags` → `ContentField.value` | frontend/assets/app.js L2 content.en/bn.diagtags | `/admin/pages/diagnostics/edit` · `fields[{id}][value][en/bn]` | text | `resources/views/public/pages/diagnostics.blade.php` | Editor/render implemented |
| `diagnostics.diagtagsdesc` → `ContentField.value` | frontend/assets/app.js L2 content.en/bn.diagtagsdesc | `/admin/pages/diagnostics/edit` · `fields[{id}][value][en/bn]` | textarea | `resources/views/public/pages/diagnostics.blade.php` | Editor/render implemented |
| `services.servicePageTitle` → `ContentField.value` | frontend/assets/app.js L2 content.en/bn.servicePageTitle | `/admin/pages/services/edit` · `fields[{id}][value][en/bn]` | text | `resources/views/public/pages/services.blade.php` | Editor/render implemented |
| `services.servicePageDesc` → `ContentField.value` | frontend/assets/app.js L2 content.en/bn.servicePageDesc | `/admin/pages/services/edit` · `fields[{id}][value][en/bn]` | textarea | `resources/views/public/pages/services.blade.php` | Editor/render implemented |
| `contact.mode_label` → `ContentField.value` | Owner contract / architecture addition; no original string | `/admin/settings?group=forms` · `fields[{id}][value][en/bn]` | text | `resources/views/public/pages/contact.blade.php`; `app/Http/Controllers/InquiryController.php` | Editor/render implemented |
| `contact.mode_appointment` → `ContentField.value` | Owner contract / architecture addition; no original string | `/admin/settings?group=forms` · `fields[{id}][value][en/bn]` | text | `resources/views/public/pages/contact.blade.php` | Editor/render implemented |
| `contact.mode_general` → `ContentField.value` | Owner contract / architecture addition; no original string | `/admin/settings?group=forms` · `fields[{id}][value][en/bn]` | text | `resources/views/public/pages/contact.blade.php` | Editor/render implemented |
| `contact.submit_contact` → `ContentField.value` | Owner Step 3 exact copy | `/admin/settings?group=forms` · `fields[{id}][value][en/bn]` | text | `resources/views/public/pages/contact.blade.php` | Editor/render implemented |
| `contact.success_appointment` → `ContentField.value` | Owner contract / architecture addition; no original string | `/admin/settings?group=forms` · `fields[{id}][value][en/bn]` | text | `InquiryController::store` success-kind selection → contact form status | Editor/render implemented |
| `contact.success_contact` → `ContentField.value` | Existing original/new-copy rows above; code registry contact.success_contact | `/admin/settings?group=forms` · `fields[{id}][value][en/bn]` | text | `InquiryController::store` success-kind selection → contact form status | Editor/render implemented |
| `contact.mode_apply` → `ContentField.value` | Owner contract / architecture addition; no original string | `/admin/settings?group=forms` · `fields[{id}][value][en/bn]` | text | `resources/views/public/pages/contact.blade.php` | Editor/render implemented |
| `contact.validation_required` → `ContentField.value` | Owner contract / architecture addition; no original string | `/admin/settings?group=forms` · `fields[{id}][value][en/bn]` | text | `app/Http/Controllers/InquiryController.php` | Editor/render implemented |
| `contact.validation_invalid` → `ContentField.value` | Owner contract / architecture addition; no original string | `/admin/settings?group=forms` · `fields[{id}][value][en/bn]` | text | `app/Http/Controllers/InquiryController.php` | Editor/render implemented |
| `contact.validation_max` → `ContentField.value` | Owner contract / architecture addition; no original string | `/admin/settings?group=forms` · `fields[{id}][value][en/bn]` | text | `app/Http/Controllers/InquiryController.php` | Editor/render implemented |
| `contact.save_failure` → `ContentField.value` | Owner contract / architecture addition; no original string | `/admin/settings?group=forms` · `fields[{id}][value][en/bn]` | text | Reserved inquiry failure/pending copy; Step 11 wiring pending | Editor implemented; future request wiring |
| `contact.submit_pending` → `ContentField.value` | Owner contract / architecture addition; no original string | `/admin/settings?group=forms` · `fields[{id}][value][en/bn]` | text | Reserved inquiry failure/pending copy; Step 11 wiring pending | Editor implemented; future request wiring |
| `ui.empty_filter_results` → `ContentField.value` | Owner contract / architecture addition; no original string | `/admin/settings?group=ui` · `fields[{id}][value][en/bn]` | text | `resources/views/public/parts/articles.blade.php`; `resources/views/public/parts/gallery.blade.php` | Editor/render implemented |
| `ui.menu_close` → `ContentField.value` | Owner contract / architecture addition; no original string | `/admin/settings?group=ui` · `fields[{id}][value][en/bn]` | text | `resources/views/public/parts/navigation.blade.php` | Editor/render implemented |
| `ui.switch_to_bn` → `ContentField.value` | L6 বাংলা | `/admin/settings?group=ui` · `fields[{id}][value][en/bn]` | text | `resources/views/public/parts/navigation.blade.php` | Editor/render implemented |
| `ui.switch_to_en` → `ContentField.value` | L6 English | `/admin/settings?group=ui` · `fields[{id}][value][en/bn]` | text | `resources/views/public/parts/navigation.blade.php` | Editor/render implemented |
| `ui.open_navigation` → `ContentField.value` | L6 Open navigation | `/admin/settings?group=ui` · `fields[{id}][value][en/bn]` | text | `resources/views/public/parts/navigation.blade.php` | Editor/render implemented |
| `ui.gallery_preview` → `ContentField.value` | L23 Gallery image preview | `/admin/settings?group=ui` · `fields[{id}][value][en/bn]` | text | `resources/views/public/parts/lightbox.blade.php` | Editor/render implemented |
| `ui.close_lightbox` → `ContentField.value` | L23 Close | `/admin/settings?group=ui` · `fields[{id}][value][en/bn]` | text | `resources/views/public/parts/lightbox.blade.php` | Editor/render implemented |
| `footer.social_label` → `ContentField.value` | L7 Facebook ↗ | `/admin/settings?group=social` · `fields[{id}][value][en/bn]` | text | `resources/views/public/parts/footer.blade.php` | Editor/render implemented |
| `footer.copyright_format` → `ContentField.value` | L7 © current year brand · galleryNote | `/admin/settings?group=footer` · `fields[{id}][value][en/bn]` | format | `resources/views/public/parts/footer.blade.php` | Editor/render implemented |
| `meta.title_format` → `ContentField.value` | L23 localized page label \| brand | `/admin/settings?group=information` · `fields[{id}][value][en/bn]` | format | `app/Http/Controllers/PublicPageController.php` | Editor/render implemented |
| `home.care_eyebrow` → `ContentField.value` | L14 OUR AREAS OF CARE | `/admin/pages/home/edit` · `fields[{id}][value][en/bn]` | text | `resources/views/public/pages/home.blade.php` | Editor/render implemented |
| `home.journal_eyebrow` → `ContentField.value` | L14 THE JOURNAL | `/admin/pages/home/edit` · `fields[{id}][value][en/bn]` | text | `resources/views/public/pages/home.blade.php` | Editor/render implemented |
| `home.gallery_eyebrow` → `ContentField.value` | L14 VISUAL STORIES | `/admin/pages/home/edit` · `fields[{id}][value][en/bn]` | text | `resources/views/public/pages/home.blade.php` | Editor/render implemented |
| `home.hero_alt` → `ContentField.value` | L14 home Illustrative healthcare portrait — not the actual doctor | `/admin/settings?group=branding` · `fields[{id}][value][en/bn]` | text | `resources/views/public/pages/home.blade.php` | Editor/render implemented |
| `home.doctor_alt` → `ContentField.value` | L14 home Healthcare consultation illustration | `/admin/pages/home/edit` · `fields[{id}][value][en/bn]` | text | `resources/views/public/pages/home.blade.php` | Editor/render implemented |
| `about.image_alt` → `ContentField.value` | L15 about Illustrative consultation photo | `/admin/pages/about/edit` · `fields[{id}][value][en/bn]` | text | `resources/views/public/pages/about.blade.php` | Editor/render implemented |
| `diagnostics.image_alt` → `ContentField.value` | L18 diagnostics Illustrative lab stock photograph | `/admin/pages/diagnostics/edit` · `fields[{id}][value][en/bn]` | text | `resources/views/public/pages/diagnostics.blade.php` | Editor/render implemented |
| `shared.brand_target` → `ContentField.value` | L6/L7 index.html; ✳ | `/admin/settings?group=branding` · `fields[{id}][value][en/bn]` | target | `resources/views/public/parts/navigation.blade.php` | Editor/render implemented |
| `home.primary_target` → `ContentField.value` | L14 contact.html | `/admin/pages/home/edit` · `fields[{id}][value][en/bn]` | target | `resources/views/public/pages/home.blade.php` | Editor/render implemented |
| `home.secondary_target` → `ContentField.value` | L14 services.html | `/admin/pages/home/edit` · `fields[{id}][value][en/bn]` | target | `resources/views/public/pages/home.blade.php` | Editor/render implemented |
| `home.doctor_target` → `ContentField.value` | L14 about.html | `/admin/pages/home/edit` · `fields[{id}][value][en/bn]` | target | `resources/views/public/pages/home.blade.php` | Editor/render implemented |
| `home.journal_target` → `ContentField.value` | L14 blog.html | `/admin/pages/home/edit` · `fields[{id}][value][en/bn]` | target | `resources/views/public/pages/home.blade.php` | Editor/render implemented |
| `home.gallery_target` → `ContentField.value` | L14 gallery.html | `/admin/pages/home/edit` · `fields[{id}][value][en/bn]` | target | `resources/views/public/pages/home.blade.php` | Editor/render implemented |
| `diagnostics.contact_target` → `ContentField.value` | L18 contact.html | `/admin/pages/diagnostics/edit` · `fields[{id}][value][en/bn]` | target | `resources/views/public/pages/diagnostics.blade.php` | Editor/render implemented |
| `home.article_count` → `ContentField.value` | Existing original/new-copy rows above; code registry home.article_count | `/admin/pages/home/edit` · `fields[{id}][value]` | integer | `app/Http/Controllers/PublicPageController.php` | Editor/render implemented |
| `home.gallery_count` → `ContentField.value` | Existing original/new-copy rows above; code registry home.gallery_count | `/admin/pages/home/edit` · `fields[{id}][value]` | integer | `app/Http/Controllers/PublicPageController.php` | Editor/render implemented |
| `home.doctor_image` → `ContentField.media_asset_id` | L14 home PHOTOS.consult | `/admin/pages/home/edit` · `fields[{id}][media_asset_id]` | media | `resources/views/public/pages/home.blade.php` | Editor/render implemented |
| `about.image` → `ContentField.media_asset_id` | L15 about PHOTOS.care | `/admin/pages/about/edit` · `fields[{id}][media_asset_id]` | media | `resources/views/public/pages/about.blade.php` | Editor/render implemented |
| `diagnostics.image` → `ContentField.media_asset_id` | L18 diagnostics PHOTOS.lab | `/admin/pages/diagnostics/edit` · `fields[{id}][media_asset_id]` | media | `resources/views/public/pages/diagnostics.blade.php` | Editor/render implemented |

### Page-owned labels and metadata (every page)

| Page key | Admin screen | Public label location | Status |
| --- | --- | --- | --- |
| `home` | `/admin/pages/home/edit` | Document-title format; Home hero has its own home.eyebrow | Label + all metadata controls implemented |
| `about` | `/admin/pages/about/edit` | `public.pages.about` → `public.parts.hero` eyebrow | Label + all metadata controls implemented |
| `services` | `/admin/pages/services/edit` | `public.pages.services` → `public.parts.hero` eyebrow | Label + all metadata controls implemented |
| `diagnostics` | `/admin/pages/diagnostics/edit` | `public.pages.diagnostics` → `public.parts.hero` eyebrow | Label + all metadata controls implemented |
| `blog` | `/admin/pages/blog/edit` | `public.pages.blog` → `public.parts.hero` eyebrow | Label + all metadata controls implemented |
| `gallery` | `/admin/pages/gallery/edit` | `public.pages.gallery` → `public.parts.hero` eyebrow | Label + all metadata controls implemented |
| `contact` | `/admin/pages/contact/edit` | `public.pages.contact` → `public.parts.hero` eyebrow | Label + all metadata controls implemented |
| `article` | `/admin/pages/article/edit` | Article back-to-blog link; editorial badge belongs to Post | Label + all metadata controls implemented |

| Field on each of the eight Page records | Source | Form control | Render/fallback | Status |
| --- | --- | --- | --- | --- |
| `label {en,bn}` | Original localized page labels / article back-link | `page[label][en/bn]` | As above; navigation labels stay independent | Implemented |
| `meta_title {en,bn}` | Original head + initialized title format | `page[meta_title][en/bn]`, max 200 | public.layouts.app `<title>`; Post metadata → Page → shared DB format; locale → English fallback | Editor/render implemented |
| `meta_description {en,bn}` | Original head / initialization metadata | `page[meta_description][en/bn]`, max 500 | public.layouts.app description; Post → Page → excerpt or shared biography | Editor/render implemented |
| `og_title {en,bn}` | Step 7 owner addition, initially null | `page[og_title][en/bn]`, max 200 | Planned OG → meta → same default; public tags Step 13 | Stored/validated; rendering pending Step 13 |
| `og_description {en,bn}` | Step 7 owner addition, initially null | `page[og_description][en/bn]`, max 500 | Planned OG → meta → same default; public tags Step 13 | Stored/validated; rendering pending Step 13 |
| `og_media_asset_id` | Step 7 owner addition, initially null | Shared media picker `page[og_media_asset_id]` | Planned Page OG → Post image where applicable → portrait → omit | FK/cast/relation + usage protection implemented; public tag pending |

### Fixed sections

| PageSection.key | Admin control | Public section | Status |
| --- | --- | --- | --- |
| `home.hero` | `/admin/pages/home/edit`, Read-only Visible badge; server rejects hiding | Original position 0; `public.pages.home` / shared partial | Required structure; position fixed |
| `home.care` | `/admin/pages/home/edit`, `sections[{id}][is_visible]` checkbox | Original position 1; `public.pages.home` / shared partial | Visibility implemented; position fixed |
| `home.doctor` | `/admin/pages/home/edit`, `sections[{id}][is_visible]` checkbox | Original position 2; `public.pages.home` / shared partial | Visibility implemented; position fixed |
| `home.journal` | `/admin/pages/home/edit`, `sections[{id}][is_visible]` checkbox | Original position 3; `public.pages.home` / shared partial | Visibility implemented; position fixed |
| `home.gallery` | `/admin/pages/home/edit`, `sections[{id}][is_visible]` checkbox | Original position 4; `public.pages.home` / shared partial | Visibility implemented; position fixed |
| `home.cta` | `/admin/pages/home/edit`, `sections[{id}][is_visible]` checkbox | Original position 5; `public.pages.home` / shared partial | Visibility implemented; position fixed |
| `about.profile` | `/admin/pages/about/edit`, Read-only Visible badge; server rejects hiding | Original position 0; `public.pages.about` / shared partial | Required structure; position fixed |
| `about.information` | `/admin/pages/about/edit`, Read-only Visible badge; server rejects hiding | Original position 1; `public.pages.about` / shared partial | Required structure; position fixed |
| `about.cta` | `/admin/pages/about/edit`, `sections[{id}][is_visible]` checkbox | Original position 2; `public.pages.about` / shared partial | Visibility implemented; position fixed |
| `services.services` | `/admin/pages/services/edit`, Read-only Visible badge; server rejects hiding | Original position 0; `public.pages.services` / shared partial | Required structure; position fixed |
| `services.cta` | `/admin/pages/services/edit`, `sections[{id}][is_visible]` checkbox | Original position 1; `public.pages.services` / shared partial | Visibility implemented; position fixed |
| `diagnostics.laboratory` | `/admin/pages/diagnostics/edit`, Read-only Visible badge; server rejects hiding | Original position 0; `public.pages.diagnostics` / shared partial | Required structure; position fixed |
| `diagnostics.cta` | `/admin/pages/diagnostics/edit`, `sections[{id}][is_visible]` checkbox | Original position 1; `public.pages.diagnostics` / shared partial | Visibility implemented; position fixed |
| `blog.posts` | `/admin/pages/blog/edit`, Read-only Visible badge; server rejects hiding | Original position 0; `public.pages.blog` / shared partial | Required structure; position fixed |
| `blog.cta` | `/admin/pages/blog/edit`, `sections[{id}][is_visible]` checkbox | Original position 1; `public.pages.blog` / shared partial | Visibility implemented; position fixed |
| `gallery.images` | `/admin/pages/gallery/edit`, Read-only Visible badge; server rejects hiding | Original position 0; `public.pages.gallery` / shared partial | Required structure; position fixed |
| `gallery.cta` | `/admin/pages/gallery/edit`, `sections[{id}][is_visible]` checkbox | Original position 1; `public.pages.gallery` / shared partial | Visibility implemented; position fixed |
| `contact.information_form` | `/admin/pages/contact/edit`, Read-only Visible badge; server rejects hiding | Original position 0; `public.pages.contact` / shared partial | Required structure; position fixed |
| `article.article` | `/admin/pages/article/edit`, Read-only Visible badge; server rejects hiding | Original position 0; `public.pages.article` / shared partial | Required structure; position fixed |
| `article.cta` | `/admin/pages/article/edit`, `sections[{id}][is_visible]` checkbox | Original position 1; `public.pages.article` / shared partial | Visibility implemented; position fixed |

### Navigation ownership

All original header keys (`header:home/about/services/diagnostics/blog/gallery/contact`) and footer keys (`footer:about/services/blog/gallery`) are edited through `/admin/navigation/{id}/edit`. Their original source row is retained above.

| Database field | Exact control | Public rendering | Status |
| --- | --- | --- | --- |
| NavigationItem.label | `item[label][en/bn]` shared localized editor | public.parts.navigation/footer; header:contact also supplies footer heading/diagnostics button | Implemented |
| NavigationItem.target | `item[target][en/bn]` internal/external selector → localized string | Shared WebsiteContent::target resolver and locale-aware named links | Implemented; no admin/nonexistent/executable routes |
| placement | `item[placement]`, header/footer enum | Header or footer collection | Existing identity/placement protected; custom entries movable |
| sort_order | `item[sort_order]`, 0–65535 | Header/footer ordered by sort_order then id | Implemented |
| is_visible | `item[is_visible]`, checkbox + hidden false | Header/footer filtering; language switch/private admin unaffected | Implemented |
| key | No editable input | Shared relational identity | Immutable; server generates custom keys |

Public stock/medical/diagnostic notices, original collection order and first-three initialized previews remain. Only the separate interaction stylesheet adds plain-text line-break preservation; public baseline CSS/DOM and frontend stay unchanged. Page metadata uses database fallback now; OG rendering is explicitly pending. All database test edits roll back, leaving development values/publication/visibility and owner credentials untouched.

## Step 8 exact care/service/consultation controls

The 44 existing source/mapping rows above now point to these implemented independent editors. Identical source titles initialized both a care card and an appointment choice; later edits are independent. This reconciliation supersedes historical `icon_code`, `import_key`, service `description` and `is_visible` proposal names for these owners. Initial database counts remain **4 / 5 / 4** in source order; initialization input is unchanged.

| Database owner.field | Source | Exact admin control | Public rendering | Status |
| --- | --- | --- | --- | --- |
| CareCard.key | Original L9 card identity; random custom identity on create | `/admin/care-cards/{id}/edit`, read-only; generated on create | Stable content/import identity; no public separate card route | Protected |
| CareCard.title | L2 obtitle/gytitle/iuititle/diatitle, L9 cards | `item[title][en/bn]` localized input | `public.parts.care-card` h3 on Home | Implemented |
| CareCard.description | L2 obdesc/gydesc/iuidesc/diadesc | `item[description][en/bn]`, bounded text/approved fact format | Same card p via shared format/localization | Implemented |
| CareCard.icon | L9 🤰/✿/♡/🔬 | `item[icon][en/bn]`, fixed-glyph selects | Same `.service-icon`, escaped text | Implemented; no SVG/HTML |
| CareCard.link_label | L2 learn; L9 card mini | `item[link_label][en/bn]` | Same `.mini` span | Implemented |
| CareCard.target | L9 services.html / diagnostics.html | `item[target][en/bn][kind/route]` → JSON named route string; only services/diagnostics | Same card anchor, selected locale propagated | Implemented; no external target |
| CareCard.sort_order | L9 original order | `/admin/care-cards/{id}/move`, PATCH `direction=up/down`; no numeric input | Enabled cards ordered by sort_order,id inside existing grid2 | Transactional |
| CareCard.is_enabled | Initial all visible; CMS addition | `item[is_enabled]`, or PATCH visibility `state`; confirm disabling | Only this card's Home visibility | Implemented |
| Service.key | L16 initial service identity | `/admin/services/{id}/edit`, read-only; generated on custom create | Stable identity | Protected |
| Service.slug | L16 derived stable service key | `/admin/services/create`, `item[slug]`, unique lowercase slug; read-only thereafter | Stable stored identity, no new public individual-service route | Validated/immutable |
| Service.title | L16 serviceDetails.en/bn[*][0] | `item[title][en/bn]` | `public.pages.services` .info-box h3 | Implemented |
| Service.body | L16 serviceDetails.en/bn[*][1] | `item[body][en/bn]`, bounded plain-text textarea | Same .info-box p, escaped | Implemented |
| Service.sort_order | L17 original five blocks | `/admin/services/{id}/move`, PATCH direction | Enabled order; Blade derives ordinal `01…` | Transactional; no stored number |
| Service.is_enabled | Initial five enabled; CMS addition | `item[is_enabled]` or PATCH visibility state, confirm disabling | Only Services blocks, independent of cards/options | Implemented |
| ConsultationOption.key | L22 original four choices | `/admin/consultation-options/create`, `item[key]`, unique lowercase key; read-only thereafter | Stable inquiry snapshot identity | Validated/immutable |
| ConsultationOption.label | L22 obtitle/gytitle/iuititle/diatitle initially copied | `item[label][en/bn]` shared editor | `public.pages.contact` appointment-only #topic options | Implemented; independent label |
| ConsultationOption.sort_order | L22 original option order | `/admin/consultation-options/{id}/move`, PATCH direction | Active options in display order | Transactional |
| ConsultationOption.is_active | Initial four active; CMS addition | `item[is_active]` or PATCH visibility state, confirm disabling | Appointment select and server active-choice validation | Implemented |
| Inquiry.consultation_key/consultation_label | Selected option at real submission | No public/editable collection control; snapshot kept on rename/disable/delete | Private stored readable snapshot; never a public list | Retained; nullable FK, no cascade |

All collection deletes use DELETE plus `confirm_delete=1` in the shared confirmation component. Ordinary reseeding cannot restore intentional deletions. Empty grids reuse database `ui.empty_filter_results`; empty appointment choices disable appointment submit while General contact remains available. All normal populated markup/classes and baseline CSS are unchanged. Both translations save together; omitted language stays intact and empty Bangla falls back through LocalizedContent.

### Diagnostics stays in fixed Page Content

| Owned field / relationship | Exact editor | Public rendering | Status |
| --- | --- | --- | --- |
| Page(diagnostics).label | `/admin/pages/diagnostics/edit`, `page[label][en/bn]` | Hero eyebrow | Existing editor reused |
| diagnostics.diagtitle / diagdesc | Same screen, `fields[{id}][value][en/bn]` | Hero title/intro | Verified next-request bilingual edits |
| diagnostics.image / image_alt | Same screen, media picker `fields[{id}][media_asset_id]` / localized value | Original splitimg dimensions/classes and escaped alt | Verified shared media resolver |
| diagnostics.diagtags / diagtagsdesc | Same screen, localized value | Inquiry-topic h3/copy; categories/catalog not created | Verified |
| diagnostics.diagnotice | Same screen, localized value, non-empty English | Original availability notice | Preserved unless intentionally edited |
| diagnostics.contact_target | Same screen, named contact destination control | Original contact button href | Safe request target retained |
| NavigationItem(header:contact).label | `/admin/navigation/{id}/edit`, item[label][en/bn] | Diagnostics contact button text | Shared existing owner |
| SiteSetting.clinic_name/address/hours/friday_note; shared.visitTitle/visitText | `/admin/settings?group=clinic` / `group=information` | Laboratory clinic label and visiting heading/shared fact format; shared contact/footer uses | Verified canonical clinic/address; no duplicate |
| SiteSetting.cta_title/description/label/target | `/admin/settings?group=cta` | Shared CTA, contact target | Verified next-request change |
| PageSection(diagnostics.cta).is_visible | Fixed page's existing section visibility checkbox | Shared CTA presence; laboratory remains required | Existing editor reused |

No diagnostic tests, prices, booking slots or reports were added. See CARE_CONTENT for routes, ordering/snapshot behavior, exact tests and manual desktop/mobile checks. Article/category/gallery staff editors and private inbox remain pending.
