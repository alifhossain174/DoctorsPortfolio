<?php

namespace App\Console\Commands;

use App\Services\InitialWebsiteImporter;
use Illuminate\Console\Command;

class RepairInitialContent extends Command
{
    protected $signature = 'website:repair-initial-content';

    protected $description = 'Explicitly restore missing original demo records; preserve every existing record';

    public function handle(InitialWebsiteImporter $importer): int
    {
        $result = $importer->import(repairMissing: true);
        $this->info('Missing original records restored: '.array_sum($result['created']).'. Existing records were preserved.');

        return self::SUCCESS;
    }
}
