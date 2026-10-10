<?php

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;
use Tests\Support\TestDatabase;

// Explicit local test setup only; no reset, seed or owner database connection.
require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();
TestDatabase::assertSafe($app->environment(), config('database.default'), config('database.connections.mysql.database'), config('database.connections.mysql.url'));
$configuration = config('database.connections.mysql');
if (! in_array($configuration['host'], ['127.0.0.1', 'localhost'], true)) {
    throw new RuntimeException('Test schema creation is limited to the existing local MySQL/MariaDB service.');
}
$pdo = new PDO('mysql:host='.$configuration['host'].';port='.$configuration['port'].';charset=utf8mb4', $configuration['username'], $configuration['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$pdo->exec('CREATE DATABASE IF NOT EXISTS doctor_portfolio_testing CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
if (DB::connection()->selectOne('SELECT DATABASE() AS current_database')->current_database !== TestDatabase::NAME) {
    throw new RuntimeException('Actual test connection guard failed.');
}
echo 'Dedicated test connection: doctor_portfolio_testing; '.DB::connection()->selectOne('SELECT VERSION() AS version')->version.PHP_EOL;
$exitCode = $kernel->call('migrate', ['--force' => true]);
echo $kernel->output();
exit($exitCode);
