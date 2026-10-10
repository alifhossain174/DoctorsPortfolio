<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;

/** Future CRUD controllers inherit this even when someone registers a route incorrectly. */
abstract class AdminController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return ['auth', 'admin', 'auth.session'];
    }
}
