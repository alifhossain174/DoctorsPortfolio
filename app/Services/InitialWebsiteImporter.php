<?php

namespace App\Services;

use App\Models\CareCard;
use App\Models\Category;
use App\Models\ConsultationOption;
use App\Models\ContentField;
use App\Models\GalleryImage;
use App\Models\MediaAsset;
use App\Models\NavigationItem;
use App\Models\Page;
use App\Models\PageSection;
use App\Models\Post;
use App\Models\PostSlugRedirect;
use App\Models\Service;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/** Explicit initialization only. Never call this service from a web request. */
final class InitialWebsiteImporter
{
    public const KEY = 'original-website-v1';

    public function import(bool $repairMissing = false): array
    {
        return DB::transaction(function () use ($repairMissing) {
            // Serialize concurrent explicit initialization/repair commands.
            DB::table('content_initializations')->insertOrIgnore(['key' => self::KEY, 'snapshot_sha256' => '', 'completed_at' => null]);
            $marker = DB::table('content_initializations')->where('key', self::KEY)->lockForUpdate()->first();
            if ($marker->completed_at !== null && ! $repairMissing) {
                return ['skipped' => true, 'created' => []];
            }
            $json = file_get_contents(database_path('seeders/data/initial-website.json'));
            if ($json === false || ! mb_check_encoding($json, 'UTF-8')) {
                throw new RuntimeException('Initialization snapshot is missing or not UTF-8.');
            }
            $snapshot = json_decode($json, true, flags: JSON_THROW_ON_ERROR);
            $digest = hash('sha256', $json);
            if (($snapshot['version'] ?? null) !== 1 || count($snapshot['mapping'] ?? []) !== 81) {
                throw new RuntimeException('Unexpected initialization snapshot inventory.');
            }
            if ($marker->completed_at !== null && $marker->snapshot_sha256 !== $digest) {
                throw new RuntimeException('Repair requires the original initialization snapshot checksum.');
            }
            $models = ['media_assets' => MediaAsset::class, 'site_settings' => SiteSetting::class, 'pages' => Page::class, 'content_fields' => ContentField::class, 'page_sections' => PageSection::class, 'navigation_items' => NavigationItem::class, 'care_cards' => CareCard::class, 'services' => Service::class, 'consultation_options' => ConsultationOption::class, 'categories' => Category::class, 'posts' => Post::class, 'gallery_images' => GalleryImage::class];
            $created = [];
            $importedAt = now();
            foreach ($models as $table => $model) {
                $created[$table] = 0;
                foreach ($snapshot['records'][$table] as $row) {
                    $identity = array_key_exists('key', $row) ? 'key' : 'slug';
                    if ($model::where($identity, $row[$identity])->exists()) {
                        continue; // Preserve edits, timestamps, publication and visibility.
                    }
                    if ($table === 'posts' && PostSlugRedirect::where('slug', $row['slug'])->whereHas('post')->exists()) {
                        continue; // Explicit repair must also recognize an owner's renamed original article.
                    }
                    foreach (['_media' => [MediaAsset::class, 'key', 'media_asset_id'], '_page' => [Page::class, 'key', 'page_id'], '_category' => [Category::class, 'slug', 'category_id'], '_portrait' => [MediaAsset::class, 'key', 'portrait_media_asset_id']] as $reference => [$related, $column, $foreignKey]) {
                        if (isset($row[$reference])) {
                            $row[$foreignKey] = $related::where($column, $row[$reference])->firstOrFail()->id;
                            unset($row[$reference]);
                        }
                    }
                    if ($table === 'posts' && $row['status'] === 'published') {
                        $row['published_at'] = $importedAt; // Initialization time, never a historical article date.
                    }
                    $model::create($row);
                    $created[$table]++;
                }
            }
            if ($marker->completed_at === null) {
                DB::table('content_initializations')->where('key', self::KEY)->update(['snapshot_sha256' => $digest, 'completed_at' => $importedAt]);
            }

            return ['skipped' => false, 'created' => $created];
        }, attempts: 3);
    }
}
