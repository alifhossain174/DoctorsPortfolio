<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

Artisan::command('app:check-database', function () {
    if (! in_array(config('database.default'), ['mysql', 'mariadb'], true)) {
        $this->error('Configure a MySQL/MariaDB connection in the private .env.');

        return 1;
    }

    try {
        // Read-only: verify the configured schema/server, never create tables.
        $result = DB::selectOne('SELECT DATABASE() AS db_name, VERSION() AS server_version');
        if (! $result->db_name) {
            $this->error('No database is selected. Set DB_DATABASE in the private .env.');

            return 1;
        }

        $this->info('Connected to database: '.$result->db_name);
        $this->line('Server: '.$result->server_version);

        return 0;
    } catch (Throwable $exception) {
        // Raw exceptions may expose connection details; do not print or log them.
        $this->error('Database connection failed (code '.$exception->getCode().').');
        $this->line('Start XAMPP MySQL, verify the dedicated database exists, and set local DB_* values in .env.');
        $this->line('Then run: php artisan config:clear followed by php artisan app:check-database');

        return 1;
    }
})->purpose('Check the configured MySQL/MariaDB database without applying migrations');
