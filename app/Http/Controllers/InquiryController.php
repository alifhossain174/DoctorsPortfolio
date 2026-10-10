<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Services\PublicUrl;
use App\Services\WebsiteContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class InquiryController extends Controller
{
    public function store(Request $request, WebsiteContent $site)
    {
        // Form feedback uses its validated locale context. A valid URL locale wins.
        $formLocale = $request->query('locale');
        if (! in_array($formLocale, ['en', 'bn'], true)) {
            $formLocale = $request->input('locale');
        }
        if (in_array($formLocale, ['en', 'bn'], true)) {
            app()->setLocale($formLocale);
            $request->session()->put('public_locale', $formLocale);
        }
        $validator = Validator::make($request->all(), [
            'locale' => ['required', Rule::in(['en', 'bn'])],
            'kind' => ['required', Rule::in(['appointment', 'contact'])],
            'name' => ['required', 'string', 'max:80'], 'phone' => ['required', 'string', 'max:40'],
            'message' => ['nullable', 'string', 'max:500'],
            'consultation_option_id' => ['exclude_unless:kind,appointment', 'required', 'integer', Rule::exists('consultation_options', 'id')->where('is_active', true)],
        ], [
            'required' => $site->text('contact.validation_required'), 'max' => $site->text('contact.validation_max'),
            'in' => $site->text('contact.validation_invalid'), 'exists' => $site->text('contact.validation_invalid'),
            'integer' => $site->text('contact.validation_invalid'), 'string' => $site->text('contact.validation_invalid'),
        ], [
            'name' => $site->text('contact.name'), 'phone' => $site->text('contact.phone'),
            'message' => $site->text('contact.message'), 'consultation_option_id' => $site->text('contact.topic'),
            'kind' => $site->text('contact.mode_label'), 'locale' => $site->text('ui.switch_to_'.app()->getLocale()),
        ]);
        $mode = $request->input('kind') === 'contact' ? 'contact' : 'appointment';
        $destination = app(PublicUrl::class)->route('contact', ['mode' => $mode]);
        if ($validator->fails()) {
            return redirect($destination)->withErrors($validator)->withInput($request->only(['kind', 'name', 'phone', 'message', 'consultation_option_id']));
        }
        $data = $validator->validated();
        $data['locale'] = app()->getLocale();
        // Synchronous save completes before any success response. No external redirect.
        Inquiry::create($data);

        return redirect($destination)->with('inquiry_success', $site->text('contact.success_'.$data['kind']));
    }
}
