<?php

use App\Http\Controllers\InquiryController;
use App\Http\Controllers\LegacyPageController;
use App\Http\Controllers\PublicPageController;
use Illuminate\Support\Facades\Route;

Route::middleware('public.locale')->group(function () {
    Route::get('/', [PublicPageController::class, 'show'])->name('home');
    foreach (['about', 'services', 'diagnostics', 'blog', 'gallery', 'contact'] as $page) {
        Route::get('/'.$page, [PublicPageController::class, 'show'])->defaults('page', $page)->name($page);
    }
    Route::get('/blog/{post:slug}', [PublicPageController::class, 'article'])->name('article');
    foreach (['index' => 'home', 'about' => 'about', 'services' => 'services', 'diagnostics' => 'diagnostics', 'blog' => 'blog', 'gallery' => 'gallery', 'contact' => 'contact'] as $entry => $page) {
        Route::get('/'.$entry.'.html', [LegacyPageController::class, 'page'])->defaults('page', $page)->name('legacy.'.$page);
    }
    Route::get('/article.html', [LegacyPageController::class, 'article'])->name('legacy.article');
    Route::post('/inquiries', [InquiryController::class, 'store'])->middleware('throttle:10,1')->name('inquiries.store');
});
