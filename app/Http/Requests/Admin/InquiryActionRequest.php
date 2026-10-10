<?php

namespace App\Http\Requests\Admin;

use App\Models\Inquiry;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class InquiryActionRequest extends FormRequest
{
    private Inquiry $record;

    public function authorize(): bool
    {
        Gate::authorize('access-admin');
        $this->record = Inquiry::where('kind', config('inquiries.inboxes.'.$this->route('inbox').'.kind'))->findOrFail($this->route('inquiry'));

        return true;
    }

    public function inquiry(): Inquiry
    {
        return $this->record;
    }

    public function rules(): array
    {
        $rules = ['expected_version' => ['required', 'string', 'regex:/^[a-f0-9]{64}$/D']];
        if ($this->routeIs('admin.*.destroy')) {
            return [...$rules, 'confirm_delete' => ['accepted']];
        }
        if ($this->routeIs('admin.*.read')) {
            return [...$rules, 'confirm_status' => ['nullable', 'boolean']];
        }

        return [...$rules,
            'status' => ['required', Rule::in(config('inquiries.inboxes.'.$this->route('inbox').'.statuses'))],
            'internal_notes' => ['present', 'nullable', 'string', 'max:15000'],
            'confirmed_visit_at' => $this->record->kind === 'appointment' ? ['nullable', 'date_format:Y-m-d\TH:i'] : ['prohibited'],
            'confirm_status' => ['nullable', 'boolean'],
            'name' => ['prohibited'], 'phone' => ['prohibited'], 'message' => ['prohibited'], 'email' => ['prohibited'], 'locale' => ['prohibited'], 'kind' => ['prohibited'], 'consultation_label' => ['prohibited'], 'handled_by' => ['prohibited'],
        ];
    }

    public function destination(): string
    {
        return route('admin.'.$this->route('inbox').'.show', ['inquiry' => $this->record->id]);
    }

    public function safeInput(): array
    {
        $data = [];
        foreach (['status', 'internal_notes', 'confirmed_visit_at'] as $key) {
            if (is_string($this->input($key))) {
                $data[$key] = mb_substr($this->input($key), 0, $key === 'internal_notes' ? 15000 : 100);
            }
        }

        return $data;
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(redirect($this->destination())->withErrors($validator)->withInput($this->safeInput()));
    }
}
