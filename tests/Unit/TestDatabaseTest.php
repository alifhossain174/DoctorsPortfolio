<?php

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Tests\Support\TestDatabase;

final class TestDatabaseTest extends TestCase
{
    public function test_only_the_explicit_mysql_testing_target_is_allowed(): void
    {
        TestDatabase::assertSafe('testing', 'mysql', TestDatabase::NAME, null);
        $this->addToAssertionCount(1);
    }

    #[DataProvider('unsafeTargets')]
    public function test_unsafe_configuration_fails_before_database_work(string $environment, string $connection, ?string $database, ?string $url): void
    {
        $this->expectException(\RuntimeException::class);
        TestDatabase::assertSafe($environment, $connection, $database, $url);
    }

    public static function unsafeTargets(): array
    {
        return [
            'owner development' => ['testing', 'mysql', 'doctor_portfolio', null],
            'unapproved test name' => ['testing', 'mysql', 'other_testing', null],
            'sqlite substitute' => ['testing', 'sqlite', TestDatabase::NAME, null],
            'production environment' => ['production', 'mysql', TestDatabase::NAME, null],
            'URL overrides name' => ['testing', 'mysql', TestDatabase::NAME, 'mysql://example.invalid/doctor_portfolio'],
            'missing name' => ['testing', 'mysql', null, null],
        ];
    }
}
