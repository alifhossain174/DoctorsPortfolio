<?php

namespace App\Http\Requests\Admin;

use App\Rules\RasterImage;
use App\Services\ContentRegistry;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Gate;

final class MediaRequest extends FormRequest
{
    protected function getRedirectUrl(): string
    {
        $picker = $this->input('picker') === '1' ? ['picker' => '1'] : [];

        return $this->isMethod('POST') ? route('admin.media.create', $picker) : route('admin.media.edit', ['media' => $this->route('media'), ...$picker]);
    }

    public function authorize(): bool
    {
        return Gate::allows('access-admin');
    }

    protected function prepareForValidation(): void
    {
        // Quick uploads need only a file. The filename is a library label, not an
        // invented image description; all localized metadata remains editable.
        if ($this->isMethod('POST') && $this->input('quick_upload') === '1' && $this->file('image') instanceof UploadedFile) {
            $title = $this->input('title', []);
            if (is_array($title) && trim(is_string($title['en'] ?? null) ? $title['en'] : '') === '') {
                $filename = basename(str_replace('\\', '/', $this->file('image')->getClientOriginalName()));
                $title['en'] = mb_substr(pathinfo($filename, PATHINFO_FILENAME), 0, 200) ?: 'Uploaded image';
                $this->merge(['title' => $title]);
            }
        }
        foreach (self::textFields() as $field) {
            $translations = $this->input($field);
            if (is_array($translations)) {
                foreach ($translations as $language => $value) {
                    if (is_string($value)) {
                        $translations[$language] = trim($value);
                    }
                }
                $this->merge([$field => $translations]);
            }
        }
    }

    public static function textFields(): array
    {
        return ['title', 'alt_text', 'caption', 'provenance_note', 'stock_note'];
    }

    public function rules(): array
    {
        $rules = [];
        $rules['picker'] = ['sometimes', 'in:1'];
        $rules['quick_upload'] = $this->isMethod('POST') ? ['sometimes', 'in:1'] : ['prohibited'];
        foreach (app(ContentRegistry::class)->fields('MediaAsset') as $field => $definition) {
            if (in_array($field, self::textFields(), true)) {
                foreach ($definition['rules'] as $key => $value) {
                    $rules[$key] = $this->isMethod('POST') && $field === 'title' ? $value : array_map(fn ($rule) => $rule === 'required' ? 'sometimes' : $rule, $value);
                }
            }
        }
        // The full merged record still requires English when updating. Unknown languages fail.
        if ($this->isMethod('POST')) {
            $rules['image'] = ['required', 'file', 'max:'.config('media.max_kilobytes'), new RasterImage];
        } else {
            $rules['image'] = ['prohibited'];
        }
        foreach (['key', 'disk', 'path', 'source_url', 'mime_type', 'size', 'width', 'height', 'original_filename'] as $field) {
            $rules[$field] = ['prohibited'];
        }

        return $rules;
    }
}
