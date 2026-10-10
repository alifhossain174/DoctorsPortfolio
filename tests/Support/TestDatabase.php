<?php

namespace Tests\Support;

/** Checked before connecting, opening transactions, migrating or creating fixtures. */
final class TestDatabase
{
    public const NAME = 'doctor_portfolio_testing';

    public static function assertSafe(string $environment, string $connection, ?string $database, ?string $url): void
    {
        if ($environment !== 'testing' || $connection !== 'mysql' || $database !== self::NAME || ($url !== null && $url !== '')) {
            throw new \RuntimeException('Tests require APP_ENV=testing, DB_CONNECTION=mysql, DB_DATABASE=doctor_portfolio_testing and no DB_URL. No database work was performed.');
        }
    }
}
