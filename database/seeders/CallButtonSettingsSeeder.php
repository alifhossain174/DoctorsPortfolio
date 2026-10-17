<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CallButtonSettingsSeeder extends Seeder
{
    public function run(): void
    {
        // Initialize this new label only. Never reset an edited label or number.
        DB::transaction(function () {
            $settings = SiteSetting::where('key', 'primary')->lockForUpdate()->first();
            if ($settings && $settings->call_button_label === null) {
                $settings->update(['call_button_label' => ['en' => 'Call now', 'bn' => 'কল করুন']]);
            }
        });
    }
}
