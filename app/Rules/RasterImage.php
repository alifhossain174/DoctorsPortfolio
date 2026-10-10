<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

final class RasterImage implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $value instanceof UploadedFile || ! $value->isValid()) {
            $fail('Choose a valid JPEG, PNG or WebP image.');

            return;
        }
        $extension = strtolower($value->getClientOriginalExtension());
        $expected = ['jpg' => IMAGETYPE_JPEG, 'jpeg' => IMAGETYPE_JPEG, 'png' => IMAGETYPE_PNG, 'webp' => IMAGETYPE_WEBP][$extension] ?? null;
        if (! $expected || $value->getSize() > config('media.max_kilobytes') * 1024) {
            $fail('Use JPEG, PNG or WebP up to 5 MiB.');

            return;
        }
        $path = $value->getRealPath();
        if (! $path || ! is_readable($path)) {
            $fail('The uploaded image cannot be read. Please retry.');

            return;
        }
        $info = @getimagesize($path);
        $mime = (new \finfo(FILEINFO_MIME_TYPE))->file($path);
        $head = file_get_contents($path, false, null, 0, 12);
        if (! is_string($head)) {
            $fail('The uploaded image cannot be read. Please retry.');

            return;
        }
        $signature = match ($expected) {
            IMAGETYPE_JPEG => str_starts_with($head, "\xFF\xD8\xFF"),
            IMAGETYPE_PNG => str_starts_with($head, "\x89PNG\r\n\x1A\n"),
            IMAGETYPE_WEBP => str_starts_with($head, 'RIFF') && substr($head, 8, 4) === 'WEBP',
        };
        if (! $signature || ! $info || $info[2] !== $expected || $mime !== image_type_to_mime_type($expected)) {
            $fail('The file extension, raster signature and image MIME must match.');

            return;
        }
        if ($info[0] < 1 || $info[1] < 1 || max($info[0], $info[1]) > config('media.max_edge') || config('media.max_pixels') < $info[0] * $info[1]) {
            $fail('Images may be up to 6000 pixels on either edge and 24 million pixels in total.');
        }
    }
}
