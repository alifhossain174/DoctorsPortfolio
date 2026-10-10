<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\LegacyPageController;
use App\Http\Controllers\PublicPageController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [LoginController::class, 'create'])->name('login');
        Route::post('/login', [LoginController::class, 'store'])->name('login.store');
    });
    Route::middleware(['auth', 'admin', 'auth.session'])->group(function () {
        Route::get('/', DashboardController::class)->name('dashboard');
        Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [ProfileController::class, 'password'])->name('password.update');
    });
});

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
