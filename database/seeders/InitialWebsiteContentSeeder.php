<?php

namespace Database\Seeders;

use App\Services\InitialWebsiteImporter;
use Illuminate\Database\Seeder;

class InitialWebsiteContentSeeder extends Seeder
{
    public function run(): void
    {
        $result = app(InitialWebsiteImporter::class)->import();
        $this->command?->info($result['skipped'] ? 'Website initialization already completed; existing content was not changed.' : 'Website content initialized: '.array_sum($result['created']).' records.');
    }
}
