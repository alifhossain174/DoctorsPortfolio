<?php

namespace App\Services;

use App\Models\Inquiry;
use App\Models\InquiryStatusChange;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

final class InquiryManager
{
    public function version(Inquiry $inquiry): string
    {
        return hash_hmac('sha256', json_encode([$inquiry->id, $inquiry->status, $inquiry->internal_notes, $inquiry->confirmed_visit_at?->format('c'), $inquiry->handled_by, $inquiry->handled_at?->format('c'), $inquiry->updated_at?->format('c')], JSON_THROW_ON_ERROR), (string) config('app.key'));
    }

    public function availableStatuses(Inquiry $inquiry): array
    {
        return array_unique([$inquiry->status, ...config('inquiries.transitions.'.$inquiry->kind.'.'.$inquiry->status, [])]);
    }

    public function update(int $id, string $kind, array $data, User $actor): Inquiry
    {
        Gate::forUser($actor)->authorize('access-admin');

        return DB::transaction(function () use ($id, $kind, $data, $actor) {
            $inquiry = Inquiry::where('kind', $kind)->lockForUpdate()->findOrFail($id);
            $this->assertVersion($inquiry, $data['expected_version']);
            $status = $data['status'] ?? $inquiry->status;
            if (! in_array($status, $this->availableStatuses($inquiry), true)) {
                throw ValidationException::withMessages(['status' => 'This status transition is not allowed. Reload the request and choose an available status.']);
            }
            $changedStatus = $status !== $inquiry->status;
            $sensitive = in_array($status, ['confirmed', 'cancelled', 'spam'], true) || in_array($inquiry->status, ['completed', 'cancelled', 'spam'], true);
            if ($changedStatus && $sensitive && ! ($data['confirm_status'] ?? false)) {
                throw ValidationException::withMessages(['confirm_status' => 'Confirm this status change before saving.']);
            }
            $time = array_key_exists('confirmed_visit_at', $data) ? $data['confirmed_visit_at'] : $inquiry->confirmed_visit_at;
            if ($kind === 'contact' && $time !== null) {
                throw ValidationException::withMessages(['confirmed_visit_at' => 'A general contact message has no appointment time.']);
            }
            if (is_string($time) && $time !== '') {
                $time = CarbonImmutable::createFromFormat('!Y-m-d\TH:i', $time, config('app.timezone'));
                if ($time->lt($inquiry->created_at) || $time->year > 2100) {
                    throw ValidationException::withMessages(['confirmed_visit_at' => 'Use a valid visit time after this request was received, no later than year 2100.']);
                }
            }
            if ($time && ! in_array($status, ['confirmed', 'completed', 'cancelled'], true)) {
                throw ValidationException::withMessages(['confirmed_visit_at' => 'Clear the confirmed visit time when returning to an unconfirmed status.']);
            }
            $from = $inquiry->status;
            $inquiry->forceFill(['status' => $status, 'internal_notes' => $data['internal_notes'] ?? $inquiry->internal_notes, 'confirmed_visit_at' => $time ?: null]);
            // Explicit blank notes clear notes; omitted notes preserve them (e.g. Mark read).
            if (array_key_exists('internal_notes', $data)) {
                $inquiry->internal_notes = $data['internal_notes'];
            }
            if ($inquiry->isDirty(['status', 'internal_notes', 'confirmed_visit_at'])) {
                $inquiry->forceFill(['handled_by' => $actor->id, 'handled_at' => now()])->save();
                if ($changedStatus) {
                    InquiryStatusChange::create(['inquiry_id' => $inquiry->id, 'from_status' => $from, 'to_status' => $status, 'actor_id' => $actor->id, 'created_at' => now()]);
                }
            }

            return $inquiry;
        }, attempts: 3);
    }

    public function delete(int $id, string $kind, string $version, User $actor): void
    {
        Gate::forUser($actor)->authorize('access-admin');
        DB::transaction(function () use ($id, $kind, $version) {
            $inquiry = Inquiry::where('kind', $kind)->lockForUpdate()->findOrFail($id);
            $this->assertVersion($inquiry, $version);
            $inquiry->delete(); // Its local status history is deleted; content/options/users survive.
        }, attempts: 3);
    }

    private function assertVersion(Inquiry $inquiry, string $version): void
    {
        if (! hash_equals($this->version($inquiry), $version)) {
            throw ValidationException::withMessages(['expected_version' => 'Another save changed this request. Reload before saving or deleting.']);
        }
    }
}
