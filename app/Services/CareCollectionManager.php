<?php

namespace App\Services;

use App\Models\CmsModel;
use App\Models\SiteSetting;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

/** Small ordered collections only; every mutation locks the same existing website anchor. */
final class CareCollectionManager
{
    private function lockedRows(array $definition): Collection
    {
        // Serializes even an empty collection, without changing any shared setting.
        SiteSetting::where('key', 'primary')->lockForUpdate()->firstOrFail();

        return $definition['model']::orderBy('sort_order')->orderBy('id')->lockForUpdate()->get();
    }

    private function normalize(Collection $rows): void
    {
        foreach ($rows->values() as $position => $record) {
            if ($record->sort_order !== $position) {
                $record->update(['sort_order' => $position]);
            }
        }
    }

    public function create(array $definition, array $input): CmsModel
    {
        Gate::authorize('access-admin');

        return DB::transaction(function () use ($definition, $input) {
            $rows = $this->lockedRows($definition);
            $this->normalize($rows);
            if ($definition['owner'] !== 'ConsultationOption') {
                $input['key'] = 'custom-'.bin2hex(random_bytes(16));
            }
            foreach ($definition['fields'] as $field) {
                if (is_array($input[$field] ?? null)) {
                    $input[$field] = array_replace(['en' => null, 'bn' => null], $input[$field]);
                }
            }
            $input['sort_order'] = $rows->count();
            $record = $definition['model']::create($input);
            app()->forgetInstance(WebsiteContent::class);

            return $record;
        }, 3);
    }

    public function update(array $definition, CmsModel $record, array $input, bool $confirmed): void
    {
        Gate::authorize('access-admin');
        DB::transaction(function () use ($definition, $record, $input, $confirmed) {
            $rows = $this->lockedRows($definition);
            $current = $rows->firstWhere('id', $record->id) ?? $record->newQuery()->findOrFail($record->id);
            if ($current->getAttribute($definition['state']) && array_key_exists($definition['state'], $input) && ! $input[$definition['state']] && ! $confirmed) {
                throw ValidationException::withMessages(['confirm_disable' => 'Confirm disabling this record before saving.']);
            }
            app(CmsEditorSaver::class)->update($current, $input, 'item');
            $this->normalize($rows);
        }, 3);
    }

    public function move(array $definition, CmsModel $record, string $direction): bool
    {
        Gate::authorize('access-admin');

        return DB::transaction(function () use ($definition, $record, $direction) {
            $rows = $this->lockedRows($definition)->values();
            $position = $rows->search(fn ($row) => $row->id === $record->id);
            if ($position === false) {
                $record->newQuery()->findOrFail($record->id);
            }
            $next = $position + ($direction === 'up' ? -1 : 1);
            if ($next < 0 || $next >= $rows->count()) {
                return false;
            }
            $other = $rows[$next];
            $rows[$next] = $rows[$position];
            $rows[$position] = $other;
            $this->normalize($rows);
            app()->forgetInstance(WebsiteContent::class);

            return true;
        }, 3);
    }

    public function delete(array $definition, CmsModel $record): void
    {
        Gate::authorize('access-admin');
        DB::transaction(function () use ($definition, $record) {
            $rows = $this->lockedRows($definition);
            $current = $rows->firstWhere('id', $record->id) ?? $record->newQuery()->findOrFail($record->id);
            // ConsultationOption's existing nullOnDelete FK retains inquiries and snapshots.
            $current->delete();
            $this->normalize($rows->reject(fn ($row) => $row->id === $current->id));
            app()->forgetInstance(WebsiteContent::class);
        }, 3);
    }
}
