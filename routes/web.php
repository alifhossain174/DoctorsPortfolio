<?php

use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\CareCollectionController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\NavigationController;
use App\Http\Controllers\Admin\PageContentController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SettingsController;
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
        Route::resource('media', MediaController::class)->parameters(['media' => 'media'])->except('show');
        Route::put('/media/{media}/references', [MediaController::class, 'replace'])->name('media.replace');
        Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
        Route::put('/settings/{group}', [SettingsController::class, 'update'])->name('settings.update');
        Route::get('/pages', [PageContentController::class, 'index'])->name('pages.index');
        Route::get('/pages/{page:key}/edit', [PageContentController::class, 'edit'])->name('pages.edit');
        Route::put('/pages/{page:key}', [PageContentController::class, 'update'])->name('pages.update');
        Route::resource('navigation', NavigationController::class)->parameters(['navigation' => 'navigationItem'])->except('show');
        Route::resource('categories', CategoryController::class)->except('show');
        Route::resource('gallery', GalleryController::class)->parameters(['gallery' => 'image'])->except('show');
        Route::patch('/gallery/{image}/move', [GalleryController::class, 'move'])->name('gallery.move');
        Route::patch('/gallery/{image}/visibility', [GalleryController::class, 'visibility'])->name('gallery.visibility');
        Route::put('/categories/{category}/reassign', [CategoryController::class, 'reassign'])->name('categories.reassign');
        Route::resource('articles', ArticleController::class)->parameters(['articles' => 'post'])->except('show');
        Route::patch('/articles/{post}/publication', [ArticleController::class, 'publication'])->name('articles.publication');
        Route::get('/articles/{post}/preview', [ArticleController::class, 'preview'])->middleware('public.locale')->name('articles.preview');
        foreach (array_keys(config('care_collections')) as $collection) {
            Route::get('/'.$collection, [CareCollectionController::class, 'index'])->defaults('collection', $collection)->name($collection.'.index');
            Route::get('/'.$collection.'/create', [CareCollectionController::class, 'create'])->defaults('collection', $collection)->name($collection.'.create');
            Route::post('/'.$collection, [CareCollectionController::class, 'store'])->defaults('collection', $collection)->name($collection.'.store');
            Route::get('/'.$collection.'/{item}/edit', [CareCollectionController::class, 'edit'])->whereNumber('item')->defaults('collection', $collection)->name($collection.'.edit');
            Route::put('/'.$collection.'/{item}', [CareCollectionController::class, 'update'])->whereNumber('item')->defaults('collection', $collection)->name($collection.'.update');
            Route::delete('/'.$collection.'/{item}', [CareCollectionController::class, 'destroy'])->whereNumber('item')->defaults('collection', $collection)->name($collection.'.destroy');
            Route::patch('/'.$collection.'/{item}/move', [CareCollectionController::class, 'move'])->whereNumber('item')->defaults('collection', $collection)->name($collection.'.move');
            Route::patch('/'.$collection.'/{item}/visibility', [CareCollectionController::class, 'visibility'])->whereNumber('item')->defaults('collection', $collection)->name($collection.'.visibility');
        }
    });
});

Route::middleware('public.locale')->group(function () {
    Route::get('/', [PublicPageController::class, 'show'])->name('home');
    foreach (['about', 'services', 'diagnostics', 'blog', 'gallery', 'contact'] as $page) {
        Route::get('/'.$page, [PublicPageController::class, 'show'])->defaults('page', $page)->name($page);
    }
    Route::get('/blog/{post}', [PublicPageController::class, 'article'])->name('article');
    foreach (['index' => 'home', 'about' => 'about', 'services' => 'services', 'diagnostics' => 'diagnostics', 'blog' => 'blog', 'gallery' => 'gallery', 'contact' => 'contact'] as $entry => $page) {
        Route::get('/'.$entry.'.html', [LegacyPageController::class, 'page'])->defaults('page', $page)->name('legacy.'.$page);
    }
    Route::get('/article.html', [LegacyPageController::class, 'article'])->name('legacy.article');
    Route::post('/inquiries', [InquiryController::class, 'store'])->middleware('throttle:inquiries')->name('inquiries.store');
});
