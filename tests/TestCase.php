<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use Tests\Support\TestDatabase;

abstract class TestCase extends BaseTestCase
{
    public function createApplication()
    {
        $app = parent::createApplication();
        TestDatabase::assertSafe($app->environment(), config('database.default'), config('database.connections.mysql.database'), config('database.connections.mysql.url'));
        $connection = DB::connection();
        if ($connection->getDatabaseName() !== TestDatabase::NAME || $connection->selectOne('SELECT DATABASE() AS current_database')->current_database !== TestDatabase::NAME) {
            throw new \RuntimeException('Resolved database does not match the isolated test schema.');
        }

        return $app;
    }
}
