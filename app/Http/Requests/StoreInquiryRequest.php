<?php

namespace App\Http\Requests;

use App\Rules\InquiryPhone;
use App\Services\InquirySubmissionTokens;
use App\Services\PublicUrl;
use App\Services\WebsiteContent;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StoreInquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public static function resolveFormLocale(Request $request): void
    {
        $locale = $request->query('locale');
        if (! in_array($locale, ['en', 'bn'], true)) {
            $locale = $request->input('locale');
        }
        if (! in_array($locale, ['en', 'bn'], true)) {
            $locale = $request->session()->get('public_locale');
        }
        if (! in_array($locale, ['en', 'bn'], true)) {
            $locale = app(WebsiteContent::class)->settings?->default_locale ?? 'en';
        }
        if (in_array($locale, ['en', 'bn'], true)) {
            app()->setLocale($locale);
            $request->session()->put('public_locale', $locale);
        }
    }

    public static function destination(Request $request): string
    {
        self::resolveFormLocale($request);

        return app(PublicUrl::class)->route('contact', ['kind' => $request->input('kind') === 'contact' ? 'contact' : 'appointment']);
    }

    public static function safeOldInput(Request $request): array
    {
        $input = [];
        foreach (['kind', 'name', 'phone', 'message', 'consultation_option_id'] as $key) {
            $value = $request->input($key);
            if (is_string($value) || is_int($value)) {
                $input[$key] = mb_substr((string) $value, 0, 2000);
            }
        }

        return $input;
    }

    public static function feedback(Request $request, string $key)
    {
        $destination = self::destination($request);

        return redirect($destination)->withErrors(['submission' => app(WebsiteContent::class)->text($key)])->withInput(self::safeOldInput($request));
    }

    protected function prepareForValidation(): void
    {
        self::resolveFormLocale($this);
    }

    public function rules(): array
    {
        return [
            'locale' => ['required', Rule::in(['en', 'bn'])],
            'kind' => ['required', Rule::in(['appointment', 'contact'])],
            'name' => ['required', 'string', 'max:80'],
            'phone' => ['required', 'string', 'max:40', new InquiryPhone],
            'message' => ['required_if:kind,contact', 'nullable', 'string', 'max:500'],
            'consultation_option_id' => ['exclude_unless:kind,appointment', 'required', 'integer', Rule::exists('consultation_options', 'id')->where('is_active', true)],
            'company_website' => ['prohibited'],
            'submission_token' => ['required', function ($attribute, $value, $fail) {
                if (! app(InquirySubmissionTokens::class)->valid($this, $value)) {
                    $fail(app(WebsiteContent::class)->text('contact.submission_expired'));
                }
            }],
        ];
    }

    public function messages(): array
    {
        $site = app(WebsiteContent::class);
        $messages = [];
        foreach (['in', 'exists', 'integer', 'string'] as $rule) {
            $messages[$rule] = $site->text('contact.validation_invalid');
        }

        return [...$messages, 'required' => $site->text('contact.validation_required'), 'required_if' => $site->text('contact.validation_required'), 'max' => $site->text('contact.validation_max'), 'company_website.prohibited' => $site->text('contact.submission_rejected'), 'submission_token.required' => $site->text('contact.submission_expired')];
    }

    public function attributes(): array
    {
        $site = app(WebsiteContent::class);

        return ['name' => $site->text('contact.name'), 'phone' => $site->text('contact.phone'), 'message' => $site->text('contact.message'), 'consultation_option_id' => $site->text('contact.topic'), 'kind' => $site->text('contact.mode_label'), 'locale' => $site->text('ui.switch_to_'.app()->getLocale())];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(redirect(self::destination($this))->withErrors($validator)->withInput(self::safeOldInput($this)));
    }
}
