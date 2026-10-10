<?php

use App\Http\Controllers\InquiryController;
use App\Http\Controllers\PublicPageController;
use App\Http\Middleware\ResolvePublicLocale;
use Illuminate\Support\Facades\Route;

Route::middleware(ResolvePublicLocale::class)->group(function () {
    Route::get('/', [PublicPageController::class, 'show'])->name('home');
    foreach (['about', 'services', 'diagnostics', 'blog', 'gallery', 'contact'] as $page) {
        Route::get('/'.$page, [PublicPageController::class, 'show'])->defaults('page', $page)->name($page);
    }
    Route::get('/blog/{slug}', [PublicPageController::class, 'article'])->name('article');
    Route::post('/inquiries', [InquiryController::class, 'store'])->middleware('throttle:10,1')->name('inquiries.store');
});
