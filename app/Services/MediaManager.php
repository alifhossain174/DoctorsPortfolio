<?php

namespace App\Services;

use App\Http\Requests\Admin\MediaRequest;
use App\Models\MediaAsset;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

final class MediaManager
{
    public function upload(UploadedFile $image, array $text): MediaAsset
    {
        Gate::authorize('access-admin');
        $disk = null;
        $extension = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'][$image->getMimeType()];
        $path = 'images/'.now()->format('Y/m').'/'.bin2hex(random_bytes(16)).'.'.$extension;
        try {
            $disk = Storage::disk('public-media');
            // Apply the same code-owned safety rules when using a split public_html root.
            if (! $disk->exists('.htaccess') && ! $disk->put('.htaccess', file_get_contents(public_path('uploads/.htaccess')))) {
                throw new \RuntimeException;
            }
            $stream = fopen($image->getRealPath(), 'rb');
            try {
                if (! $disk->put($path, $stream)) {
                    throw new \RuntimeException;
                }
            } finally {
                if (is_resource($stream)) {
                    fclose($stream);
                }
            }
            $dimensions = getimagesize($image->getRealPath());

            return DB::transaction(fn () => MediaAsset::create([
                ...array_intersect_key($text, array_flip(MediaRequest::textFields())),
                'key' => 'upload:'.bin2hex(random_bytes(16)), 'disk' => 'public-media', 'path' => $path,
                'source_url' => null, 'mime_type' => $image->getMimeType(), 'size' => $image->getSize(),
                'width' => $dimensions[0], 'height' => $dimensions[1], 'original_filename' => mb_substr(basename(str_replace('\\', '/', $image->getClientOriginalName())), 0, 255),
            ]));
        } catch (\Throwable $error) {
            $cleanupFailed = false;
            try {
                if ($disk && $disk->exists($path) && ! $disk->delete($path)) {
                    throw new \RuntimeException;
                }
            } catch (\Throwable) {
                $cleanupFailed = true;
                Log::warning('Media upload cleanup requires attention.', ['path' => $path]);
            }
            throw ValidationException::withMessages(['image' => $cleanupFailed ? 'The image could not be saved. A temporary file needs hosting support cleanup before retrying.' : 'The image could not be saved. Check the upload directory and database, then retry.']);
        }
    }

    public function replaceReferences(MediaAsset $source, MediaAsset $target): int
    {
        Gate::authorize('access-admin');

        return DB::transaction(function () use ($source, $target) {
            $assets = MediaAsset::whereIn('id', [$source->id, $target->id])->orderBy('id')->lockForUpdate()->get();
            if ($assets->count() !== 2) {
                throw ValidationException::withMessages(['replacement_id' => 'Choose another valid image.']);
            }
            $source = $assets->firstWhere('id', $source->id);
            $target = $assets->firstWhere('id', $target->id);
            if ($target->url() === '' || ($target->path && ! Storage::disk('public-media')->exists($target->path))) {
                throw ValidationException::withMessages(['replacement_id' => 'The selected image is missing or invalid. Choose another image.']);
            }
            $count = 0;
            foreach (['posts', 'galleryImages', 'contentFields', 'settings', 'pages', 'ogPosts'] as $relation) {
                $query = $source->$relation();
                $foreignKey = $query->getForeignKeyName();
                $count += $query->update([$foreignKey => $target->id]);
            }

            return $count;
        });
    }

    /** Returns false only when a safely quarantined file needs manual cleanup after commit. */
    public function deleteUnused(MediaAsset $asset): bool
    {
        Gate::authorize('access-admin');
        $disk = null;
        $staged = null;
        $original = null;
        try {
            DB::transaction(function () use ($asset, &$disk, &$staged, &$original) {
                $asset = MediaAsset::lockForUpdate()->findOrFail($asset->id);
                if ($asset->inUse()) {
                    throw ValidationException::withMessages(['media' => 'This image is in use. Replace its references before deleting it.']);
                }
                if ($asset->path) {
                    if ($asset->disk !== 'public-media' || ! MediaAsset::safePath($asset->path)) {
                        throw new \RuntimeException;
                    }
                    $original = $asset->path;
                    $disk = Storage::disk('public-media');
                    if ($disk->exists($original)) {
                        $staged = '.deleting/'.bin2hex(random_bytes(16)).'.'.pathinfo($original, PATHINFO_EXTENSION);
                        if (! $disk->move($original, $staged)) {
                            throw new \RuntimeException;
                        }
                    }
                }
                $asset->delete();
            });
        } catch (\Throwable $error) {
            $recoveryFailed = false;
            if ($staged !== null) {
                try {
                    if ($disk->exists($staged)) {
                        if (! $disk->move($staged, $original)) {
                            throw new \RuntimeException;
                        }
                    }
                } catch (\Throwable) {
                    $recoveryFailed = true;
                    Log::warning('Media delete restoration requires attention.', ['path' => $original]);
                }
            }
            if ($error instanceof ValidationException) {
                throw $error;
            }
            throw ValidationException::withMessages(['media' => $recoveryFailed ? 'The image record was retained, but its file needs hosting support recovery before retrying.' : 'The image could not be removed. No database deletion was committed; check storage and retry.']);
        }
        if ($staged !== null) {
            try {
                if (! $disk->delete($staged)) {
                    throw new \RuntimeException;
                }
            } catch (\Throwable) {
                Log::warning('Media quarantine cleanup requires attention.', ['path' => $staged]);

                return false;
            }
        }

        return true;
    }
}
