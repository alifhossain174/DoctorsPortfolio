<?php

use Illuminate\Support\Facades\Route;

// Temporary boot view; the DB-backed public layout is implemented in Step 5.
Route::view('/', 'scaffold')->name('home');
