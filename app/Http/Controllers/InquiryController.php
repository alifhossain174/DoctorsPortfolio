<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Services\WebsiteContent;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InquiryController extends Controller
{
    public function store(Request $request, WebsiteContent $site)
    {
        $data = $request->validate([
            'kind' => ['required', Rule::in(['appointment', 'contact'])],
            'name' => ['required', 'string', 'max:80'], 'phone' => ['required', 'string', 'max:40'],
            'message' => ['nullable', 'string', 'max:500'],
            'consultation_option_id' => ['exclude_unless:kind,appointment', 'required', 'integer', Rule::exists('consultation_options', 'id')->where('is_active', true)],
        ], [
            'required' => $site->text('contact.validation_required'), 'max' => $site->text('contact.validation_max'),
            'in' => $site->text('contact.validation_invalid'), 'exists' => $site->text('contact.validation_invalid'),
            'integer' => $site->text('contact.validation_invalid'), 'string' => $site->text('contact.validation_invalid'),
        ]);
        $data['locale'] = app()->getLocale();
        // Synchronous save completes before any success response. No external redirect.
        Inquiry::create($data);

        return redirect()->route('contact', ['mode' => $data['kind']])->with('inquiry_success', $site->text('contact.success_'.$data['kind']));
    }
}
