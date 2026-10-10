<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class Inquiry extends Model
{
    public const STATUSES = ['new', 'read', 'confirmed', 'completed', 'cancelled', 'spam'];

    protected $fillable = ['kind', 'name', 'phone', 'email', 'consultation_option_id', 'message', 'locale'];

    protected $hidden = ['submission_token_hash', 'submission_fingerprint', 'public_reference', 'name', 'phone', 'email', 'message', 'consultation_key', 'consultation_label', 'internal_notes', 'confirmed_visit_at', 'handled_by', 'handled_at'];

    protected function casts(): array
    {
        return ['consultation_label' => 'array', 'consultation_option_id' => 'integer', 'confirmed_visit_at' => 'immutable_datetime', 'handled_at' => 'immutable_datetime', 'handled_by' => 'integer'];
    }

    protected static function booted(): void
    {
        static::creating(function (self $inquiry) {
            $inquiry->public_reference = (string) Str::ulid();
            $inquiry->status = 'new';
            if ($inquiry->kind === 'appointment') {
                $option = ConsultationOption::where('is_active', true)->findOrFail($inquiry->consultation_option_id);
                $inquiry->consultation_key = $option->key;
                $inquiry->consultation_label = ['en' => $option->localized('label', 'en'), 'bn' => $option->localized('label', 'bn')];
            } else {
                $inquiry->consultation_option_id = null;
                $inquiry->consultation_key = null;
                $inquiry->consultation_label = null;
            }
        });
        static::saving(function (self $inquiry) {
            // Hidden serialization is a separate concern from validating private stored attributes.
            $data = [];
            foreach (['kind', 'name', 'phone', 'email', 'message', 'locale', 'status', 'internal_notes', 'confirmed_visit_at', 'handled_by'] as $field) {
                $data[$field] = $inquiry->getAttribute($field);
            }
            Validator::make($data, [
                'kind' => ['required', 'in:appointment,contact'], 'name' => ['required', 'string', 'max:80'], 'phone' => ['required', 'string', 'max:40'], 'email' => ['nullable', 'email', 'max:255'], 'message' => ['nullable', 'string', 'max:500'], 'locale' => ['required', 'in:en,bn'], 'status' => ['nullable', 'in:'.implode(',', $inquiry->kind === 'contact' ? ['new', 'read', 'completed', 'spam'] : self::STATUSES)], 'internal_notes' => ['nullable', 'string', 'max:15000'], 'confirmed_visit_at' => $inquiry->kind === 'contact' ? ['prohibited'] : ['nullable', 'date'], 'handled_by' => ['nullable', 'integer', 'exists:users,id'],
            ])->validate();
        });
    }

    public function consultationOption(): BelongsTo
    {
        return $this->belongsTo(ConsultationOption::class);
    }

    public function handledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    public function statusChanges(): HasMany
    {
        return $this->hasMany(InquiryStatusChange::class);
    }
}
