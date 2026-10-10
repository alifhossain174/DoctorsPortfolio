<?php

namespace Tests\Feature;

use App\Models\Service;
use App\Services\InitialWebsiteImporter;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ImportFailureTest extends TestCase
{
    public function test_failed_import_rolls_back_all_content_and_completion_marker(): void
    {
        if (DB::connection()->getDatabaseName() !== 'doctor_portfolio_testing') {
            throw new \RuntimeException('Test database guard failed.');
        }
        DB::beginTransaction();
        $dispatcher = Service::getEventDispatcher();
        Service::setEventDispatcher(clone $dispatcher);
        try {
            Service::creating(fn () => throw new \RuntimeException('Simulated import failure'));
            try {
                app(InitialWebsiteImporter::class)->import();
                $this->fail('Import should fail.');
            } catch (\RuntimeException $exception) {
                $this->assertSame('Simulated import failure', $exception->getMessage());
            }
            $this->assertSame(0, DB::table('site_settings')->count());
            $this->assertSame(0, DB::table('content_initializations')->count());
        } finally {
            Service::setEventDispatcher($dispatcher);
            DB::rollBack();
        }
    }
}
