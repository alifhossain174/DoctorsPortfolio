<?php

namespace App\Services;

use App\Models\ConsultationOption;
use App\Models\Inquiry;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class InquirySubmission
{
    public function save(array $data, string $token): Inquiry
    {
        $hash = hash('sha256', $token);
        $fingerprint = hash_hmac('sha256', json_encode($data, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE), $token);

        return DB::transaction(function () use ($data, $hash, $fingerprint) {
            // Shared anchor with consultation-option edits protects active choice/snapshot.
            SiteSetting::where('key', 'primary')->lockForUpdate()->firstOrFail();
            $existing = Inquiry::where('submission_token_hash', $hash)->first();
            if ($existing) {
                if (! hash_equals($existing->submission_fingerprint, $fingerprint)) {
                    throw ValidationException::withMessages(['submission_token' => app(WebsiteContent::class)->text('contact.submission_expired')]);
                }

                return $existing;
            }
            if ($data['kind'] === 'appointment' && ! ConsultationOption::where('is_active', true)->whereKey($data['consultation_option_id'])->lockForUpdate()->first()) {
                throw ValidationException::withMessages(['consultation_option_id' => app(WebsiteContent::class)->text('contact.validation_invalid')]);
            }
            $inquiry = new Inquiry($data);
            $inquiry->forceFill(['submission_token_hash' => $hash, 'submission_fingerprint' => $fingerprint])->save();

            return $inquiry;
        }, attempts: 3);
    }
}
