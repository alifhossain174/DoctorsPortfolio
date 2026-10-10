<?php

namespace Database\Seeders;

use App\Models\ContentField;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PublicInterfaceContentSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $key = 'public-interface-v1';
            DB::table('content_initializations')->insertOrIgnore(['key' => $key, 'snapshot_sha256' => '', 'completed_at' => null]);
            $marker = DB::table('content_initializations')->where('key', $key)->lockForUpdate()->first();
            if ($marker->completed_at !== null) {
                return;
            }
            $json = file_get_contents(database_path('seeders/data/public-interface.json'));
            if ($json === false || ! mb_check_encoding($json, 'UTF-8')) {
                throw new \RuntimeException('Public interface initialization copy is missing or not UTF-8.');
            }
            foreach (json_decode($json, true, flags: JSON_THROW_ON_ERROR) as $field => $value) {
                ContentField::firstOrCreate(['key' => $field], ['value' => $value]);
            }
            DB::table('content_initializations')->where('key', $key)->update(['snapshot_sha256' => hash('sha256', $json), 'completed_at' => now()]);
        }, attempts: 3);
    }
}
