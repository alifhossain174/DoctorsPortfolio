<?php

namespace Tests\Feature;

use App\Models\CareCard;
use App\Models\ConsultationOption;
use App\Models\ContentField;
use App\Models\Inquiry;
use App\Models\MediaAsset;
use App\Models\Service;
use App\Models\User;
use App\Services\InitialWebsiteImporter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class CareCollectionsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        if (DB::connection()->getDatabaseName() !== 'doctor_portfolio_test') {
            throw new \RuntimeException('Use doctor_portfolio_test; do not edit owner content.');
        }
        DB::beginTransaction();
        $this->beforeApplicationDestroyed(fn () => DB::rollBack());
        app(InitialWebsiteImporter::class)->import();
        $admin = User::create(['name' => 'Test care editor', 'email' => Str::uuid().'@example.test', 'password' => bin2hex(random_bytes(24))]);
        $admin->forceFill(['is_admin' => true])->save();
        $this->actingAs($admin);
    }

    private function ordered(string $model): array
    {
        return $model::orderBy('sort_order')->orderBy('id')->pluck('id')->all();
    }

    private function assertOrder(string $model, array $ids): void
    {
        $this->assertSame($ids, $this->ordered($model));
        $this->assertSame(range(0, count($ids) - 1), $model::orderBy('sort_order')->orderBy('id')->pluck('sort_order')->all());
    }

    private function optionCount(string $html): int
    {
        $dom = new \DOMDocument;
        @$dom->loadHTML('<?xml encoding="UTF-8">'.$html);

        return (new \DOMXPath($dom))->query('//select[@id="topic"]/option')->length;
    }

    public function test_original_collections_remain_independent_and_all_editors_render(): void
    {
        $snapshot = json_decode(file_get_contents(database_path('seeders/data/initial-website.json')), true, flags: JSON_THROW_ON_ERROR);
        foreach (['care-cards' => ['care_cards', CareCard::class, 4], 'services' => ['services', Service::class, 5], 'consultation-options' => ['consultation_options', ConsultationOption::class, 4]] as $path => [$table, $model, $count]) {
            $this->assertSame($count, $model::count());
            $this->assertSame(array_column($snapshot['records'][$table], 'key'), $model::orderBy('sort_order')->orderBy('id')->pluck('key')->all());
            $this->get('/admin/'.$path)->assertOk()->assertSee('Move up')->assertSee('Confirm disable');
            $this->get('/admin/'.$path.'/create')->assertOk()->assertSee('English')->assertSee('বাংলা');
            foreach ($model::all() as $record) {
                $this->get('/admin/'.$path.'/'.$record->id.'/edit')->assertOk()->assertSee('confirm_delete')->assertSee('Stable key:')->assertSee(e($record->key), false);
            }
        }
        $form = $this->get('/admin/care-cards/'.CareCard::firstOrFail()->id.'/edit');
        $form->assertSee('name="item[target][en][route]"', false)->assertSee('name="item[icon][bn]"', false)->assertDontSee('value="external"', false)->assertDontSee('name="item[sort_order]"', false);
        foreach (['en', 'bn'] as $locale) {
            $home = $this->get('/?locale='.$locale)->assertOk()->getContent();
            $this->assertSame(4, substr_count($home, 'class="card service-card"'));
            $services = $this->get('/services?locale='.$locale)->assertOk()->getContent();
            preg_match_all('/<span class="eyebrow">(\d{2})<\/span>/', $services, $numbers);
            $this->assertSame(['01', '02', '03', '04', '05'], $numbers[1]);
            $this->assertSame(4, $this->optionCount($this->get('/contact?locale='.$locale)->assertOk()->getContent()));
        }
        $this->assertSame(0, $this->optionCount($this->get('/contact?mode=contact')->getContent()));
        $this->assertArrayNotHasKey('appointment_selectable', config('cms_models.Service'));
    }

    public function test_care_card_crud_targets_translations_and_fallback(): void
    {
        $input = ['title' => ['en' => 'Owner card', 'bn' => 'মালিকের কার্ড'], 'description' => ['en' => "Plain copy\nSecond line", 'bn' => "সাধারণ লেখা\nদ্বিতীয় লাইন"], 'icon' => ['en' => '♡', 'bn' => '♡'], 'link_label' => ['en' => 'View details', 'bn' => 'বিস্তারিত দেখুন'], 'target' => ['en' => ['kind' => 'internal', 'route' => 'services'], 'bn' => ['kind' => 'internal', 'route' => 'diagnostics']], 'is_enabled' => '1'];
        $this->post('/admin/care-cards', ['item' => $input])->assertSessionHasNoErrors();
        $card = CareCard::where('title->en', 'Owner card')->firstOrFail();
        $this->assertStringStartsWith('custom-', $card->key);
        $this->assertSame(4, $card->sort_order);
        $this->assertSame(['en' => 'services', 'bn' => 'diagnostics'], $card->target);
        $this->get('/?locale=en')->assertSee('href="'.route('services', ['locale' => 'en']).'" class="card service-card"', false)->assertSee('Owner card')->assertSee('View details');
        $this->get('/?locale=bn')->assertSee('href="'.route('diagnostics', ['locale' => 'bn']).'" class="card service-card"', false)->assertSee('মালিকের কার্ড');
        $this->put('/admin/care-cards/'.$card->id, ['item' => ['title' => ['en' => 'English only <script>text</script>']]])->assertSessionHasNoErrors();
        $this->assertSame('মালিকের কার্ড', $card->fresh()->title['bn']);
        $this->get('/?locale=en')->assertSee('English only &lt;script&gt;text&lt;/script&gt;', false)->assertDontSee('English only <script>', false);
        $this->put('/admin/care-cards/'.$card->id, ['item' => ['title' => ['bn' => '']]])->assertSessionHasNoErrors();
        $this->get('/?locale=bn')->assertSee('English only &lt;script&gt;text&lt;/script&gt;', false);
        $this->put('/admin/care-cards/'.$card->id, ['item' => ['title' => ['bn' => 'পুনরুদ্ধার করা কার্ড']]])->assertSessionHasNoErrors();
        $this->delete('/admin/care-cards/'.$card->id)->assertSessionHasErrors('confirm_delete');
        $this->assertNotNull($card->fresh());
        $this->delete('/admin/care-cards/'.$card->id, ['confirm_delete' => '1'])->assertSessionHasNoErrors();
        $this->assertNull($card->fresh());
        $this->assertSame(5, Service::count());
        $this->assertSame(4, ConsultationOption::count());
    }

    public function test_card_fields_and_create_requests_are_strictly_validated(): void
    {
        $card = CareCard::firstOrFail();
        foreach (['home', 'contact', 'javascript:alert(1)', 'https://example.test', 'admin.dashboard', '//example.test'] as $target) {
            $this->put('/admin/care-cards/'.$card->id, ['item' => ['target' => ['en' => $target]]])->assertSessionHasErrors('item.target.en');
        }
        foreach (['<svg onload="evil()"></svg>', '<script>evil()</script>', 'unvalidated-icon'] as $icon) {
            $this->put('/admin/care-cards/'.$card->id, ['item' => ['icon' => ['en' => $icon]]])->assertSessionHasErrors('item.icon.en');
        }
        $this->put('/admin/care-cards/'.$card->id, ['item' => ['title' => ['en' => 'Changed', 'fr' => 'No']]])->assertSessionHasErrors('item.title');
        foreach (['key', 'sort_order', 'is_admin', 'appointment_selectable'] as $field) {
            $this->put('/admin/care-cards/'.$card->id, ['item' => [$field => 'evil']])->assertSessionHasErrors('item');
        }
        $this->post('/admin/care-cards', ['item' => ['title' => ['bn' => 'শুধু বাংলা']]])->assertSessionHasErrors(['item.title.en', 'item.description', 'item.icon', 'item.target']);
        $this->assertSame(4, CareCard::count());
    }

    public function test_services_crud_stable_identity_and_visible_numbering(): void
    {
        $input = ['slug' => 'owner-service', 'title' => ['en' => 'Owner service block', 'bn' => 'মালিকের সেবা বিবরণ'], 'body' => ['en' => "Plain body\nSecond line", 'bn' => "সাধারণ বিবরণ\nদ্বিতীয় লাইন"], 'is_enabled' => '1'];
        $this->post('/admin/services', ['item' => $input])->assertSessionHasNoErrors();
        $service = Service::where('slug', 'owner-service')->firstOrFail();
        $this->assertSame(5, $service->sort_order);
        $this->post('/admin/services', ['item' => $input])->assertSessionHasErrors('item.slug');
        $this->post('/admin/services', ['item' => [...$input, 'slug' => '../unsafe']])->assertSessionHasErrors('item.slug');
        $this->put('/admin/services/'.$service->id, ['item' => ['slug' => 'new-slug']])->assertSessionHasErrors('item');
        $this->put('/admin/services/'.$service->id, ['item' => ['key' => 'new-key']])->assertSessionHasErrors('item');
        $this->put('/admin/services/'.$service->id, ['item' => ['body' => ['en' => 'Changed English body']]])->assertSessionHasNoErrors();
        $this->assertSame($input['body']['bn'], $service->fresh()->body['bn']);
        $this->get('/services?locale=en')->assertSee('Owner service block')->assertSee('Changed English body');
        $this->get('/services?locale=bn')->assertSee('মালিকের সেবা বিবরণ')->assertSee($input['body']['bn']);
        $first = Service::orderBy('sort_order')->firstOrFail();
        $this->patch('/admin/services/'.$first->id.'/visibility', ['state' => '0', 'confirm_disable' => '1'])->assertSessionHasNoErrors();
        $html = $this->get('/services?locale=en')->assertDontSee($first->title['en'])->getContent();
        preg_match_all('/<span class="eyebrow">(\d{2})<\/span>/', $html, $numbers);
        $this->assertSame(['01', '02', '03', '04', '05'], $numbers[1]);
        $this->assertSame(4, substr_count($this->get('/')->getContent(), 'class="card service-card"'));
        $this->assertSame(4, $this->optionCount($this->get('/contact')->getContent()));
        $this->delete('/admin/services/'.$service->id, ['confirm_delete' => '1'])->assertSessionHasNoErrors();
        $this->assertNull($service->fresh());
        $this->assertSame(5, Service::count());
    }

    public function test_move_controls_normalize_each_collection_with_disabled_records_and_boundaries(): void
    {
        foreach (config('care_collections') as $path => $definition) {
            $model = $definition['model'];
            $original = $this->ordered($model);
            $this->patch('/admin/'.$path.'/'.$original[0].'/visibility', ['state' => '0', 'confirm_disable' => '1'])->assertSessionHasNoErrors();
            $this->patch('/admin/'.$path.'/'.$original[1].'/move', ['direction' => 'up'])->assertSessionHasNoErrors();
            $moved = [$original[1], $original[0], ...array_slice($original, 2)];
            $this->assertOrder($model, $moved);
            $this->patch('/admin/'.$path.'/'.$original[1].'/move', ['direction' => 'up'])->assertSessionHasNoErrors();
            $this->assertOrder($model, $moved);
            $this->patch('/admin/'.$path.'/'.$original[1].'/move', ['direction' => 'down'])->assertSessionHasNoErrors();
            $this->assertOrder($model, $original);
            $this->patch('/admin/'.$path.'/'.end($original).'/move', ['direction' => 'down'])->assertSessionHasNoErrors();
            $this->patch('/admin/'.$path.'/'.$original[0].'/move', ['direction' => 'left'])->assertSessionHasErrors('direction');
            $this->assertOrder($model, $original);
            $this->delete('/admin/'.$path.'/'.$original[1], ['confirm_delete' => '1'])->assertSessionHasNoErrors();
            $this->assertOrder($model, [$original[0], ...array_slice($original, 2)]);
        }
    }

    public function test_public_order_matches_changes_in_both_languages_and_scoped_visibility(): void
    {
        foreach (config('care_collections') as $path => $definition) {
            $ids = $this->ordered($definition['model']);
            $second = $definition['model']::findOrFail($ids[1]);
            $first = $definition['model']::findOrFail($ids[0]);
            $this->patch('/admin/'.$path.'/'.$second->id.'/move', ['direction' => 'up'])->assertSessionHasNoErrors();
            foreach (['en', 'bn'] as $locale) {
                $this->get(route($definition['preview'], ['locale' => $locale]))->assertSeeInOrder([e($second->localized($definition['label_field'], $locale)), e($first->localized($definition['label_field'], $locale))], false);
            }
            $this->patch('/admin/'.$path.'/'.$second->id.'/visibility', ['state' => '0'])->assertSessionHasErrors('confirm_disable');
            $this->assertTrue($second->fresh()->getAttribute($definition['state']));
            $this->put('/admin/'.$path.'/'.$second->id, ['item' => [$definition['state'] => '0']])->assertSessionHasErrors('confirm_disable');
            $this->patch('/admin/'.$path.'/'.$second->id.'/visibility', ['state' => '0', 'confirm_disable' => '1'])->assertSessionHasNoErrors();
            foreach (['en', 'bn'] as $locale) {
                $this->get(route($definition['preview'], ['locale' => $locale]))->assertDontSee($second->localized($definition['label_field'], $locale));
            }
            $this->patch('/admin/'.$path.'/'.$second->id.'/visibility', ['state' => '1'])->assertSessionHasNoErrors();
            $this->assertTrue($second->fresh()->getAttribute($definition['state']));
        }
    }

    public function test_consultation_crud_preserves_inquiry_snapshots_and_rejects_disabled_choices(): void
    {
        $this->post('/admin/consultation-options', ['item' => ['key' => 'owner-topic', 'label' => ['en' => 'Owner topic', 'bn' => 'মালিকের আলোচনার বিষয়'], 'is_active' => '1']])->assertSessionHasNoErrors();
        $option = ConsultationOption::where('key', 'owner-topic')->firstOrFail();
        $this->assertSame(4, $option->sort_order);
        $this->post('/admin/consultation-options', ['item' => ['key' => 'owner-topic', 'label' => ['en' => 'Duplicate'], 'is_active' => '1']])->assertSessionHasErrors('item.key');
        $this->post('/admin/consultation-options', ['item' => ['key' => '../bad', 'label' => ['en' => 'Invalid'], 'is_active' => '1']])->assertSessionHasErrors('item.key');
        $this->put('/admin/consultation-options/'.$option->id, ['item' => ['key' => 'changed']])->assertSessionHasErrors('item');
        $inquiry = Inquiry::create(['kind' => 'appointment', 'name' => 'Isolated test request', 'phone' => 'test-only', 'consultation_option_id' => $option->id, 'locale' => 'bn']);
        $snapshot = $inquiry->consultation_label;
        $this->assertSame('owner-topic', $inquiry->consultation_key);
        $this->put('/admin/consultation-options/'.$option->id, ['item' => ['label' => ['en' => 'Renamed topic', 'bn' => 'নতুন বিষয়']]])->assertSessionHasNoErrors();
        $this->put('/admin/consultation-options/'.$option->id, ['item' => ['label' => ['en' => 'English only rename']]])->assertSessionHasNoErrors();
        $this->assertSame('নতুন বিষয়', $option->fresh()->label['bn']);
        $this->assertSame($snapshot, $inquiry->fresh()->consultation_label);
        $this->assertSame(1, $option->inquiries()->count());
        $this->patch('/admin/consultation-options/'.$option->id.'/visibility', ['state' => '0', 'confirm_disable' => '1'])->assertSessionHasNoErrors();
        $this->get('/contact?locale=bn')->assertDontSee('নতুন বিষয়');
        $this->post('/inquiries', ['kind' => 'appointment', 'name' => 'Test only', 'phone' => 'test-only', 'locale' => 'bn', 'consultation_option_id' => $option->id])->assertSessionHasErrors('consultation_option_id')->assertSessionMissing('inquiry_success');
        $this->assertSame(1, Inquiry::count());
        $this->delete('/admin/consultation-options/'.$option->id, ['confirm_delete' => '1'])->assertSessionHasNoErrors();
        $saved = $inquiry->fresh();
        $this->assertNull($saved->consultation_option_id);
        $this->assertNull($saved->consultationOption);
        $this->assertSame($snapshot, $saved->consultation_label);
        $this->assertSame('owner-topic', $saved->consultation_key);
        $this->assertSame($inquiry->public_reference, $saved->public_reference);
        $this->get('/contact')->assertDontSee('Isolated test request')->assertDontSee($inquiry->public_reference);
        $this->assertSame(4, CareCard::count());
        $this->assertSame(5, Service::count());
    }

    public function test_failed_order_save_rolls_back_the_whole_swap(): void
    {
        $ids = $this->ordered(Service::class);
        $positions = Service::orderBy('id')->pluck('sort_order', 'id')->all();
        $writes = 0;
        Service::saving(function ($record) use (&$writes) {
            if ($record->isDirty('sort_order') && ++$writes === 2) {
                throw new \RuntimeException('Simulated second write failure');
            }
        });
        $this->patch('/admin/services/'.$ids[1].'/move', ['direction' => 'up'])->assertSessionHasErrors('item');
        $this->assertSame($ids, $this->ordered(Service::class));
        $this->assertSame($positions, Service::orderBy('id')->pluck('sort_order', 'id')->all());
    }

    public function test_deleted_originals_and_owner_edits_survive_repeat_initialization(): void
    {
        $card = CareCard::firstOrFail();
        $service = Service::firstOrFail();
        $option = ConsultationOption::firstOrFail();
        $this->delete('/admin/care-cards/'.$card->id, ['confirm_delete' => '1'])->assertSessionHasNoErrors();
        $this->put('/admin/services/'.$service->id, ['item' => ['title' => ['en' => 'Owner retained edit'], 'is_enabled' => '0'], 'confirm_disable' => '1'])->assertSessionHasNoErrors();
        $this->delete('/admin/consultation-options/'.$option->id, ['confirm_delete' => '1'])->assertSessionHasNoErrors();
        $this->assertTrue(app(InitialWebsiteImporter::class)->import()['skipped']);
        $this->assertSame(3, CareCard::count());
        $this->assertSame(3, ConsultationOption::count());
        $this->assertSame('Owner retained edit', $service->fresh()->title['en']);
        $this->assertFalse($service->fresh()->is_enabled);
    }

    public function test_diagnostics_remains_fixed_content_with_shared_clinic_references_and_preserved_notice(): void
    {
        $notice = ContentField::where('key', 'diagnostics.diagnotice')->firstOrFail();
        $original = $notice->value;
        $fields = [];
        foreach (['diagtitle' => ['Diagnostic information', 'ডায়াগনস্টিক তথ্য'], 'diagdesc' => ['Owner introduction', 'মালিকের পরিচিতি'], 'diagtags' => ['Inquiry topics', 'যোগাযোগের বিষয়'], 'diagtagsdesc' => ['Ask the chamber for details.', 'বিস্তারিত জানতে চেম্বারে যোগাযোগ করুন।'], 'image_alt' => ['Owner selected lab image', 'মালিকের নির্বাচিত ল্যাবের ছবি']] as $key => [$en, $bn]) {
            $field = ContentField::where('key', 'diagnostics.'.$key)->firstOrFail();
            $fields[$field->id] = ['value' => ['en' => $en, 'bn' => $bn]];
        }
        $image = MediaAsset::where('key', '!=', 'photo:lab')->firstOrFail();
        $imageField = ContentField::where('key', 'diagnostics.image')->firstOrFail();
        $fields[$imageField->id] = ['media_asset_id' => $image->id];
        $this->put('/admin/pages/diagnostics', ['fields' => $fields])->assertSessionHasNoErrors();
        $this->put('/admin/settings/clinic', ['settings' => ['clinic_name' => ['en' => 'Shared clinic name', 'bn' => 'চেম্বারের অভিন্ন নাম'], 'address' => ['en' => 'Shared owner address', 'bn' => 'মালিকের অভিন্ন ঠিকানা']]])->assertSessionHasNoErrors();
        $this->assertSame($original, $notice->fresh()->value);
        $this->get('/diagnostics?locale=en')->assertSee('Diagnostic information')->assertSee('Inquiry topics')->assertSee('Shared clinic name')->assertSee('Shared owner address')->assertSee($original['en'])->assertSee('alt="Owner selected lab image"', false)->assertSee(e($image->url()), false)->assertSee('href="'.route('contact', ['locale' => 'en']).'"', false);
        $this->get('/diagnostics?locale=bn')->assertSee('ডায়াগনস্টিক তথ্য')->assertSee('যোগাযোগের বিষয়')->assertSee('চেম্বারের অভিন্ন নাম')->assertSee('মালিকের অভিন্ন ঠিকানা')->assertSee($original['bn'])->assertSee('alt="মালিকের নির্বাচিত ল্যাবের ছবি"', false);
        $target = ContentField::where('key', 'diagnostics.contact_target')->firstOrFail();
        $this->put('/admin/pages/diagnostics', ['fields' => [$target->id => ['value' => ['en' => 'services']]]])->assertSessionHasErrors('fields.'.$target->id.'.value.en');
        $this->put('/admin/settings/cta', ['settings' => ['cta_title' => ['en' => 'Website request CTA', 'bn' => 'ওয়েবসাইটে অনুরোধ']]])->assertSessionHasNoErrors();
        $this->get('/diagnostics?locale=bn')->assertSee('ওয়েবসাইটে অনুরোধ');
        $this->assertSame(4, CareCard::count());
        $this->assertSame(5, Service::count());
        $this->assertSame(4, ConsultationOption::count());
    }

    public function test_empty_collections_have_safe_empty_states_and_allow_new_creation(): void
    {
        foreach (config('care_collections') as $path => $definition) {
            foreach ($definition['model']::all() as $record) {
                $this->patch('/admin/'.$path.'/'.$record->id.'/visibility', ['state' => '0', 'confirm_disable' => '1'])->assertSessionHasNoErrors();
            }
        }
        $empty = ContentField::where('key', 'ui.empty_filter_results')->firstOrFail()->value;
        $this->get('/?locale=bn')->assertSee($empty['bn'])->assertDontSee('class="card service-card"', false);
        $this->get('/services?locale=en')->assertSee($empty['en'])->assertDontSee('<span class="eyebrow">01</span>', false);
        $this->assertSame(0, $this->optionCount($this->get('/contact')->getContent()));
        $this->get('/contact?mode=contact')->assertOk()->assertSee('name="kind" value="contact"', false);
        ConsultationOption::query()->delete(); // Isolated test transaction, no owner database.
        $this->get('/admin/consultation-options')->assertOk()->assertSee('No Appointment consultation options yet');
        $this->post('/admin/consultation-options', ['item' => ['key' => 'first-new', 'label' => ['en' => 'First new option'], 'is_active' => '1']])->assertSessionHasNoErrors();
        $this->assertSame(0, ConsultationOption::firstOrFail()->sort_order);
        $this->assertSame(['en' => 'First new option', 'bn' => null], ConsultationOption::firstOrFail()->label);
    }

    public function test_all_collection_routes_protect_guests_non_admins_and_unknown_ids(): void
    {
        $this->get('/admin/services/999999999/edit')->assertNotFound();
        $this->get('/admin/services/not-a-number/edit')->assertNotFound();
        $this->app['auth']->guard()->logout();
        foreach (config('care_collections') as $path => $definition) {
            $id = $definition['model']::firstOrFail()->id;
            foreach (['/'.$path, '/'.$path.'/create', '/'.$path.'/'.$id.'/edit'] as $url) {
                $this->get('/admin'.$url)->assertRedirect('/admin/login');
            }
            foreach (['post' => '/admin/'.$path, 'put' => '/admin/'.$path.'/'.$id, 'delete' => '/admin/'.$path.'/'.$id] as $verb => $url) {
                $this->{$verb}($url, [])->assertRedirect('/admin/login');
            }
            $this->patch('/admin/'.$path.'/'.$id.'/move', [])->assertRedirect('/admin/login');
            $this->patch('/admin/'.$path.'/'.$id.'/visibility', [])->assertRedirect('/admin/login');
        }
        $user = User::create(['name' => 'Non-admin', 'email' => Str::uuid().'@example.test', 'password' => bin2hex(random_bytes(24))]);
        $this->flushSession(); // A fresh login must not inherit the previous admin's auth.session password hash.
        $this->actingAs($user);
        foreach (config('care_collections') as $path => $definition) {
            $id = $definition['model']::firstOrFail()->id;
            $this->get('/admin/'.$path)->assertForbidden();
            $this->get('/admin/'.$path.'/create')->assertForbidden();
            $this->get('/admin/'.$path.'/'.$id.'/edit')->assertForbidden();
            $this->post('/admin/'.$path, [])->assertForbidden();
            $this->put('/admin/'.$path.'/'.$id, [])->assertForbidden();
            $this->delete('/admin/'.$path.'/'.$id, [])->assertForbidden();
            $this->patch('/admin/'.$path.'/'.$id.'/move', [])->assertForbidden();
            $this->patch('/admin/'.$path.'/'.$id.'/visibility', [])->assertForbidden();
        }
        $this->assertSame(['care-cards', 'services', 'consultation-options'], array_keys(config('care_collections')));
    }
}
